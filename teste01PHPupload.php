<!DOCTYPE html>
<html>
	<head>
		<title>Dados Cartões - Upload do arquivo</title>
		<meta charset="UTF-8" />
	</head>	
	<body>
		<br>
		<h2> Faça o upload do arquivo clicando no botão: </h2>
		<div>
			<form method="post" action="upload.php" enctype="multipart/form-data">
				<input type="file" name="arquivo"  />
				<input type="submit" value="Carregar arquivo" />
			</form>
		</div>			
	</body>
</html>