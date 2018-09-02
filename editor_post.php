<?php

// EDITAR / CRIAR POST
session_start(); 

// Controllo di sicurezza caso qlcuno voglia accedere direttamente alla pagina web senza fare il login
if(!isset($_SESSION['users']))
{
    include 'cabecalho.php'; 
    echo '<div class="error">Náo tem permissão para ver o conteud do forum.<br><br></div>
        <a href="index.php">Voltar</a>'; 
    include 'rodape.php'; 
    exit;
}
    //------------------------------------------------
    include 'cabecalho.php';

    //--------------------------------------------------
    // --------- MOSTRA IL POST DA EDITAR --------------
    //--------------------------------------------------
    // Verificara se é para editar o post
    $pid= -1;
    $editar = false; 
    $mensagem = "";
    $titulo = "";
    
    // Questa 'pid' é la variabile che arriva dal forum
    // Se questa variabile esiste... 
    if(isset($_REQUEST['pid']))
    {
        // La var $pid riceverá cio che arriva dal forum
        // Quindi significa che stiamo editando
        $pid = $_REQUEST['pid']; 
        $editar = true; 

        // Buscando dados do post
        include 'config.php';
        $conn = new PDO("mysql:dbname=$banco;host=$host", $user, $senha); 
        $robo = $conn->prepare("SELECT*FROM post WHERE id =".$pid);
        $robo->execute(); 

        $rs = $robo->fetch(PDO::FETCH_ASSOC);
        $conn = null; 

        $titulo = $rs['titulo'];
        $mensagem = $rs['mensagem']; 
       // echo $mensagem;

    }




    // Dados do utilizador que esta logado
    echo '<div class="dados_utilizador">
            <img src="img/avatar/'.$_SESSION['avatar'].'"><span>'.$_SESSION['users'].'</span> | 
            <a href="logout.php">Logout</a>
        </div>';

    // Formulario para construção dos posts
    echo '<div>
            <form class="form_post" method="post" action="post_add.php">
                <h3>Post</h3><hr><br>

                <input type="hidden" name="id_user" value='.$_SESSION['id_user'].'>
                <input type="hidden" name="id_post" value='.$pid.'>     


                Titulo:<br>
                <input type="text" name="txt_titulo" size="85" value="'.$titulo.'"><br><br>
                Mensagem:<br>
                <textarea rows="10" cols="85" name="txt_mensagem" >'.$mensagem.'</textarea><br><br>

                <input type="submit" value="Gravar"><br><br>
                <a href="forum.php">Voltar</a>
            </form>
         </div>';   


    //------------------------------------------------
    include 'rodape.php';  

?>