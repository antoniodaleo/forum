<?php
    // LOGIN
    echo '
        <form class="form_login" method="post" action="login_verificador.php">
            <h3>Login</h3><hr><br>
            Para entrar no microforum necessita introducir o seu username e password.<br>
            Se não tem conta pode criar <a href="signup.php"> nova conta de utilizador</a><br><br> 

            Username: <br><input type="text" name="txt_utilizador"><br><br>
            Password: <br><input type="password" name="txt_password"><br><br>
            
            <input type="submit" name="btn_submit" value="Entrar">

        </form>
    
    '; 

?>