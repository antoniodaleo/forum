<?php
    // VERIFICA OS DADOS DE LOGIN
    session_start(); 

    if(isset($_SESSION['users']))
    {
        include('cabecalho.php'); 
        echo '<div class="mensagem">Já se encontra logado no site.<br><br>
                <a href= "foum.php">Avançar para o form</a></div>'; 
        include('rodape.php');
        exit; 
    }

    //-----------------------------------------------------------------
    include 'cabecalho.php'; 


    $utilizador = $_POST['txt_utilizador']; 
    $pass = $_POST['txt_password'];

    // VERIFICAAR SE OS CAMPOS FORAM PREENCHIDOS
if($utilizador == "" || $pass =="")
{
    echo '<div class ="error">
            Não foram preenchidos os campos.<br><br>
            <a href="index.php">Tente novamente</a>
        </div> '; 
    include 'rodape.php';
    exit; 
}

//-----------------------------------------------------------------
//VERIFICAÇÃO DOS DADOS DE LOGIN
$passEncri = md5($pass);

include 'config.php';
$conn = new PDO("mysql:dbname=$banco;host=$host", $user, $senha);
$robo = $conn->prepare("SELECT *FROM users WHERE username = ? AND pass= ? ");
$robo-> bindParam(1,$utilizador, PDO::PARAM_STR); 
$robo-> bindParam(2,$passEncri, PDO::PARAM_STR); 
$robo->execute(); 
$conn = null;

// Verifica se os dados correspondem a valores da base de dados
if($robo->rowCount() == 0)
{
    // Erro dados invalidos
    echo '<div class="error"> 
            Dados de login invalidos<br><br> 
            <a href="index.php">Tente novamente</a>   
        </div>'; 
        include 'rodape.php';
        exit; 
}
else
{

    //DEFINIR OS DADOS DE SESSÃO
    $rs = $robo->fetch(PDO::FETCH_ASSOC);
    
    $_SESSION['id_user'] = $rs['id'];  
    $_SESSION['users'] = $rs['username']; 
    $_SESSION['avatar'] =$rs['avatar']; 

    echo '<div class="login_sucesso">
            Bem-Vindo ao forum, <strong>'.$_SESSION['users'].'</strong><br><br>  
            <a href="forum.php">Continuar</a>
        </div>';
    //echo '<div class="mensagem">Sucesso</div>'; 
}



//-----------------------------------------------------------------
    include 'rodape.php'; 
    

?>