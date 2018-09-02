<?php
    session_start(); 
    // Questa variabile esiste solo qui per far apparire obbligat il login
    $session_user = null ; 


    //unset($_SESSION['users']);

    if(isset($_SESSION['users']))
    {
        include('cabecalho.php'); 
        echo '<div class="mensagem">Já se encontra logado no site.<br><br>
                <a href= "forum.php">Avançar para o form</a></div>'; 
        include('rodape.php');
        exit; 
    }

   

    //CABECALHO-------------------------------------------
    include 'cabecalho.php'; 

    // Caso nessuno sia logado mi direzioni sul login
    if($session_user == null)
    {
        include 'login.php';
    }


    //RODAPÉ---------------------------------------------
    include 'rodape.php'; 

?>