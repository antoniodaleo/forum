<?php
    // Gravar o Editar post
    session_start(); 

    if(!isset($_SESSION['users']))
    {
        include 'cabecalho.php'; 
        echo '<div class="error">Náo tem permissão para ver o conteud do forum.<br><br></div>
            <a href="index.php">Voltar</a>'; 
        include 'rodape.php'; 
        exit;
    }

    //-------------------------------------
    include 'cabecalho.php';
        // Dados do utilizador que esta logado
        echo '<div class="dados_utilizador">
                 <img src="img/avatar/'.$_SESSION['avatar'].'"><span>'.$_SESSION['users'].'</span> | 
                 <a href="logout.php">Logout</a>
             </div>';

        // ATTRAVERSO editor_post.php PASSO I DATI DEL FORMULARIO ALLA PAGINA post_add.php     
        $id_user = $_POST['id_user']; 
        $id_post = $_POST['id_post']; 
        $titulo = $_POST['txt_titulo']; 
        $mensagem = $_POST['txt_mensagem']; 
        $editar = false;

        //VERIFICAR SE OS CAMPOS ESTÃO PREENCHIDOS
        if($titulo == "" || $mensagem=="")
        {
            echo '<div class ="error">
                    Não foram preenchidos os campos.<br><br>
                    <a href="editor_post.php">Tente novamente</a>
                 </div> '; 
             include 'rodape.php';
             exit; 
        }


    // ABRIR LIGAÇÃO Á BD
    include 'config.php';
    
    $conn = new PDO("mysql:dbname=$banco;host=$host", $user, $senha );
    
    // Se quest id_post é -1 vuol dire che non esiste 
    if($id_post == -1)
    {
        //Vai buscar o id_post disponivel
        $robo = $conn->prepare("SELECT MAX(id) AS MaxID FROM post");
        $robo->execute(); 
        $id_post = $robo->fetch(PDO::FETCH_ASSOC)['MaxID'];
        
        
        if($id_post == null)
            $id_post = 0;
            
        else 
            $id_post++;
            $editar = false ;    
    }
    else
    {
        $editar = true; 
    }

    // Se for para editar -----------------
    if(!$editar)
    {
        $data = date('Y-m-d h:i:s', time()); 

        $robo = $conn->prepare("INSERT INTO post VALUES(?,?,?,?,?)");
        $robo->bindParam(1, $id_post, PDO::PARAM_INT);
        $robo->bindParam(2, $id_user, PDO::PARAM_INT);
        $robo->bindParam(3, $titulo, PDO::PARAM_STR);
        $robo->bindParam(4, $mensagem, PDO::PARAM_STR);
        $robo->bindParam(5, $data, PDO::PARAM_STR);
        $robo->execute(); 

        echo '
            <div class="login_sucesso">
                Post gravado com sucesso. <br><br>
                <a href="forum.php">Voltar</a>
            </div>'; 

    }
    else
    {
        // Atualizar os dados do post escolhido na bd
        // Data atual
        $data = date('Y-m-d h:i:s', time());

        $robo = $conn->prepare("UPDATE post SET titulo= :tit, mensagem =:mes, data_post=:dat WHERE id =:pid");
        $robo->bindParam(":pid", $id_post, PDO::PARAM_INT); 
        $robo->bindParam(":tit", $titulo, PDO::PARAM_STR); 
        $robo->bindParam(":mes", $mensagem, PDO::PARAM_STR); 
        $robo->bindParam(":dat", $data, PDO::PARAM_STR);
        $robo->execute(); 
        echo '
        <div class="login_sucesso">
            Post modificado com sucesso. <br><br>
            <a href="forum.php">Voltar</a>
        </div>
    '; 
    }



   


    //-------------------------------------
    include 'rodape.php'; 
?>