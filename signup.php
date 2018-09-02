<?php
    // SIGN UP
    
    session_start(); 
    
    unset($_SESSION['users']);
    
    //$id_sessao = session_id();
    //if(empty($id_sessao)) session_start(); 
    
    
    //cabecalho -----------------------------------------
    include 'cabecalho.php'; 

    // Verificar se foram inseridos dados de utilizador
    // Se nn é stato cliccato il bottone mi presenti il formulario altrimenti effettui la registrazion
    if(!isset($_POST['btn_submit']))
    {
       ApresentarFormulario();  
    }
    else
    {
        RegistrarUtilizador(); 
    }

    //rodape --------------------------------------------
    include 'rodape.php'; 


    //funções -------------------------------------------
    function ApresentarFormulario()
    {
        //Apresenta o formulario para adição de novo utilizador
        echo '
            <form class="form_signup" method="post" action="signup.php?a=signup" enctype="multipart/form-data">
                <h3>SIGN UP</h3><hr><br>

                Username:<br><input type="text" name="txt_user"><br><br>
                Password:<br><input type="password" name="txt_password1"><br><br>
                Rescrever Password:<br><input type="password" name="txt_password2"><br><br>
                
                <input type="hidden" name="MAX_FILE_SIZE" value="50000">
                Avatar: <input type="file" name="imagem_avatar"><br>
                <small>[Imagem do tipo <strong>JPG</strong>, tamanho máximo: 50Kb]</small><br><br>

                <input type="submit" name="btn_submit" value="Registrar"><br><br>
                <a href="index.php">Voltar</a>

            </form>
        
        '; 

    }

    // ---------------------------------------------------
    function RegistrarUtilizador()
    {
        $utilizador = $_POST['txt_user']; 
        $password_1 = $_POST['txt_password1']; 
        $password_2 = $_POST['txt_password2'];
        //AVATAR
        $avatar = $_FILES['imagem_avatar'];
        $erro=false; 

        // VERIFICAÇÃO DE ERROS
        if($utilizador =="" or $password_1 =="" or $password_2=="")
        {
            echo '<div class="error">Não foram preenchidos os campos necessários</div>'; 
            $erro = true; 
        }
        else if($password_1 != $password_2)
        {
            echo '<div class="error">Não foram preenchidas as password necessárias</div>';
            $erro = true; 
        }
        //---------------------------------------------------------------------
        // Erros do Avatar
        else if($avatar['name']!="" && $avatar['type'] != "image/jpeg" )
        {
            echo '<div class="error">Ficheiro de imagem inválido</div>';
            $erro = true; 
        }
        else if ($avatar['name'] != "" && $avatar['size']> $_POST['MAX_FILE_SIZE'])
        {   
            echo '<div class="error">Ficheiro de imagem maior do que permitido</div>';
            $erro = true; 
        }
        // IN QUANTO L ERRO É TRUE MI PRESENTI IL FORMULARIO
        if($erro)
        {
            ApresentarFormulario();
            include 'rodape.php';
            exit; 
        }

        //----------------------------------
        // PROCESSAMENTO DO REGISTRO DO NOVO UTILIZADOR
        //--------------------------------------
        include 'config.php';

        $conn = new PDO("mysql:dbname=$banco;host=$host", $user, $senha); 

        // Verificar se ja existe um username
        $robo = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $robo->bindParam(1,$utilizador, PDO::PARAM_STR);
        $robo->execute(); 
        
        if($robo->rowCount() !=0)
        {
            echo '<div>Ja esiste um membro</div>';
            $conn = null; 
            ApresentarFormulario(); 
            // Incluir rodape do forum
            exit(); 

        }else {
            // registro do novo utilizador
            $robo = $conn->prepare("SELECT MAX(id) AS MaxId FROM users");
            $robo->execute(); 
            $ultimo_id = $robo->fetch(PDO::FETCH_ASSOC)['MaxId']; 

            if($ultimo_id == null) 
                // Se non ci stanno id attribuisci 0
                $ultimo_id = 0; 

            else
                // Altrimenti aggiungi un id 
                $ultimo_id ++; 

                // encriptar password
                $passEncri = md5($password_1); 

                $sql = "INSERT INTO users VALUES (:id, :user, :pass, :avatar)"; 
                $robo = $conn->prepare($sql); 
                $robo->bindParam(":id", $ultimo_id, PDO::PARAM_INT);
                $robo->bindParam(":user", $utilizador, PDO::PARAM_STR);
                $robo->bindParam(":pass", $passEncri, PDO::PARAM_STR);
                $robo->bindParam(":avatar", $avatar['name'], PDO::PARAM_STR);
                $robo->execute(); 
                $conn = null; 

                // upload ficheiro avatar
                move_uploaded_file($avatar['tmp_name'],"img/avatar/".$avatar['name']);
                
                // Apresentar uma imagem de boa vinda 
                echo '
                    <div class="novo_registro_successo">
                            Bem vindo no forum<br>
                        <a href="index.php">Quadro de login</a>
                    </div><br><br>
                                    '; 


        }

        echo '<p>Terminado</p>';
        

    }
?>