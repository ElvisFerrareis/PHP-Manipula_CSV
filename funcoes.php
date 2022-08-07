<?php
	function leCSV($nome)
	{
		$nome = 'arquivos/'.$nome;
		try
		{
		//Funciona somente para arquivos no mesmo formato do exemplo
			if($csv = fopen($nome, "r"))
			{
				$arquivo = utf8_encode(fread($csv, filesize($nome)));
				fclose($csv);
				$linhas = preg_split("/\r\n|\r|\n/", $arquivo);
				for($i = 0; $i < count($linhas) - 1; $i++)
				{
					$dadosTabela[$i] = explode(";", $linhas[$i]);
					if($i > 0)
					{
						$data = explode("/", $dadosTabela[$i][0]);
						$dadosTabela[$i][0] = trim($data[2]) . "-" . trim($data[1]) . "-" . trim($data[0]);
						$dadosTabela[$i][4] = str_replace(",", ".", str_replace(".", "", $dadosTabela[$i][4]));
						$periodo = explode("-", $dadosTabela[$i][6]);
						$periodo[0] = explode("/", $periodo[0]);
						$dadosTabela[$i][6] = trim($periodo[0][2]) . "-" . trim($periodo[0][1]) . "-" . trim($periodo[0][0]);
						$periodo[1] = explode("/", $periodo[1]);
						$dadosTabela[$i][8] = trim($periodo[0][2]) . "-" . trim($periodo[0][1]) . "-" . trim($periodo[0][0]);
					}
					else
					{
						for($j = 0; $j < count($dadosTabela[$i]) - 1; $j++)
						{
							$dadosTabela[$i][6] = "validadeinicio";
							$dadosTabela[$i][8] = "validadefim";
							$dadosTabela[$i][$j] = strtolower($dadosTabela[$i][$j]);
							$dadosTabela[$i][$j] = str_replace("/", "", $dadosTabela[$i][$j]);
						}
					}
				}
				
				return $dadosTabela;
			}
			else
			{
				return "";
			}
		}
		catch (Exception $e)
		{
			return "";
		}
	}
	
	function iniciaConexaoMySQL()
	{
		try
		{
			//Alterar estes dados para os corretos para conectar à base de dados.
			$hostname = ""; //nome da máquina ou IP onde está o SGBD
			$usuario = ""; //usuário do SGBD - necessita permição de INSERT e SELECT
			$senha = ""; //senha do usuário
			$bancoDeDados = ""; //nome do schema para conexão
			//
			
			$conexao = mysqli_connect($hostname, $usuario, $senha);
			mysqli_select_db($conexao, $bancoDeDados);
			mysqli_set_charset($conexao, "utf8");
			
			return $conexao;
		}
		catch(Exception $e)
		{
			printf("%s", $e);
		}
	}
	
	function insertMySQL($conexao, $bancoDeDados, $tabela, $dados)
	{
		try
		{
			//Se o arquivo tiver mais de 1000 linhas, talvez seja interessante não executar a inserção toda de uma vez
			$query = "INSERT INTO " . $bancoDeDados . "." . $tabela . "(`" .implode("`, `", $dados[0]) . "`) VALUES ";
			
			
			for($i = 1; $i < count($dados); $i++)
			{
				$query .= "('" . implode("', '", $dados[$i]) . "')";			
				if($i < count($dados) - 1)
				{
					$query .= ", ";
				}
			}
			mysqli_query($conexao, $query);
			mysqli_commit($conexao);
		}
		catch(Exception $e)
		{
			printf("%s", $e);
		}
	}
	
	function selectMySQL($conexao, $query)
	{
		try
		{
			return mysqli_query($conexao, $query);
		}
		catch(Exception $e)
		{
			printf("%s", $e);
		}		
	}
	
	function fechaConexaoMySQL($conexao)
	{
		try
		{
			mysqli_close($conexao);
		}
		catch(Exception $e)
		{
			printf("%s", $e);
		}		
	}
?>