<?php
    // LOGOUT
    session_start(); 
    include 'cabecalho.php';
   
    $mensagem = 'Pagina nao disponivel ao visitante';

    if(isset($_SESSION['users'])) $mensagem= 'Ate a proxima '.$_SESSION['users'].'!'; 
      
    
    unset($_SESSION['users']); 
    echo '<div class="login_sucesso">
                '.$mensagem.'<br><br>
                <a href="index.php">Voltar</a>
          </div>'; 
    
    
    include 'rodape.php'; 

?>