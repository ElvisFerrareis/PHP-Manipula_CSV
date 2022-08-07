<!DOCTYPE html>
<html>
	<head>
		<title>Dados Cartões - Login</title>
		<meta charset="UTF-8" />
		<script type="text/javascript">
			function submitform()
			{
				document.getElementById("formulario").submit();
			}
		</script>
	</head>	
	<body>
		<?php
			include 'funcoes.php';
			
			$login = $_POST["login"];
			$senha = $_POST["senha"];
			
			$conexao = iniciaConexaoMySQL();
			
			$query = "SELECT * FROM usuarios WHERE `login` = '" . $login . "' AND `senha` = sha('" . $senha . "')";
			
			$resultado = selectMySQL($conexao, $query);
			
			if(mysqli_num_rows($resultado))
			{
				echo "Login efeuado com sucesso!";
				header('Location: /Costalis/teste01PHPupload.php');
			}
			else
			{
				header('Location: /Costalis/login.php?erroLogin=1');
			}
		?>
	</body>
</html>