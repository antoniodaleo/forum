<?php
    //FORUM
    session_start(); 

    if(!isset($_SESSION['users']))
    {
        include 'cabecalho.php'; 
        echo '<div class="error">Náo tem permissão para ver o conteud do forum.<br><br></div>
            <a href="index.php">Voltar</a>'; 
        include 'rodape.php'; 
        exit;
    }
    //-------------------------------------------------

    include 'cabecalho.php';
    //-------------------------------------------------
    // Dados do utilizador que esta logado
    echo '<div class="dados_utilizador">
            <img src="img/avatar/'.$_SESSION['avatar'].'"><span>'.$_SESSION['users'].'</span> | 
            <a href="logout.php">Logout</a>
        </div>';

    //Novo post----------------------------------------------------
    echo '<div class="novo_post"><a href="editor_post.php">Novo post</a>  </div>'; 


    //-------------------------------------------------
    // Aprensetação dos posts do nosso fórum
    include 'config.php';
    $conn = new PDO("mysql:dbname=$banco;host=$host", $user, $senha);  
    // Buscar os dados
    $sql ="SELECT p.id, p.id_user, p.titulo, p.mensagem,
            p.data_post, u.username, u.avatar 
            FROM post p INNER JOIN users u ON p.id_user = u.id ORDER BY data_post DESC";

    $robo = $conn->prepare($sql); 
    $robo-> execute();  

    $conn = null ; 

    if($robo->rowCount()==0)
    {
        echo '<div class = "login_sucesso">
                Nao existem post no forum
              </div><br>'; 
    }else{
        // Foram encontrados post 
        foreach($robo as $rs)
        {
           // dados do post
           
            $id_post = $rs['id']; 
            $id_user = $rs['id_user'];
            $titulo = $rs['titulo']; 
            $mensagem = $rs['mensagem']; 
            $data_post = $rs['data_post']; 
            //Mi prendi anche il nome e l avatar attraverso dell inner join
            $username = $rs['username']; 
            $avatar = $rs['avatar']; 

            // MI CREI IL DIV/CONTENITORE DEI POST 
            echo '<div class="post">';
            
                echo '<img src="img/avatar/'.$avatar.'">';
                echo '<span id="post_username">'.$username.'</span>';
                echo '<span id="post_titulo">'.$titulo.'</span>';
                echo '<hr>';
                echo '<div id="post_mensagem">'.$mensagem.'</div>';
                
                // Data e hora da mensagem/post
                echo '<div id="post-data">';
                    //Adicionar o link editar para o utilizador ativo
                    // Se l ID USER del post pubblicato é uguale all ID dell USER LOGATO...
                    if($id_user == $_SESSION['id_user'])
                    {
                        echo '<a href="editor_post.php?pid='.$id_post.'">Editar</a>';
                    }
                    
                    
                    echo $data_post.' |  '; 
                    echo '<span>'.$id_post.'</span>'; 
                echo '</div>';  
                            
            echo '</div><hr>';
        }

    }
    //-------------------------------------------------
    include 'rodape.php'; 
   
    
  
?>