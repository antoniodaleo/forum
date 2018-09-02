<?php
    // INSTALL
    // Criar base de dados que suporta o site
    include 'config.php';
    
    //CRIAR BASE DE DADOS -----------------------------
    $conn = new PDO("mysql:host:$host",$user,$senha); 
    $robo = $conn->prepare("CREATE DATABASE $banco");
    $robo->execute();
    $conn = null; 

    echo '<p>Banco criado com sucesso</p><br><hr>'; 
    //------------------------------------------------
    $conn = new PDO("mysql:dbname=$banco;host=$host", $user, $senha); 
    
    //----------------------------------------------------------------
    // Tabela "users" = utilizador do micro forum
    $sql = "CREATE TABLE users(
        id int not null primary key, 
        username varchar(45),
        pass varchar(100), 
        avatar varchar(250)
    )  ";

    $robo = $conn->prepare($sql); 
    $robo->execute(); 
    

    echo 'Tabela users criada com sucesso<br><br>'; 
    
    
    // Tabela "post" = utilizador do micro forum
 

     $sql = "CREATE TABLE post(
        id int not null primary key, 
        id_user int not null, 
        titulo varchar(100), 
        mensagem text, 
        data_post datetime,
        FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
    ) ";
    
    $robo = $conn->prepare($sql); 
    $robo->execute(); 
    $conn = null;
    echo 'Tabela criada com sucesso<br><br>';

    echo 'Banco criado com sucesso , processo terminado'; 
   
?>