<?php
$_UP['pasta'] = getcwd().'/arquivos/';
$_UP['tamanho'] = 1024 * 1024 * 2;
$_UP['extensoes'] = array('csv');
$_UP['renomeia'] = false;

$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especificado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

if($_FILES['arquivo']['error'] != 0)
{
	die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
	exit;
}
 
$nomeArquivo = explode('.', $_FILES['arquivo']['name']);
$parteNomeArquivo = end($nomeArquivo);
$extensao = strtolower($parteNomeArquivo);
if(array_search($extensao, $_UP['extensoes']) === false)
{
	echo "Por favor, envie arquivos com as seguintes extensões: .csv";
}

else if($_UP['tamanho'] < $_FILES['arquivo']['size'])
{
	echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
}
else
{
	$nome_final = $_FILES['arquivo']['name'];
	if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final))
	{
	echo "Upload efetuado com sucesso!";
	echo '<br /><a href="teste01PHP.php">Clique aqui para ir para a próxima página.</a>';
	}
	else
	{
		echo "Não foi possível enviar o arquivo, tente novamente";
	} 
} 
?>
