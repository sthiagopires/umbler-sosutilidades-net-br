<?php
 session_start();
 ob_start();

require 'config2.php';
require 'classes/usuarios.class.php';

if(!empty($_POST['email'])) {
	$email = addslashes($_POST['email']);
	$usuario = addslashes($_POST['usuario']);
	$senha = md5($_POST['senha']);

	$usuarios = new Usuarios($pdo);



	if($usuarios->fazerLogin($email, $senha, $usuario)) {
        $hoje = date('d/m/Y');
		header("Location: index.php?search=$hoje");
		exit;
	} else {
		$erro = "Usuário e/ou senha estão errados!";
	}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="icon_sos.ico">
    
    <title>Faça Seu Login (SoS)</title>
	
	<style> .erro { 
        color: red;
        font-weight: bold;
        } 

        .sair {
    display: flex;
    justify-content: center;
    padding-right: 20px;
	padding-top: 20px;
    font-size: 18px;
    font-weight: bold;
}
    </style>
    
    <!--PUXAR O CSS EXTERNO-->
    <link rel="stylesheet" href="style_sos.css">
	
	<?php //print_r($_REQUEST); ?>

</head>
<body>
    <section class="content_center">
    <div class="text_center">
    
        <a href="login.php"><img src="login.png" alt="Todos os Direitos Reservados"></a>
        <h3>Faça o Seu Login</h3>
		<?php if(isset($_POST['email'])){ echo "<span class='erro'>$erro</span>";} ?>
    </div>
<form action="" method="POST">
        <input autocomplete="on" name="email" type="email" placeholder="Digite o Seu E-mail" required>
    <div>
        <input name="senha" type="password" placeholder="Digite a sua Senha" required>
    </div>
    <div class="user">
    <label><b>Usuario: </b></label>
            <input name="usuario" value="Inglid" type="radio"> Inglid 
            <input  name="usuario" value="Flavia"  type="radio"> Flávia
            <input  name="usuario" value="user_novo"  type="radio"> user_novo
            <input hidden checked name="usuario" value="Thiago"  type="radio">
        </div>

    <div>
        <button type="submit">Entrar</button>
    </div>
	
    <div class="cadastre_center">
        <a href="cadastro.php">Cadastre - se</a> <!-- target="_blank" -->
    </div>
    <div class="sair" ><a href="sair.php" >Sair</a></div>
    <!--
    <div class="esqueceu_center">
        <a href="">Esqueceu a Senha?</a>
    </div>
    -->
</form>
    </section>
</body>
</html>