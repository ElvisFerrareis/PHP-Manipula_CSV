<!DOCTYPE html>
<html>
	<head>
		<title>Dados Cartões - Login</title>
		<meta charset="UTF-8" />
	</head>	
	<body>
		<br>
		<h2> PÁGINA DE LOGIN </h2>
		<div>
		<?php
			if(isset($_GET['erroLogin']) and $_GET['erroLogin'] == 1)
			{
				echo "<br /><h2>Usuário e/ou senha incorretos.<br />Entre com os dados novamente.</h2><br />";
			}
		?>
			<form method="post" action="verificaLogin.php" enctype="multipart/form-data">
				<p>Entre com as informações para fazer login:<br /></p>
				<p>Login: <input type="text" name="login"  /></p>
				<p>Senha: <input type="password" name="senha"  /><br /><br /></p>
				<input type="submit" value="Entrar" />
			</form>
		</div>			
	</body>
</html>