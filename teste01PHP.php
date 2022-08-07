<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript">
			function trocaCor(elemento)
			{
				elemento.style.backgroundColor = "#FFAA98";
			}
			
			function destrocaCor(elemento)
			{
				var id = elemento.id;
				var linha = parseInt(id);
				if(linha < 27)
				{
					if(linha % 2 == 0)
					{
						elemento.style.backgroundColor = "#EEEEEE";
					}
					else
					{
						elemento.style.backgroundColor = "#00F0F0";
					}
				}
				else
				{
					elemento.style.backgroundColor = "#00CCFF";
				}
			}
		</script>
		<title>Dados Cartões - lista</title>
		<meta charset="UTF-8" />
	</head>	
	<body>
		<?php
			include 'funcoes.php';
			
			$dados = leCSV("export.csv");
			$categoriaSelecionada = "";
			$mesesSelecionados = Array();
			$filtrarLimite = False;
			$selecaoMeses = False;
			
			if(isset($_POST["categorias"]))
			{
				$categoriaSelecionada = $_POST["categorias"];
			}
			
			for($i = 1; $i < 10; $i++)
			{
				if(isset($_POST[strval($i)]))
				{
					$mesesSelecionados[$i] = $i;
				}
				else
				{
					$mesesSelecionados[$i] = "";
				}
				$selecaoMeses = True;
			}
			
			if(isset($_POST["filtroValor"]) and $_POST["filtroValor"] != "")
			{
				$filtroValor = $_POST["filtroValor"];
				$filtrarLimite = True;
			}
			
			$conexao = iniciaConexaoMySQL();
			
			$query = "SELECT COUNT(*) FROM teste_costalis.clientes";
			$consulta = mysqli_fetch_array(selectMySQL($conexao, $query));
			if($consulta[0] == 0)
			{
				insertMySQL($conexao, "teste_costalis", "clientes", $dados);
			}
			
			$query = "SELECT categoria, MONTH(data) AS mes, SUM(valor) AS valor FROM teste_costalis.clientes";
			if(($categoriaSelecionada != "todos" and $categoriaSelecionada != "") or (implode("", $mesesSelecionados) != ""))
			{
				$query .= " WHERE";
				if($categoriaSelecionada != "todos" and $categoriaSelecionada != "")
				{
					$query .= " categoria = '" . $categoriaSelecionada . "'";
					if(implode("", $mesesSelecionados) != "")
					{
						$query .= " AND";
					}
				}
				if(implode("", $mesesSelecionados) != "")
				{
					$listaMeses = implode('", "', $mesesSelecionados);
					$query .= ' MONTH(data) in ("' . $listaMeses . '")';
				}
			}
			$query .= " GROUP BY categoria, MONTH(data) ORDER BY categoria, MONTH(data)";
			$consulta = selectMySQL($conexao, $query);
			
			$query = "SELECT DISTINCT categoria FROM teste_costalis.clientes ORDER BY categoria";
			$selecao = selectMySQL($conexao, $query);
			
			$query = "SELECT DISTINCT categoria FROM teste_costalis.clientes";
			if($categoriaSelecionada != "todos" and $categoriaSelecionada != "")
			{
				$query .= " WHERE categoria = '" . $categoriaSelecionada . "'";
			}
			$query .= " ORDER BY categoria";
			$categoria = selectMySQL($conexao, $query);
			
			$query = "SELECT categoria, SUM(valor) AS valor FROM teste_costalis.clientes";
			if(($categoriaSelecionada != "todos" and $categoriaSelecionada != "") or (implode("", $mesesSelecionados) != ""))
			{
				$query .= " WHERE";
				if($categoriaSelecionada != "todos" and $categoriaSelecionada != "")
				{
					$query .= " categoria = '" . $categoriaSelecionada . "'";
					if(implode("", $mesesSelecionados) != "")
					{
						$query .= " AND";
					}
				}
				if(implode("", $mesesSelecionados) != "")
				{
					$listaMeses = implode('", "', $mesesSelecionados);
					$query .= ' MONTH(data) in ("' . $listaMeses . '")';
				}
			}
			$query .= " GROUP BY categoria ORDER BY categoria";
			$totalCategoria = selectMySQL($conexao, $query);
			
			$query = "SELECT MONTH(data) AS mes, SUM(valor) AS valor FROM teste_costalis.clientes";
			if(($categoriaSelecionada != "todos" and $categoriaSelecionada != "") or (implode("", $mesesSelecionados) != ""))
			{
				$query .= " WHERE";
				if($categoriaSelecionada != "todos" and $categoriaSelecionada != "")
				{
					$query .= " categoria = '" . $categoriaSelecionada . "'";
					if(implode("", $mesesSelecionados) != "")
					{
						$query .= " AND";
					}
				}
				if(implode("", $mesesSelecionados) != "")
				{
					$listaMeses = implode('", "', $mesesSelecionados);
					$query .= ' MONTH(data) in ("' . $listaMeses . '")';
				}
			}
			$query .= " GROUP BY MONTH(data) ORDER BY MONTH(data)";
			$totalMes = selectMySQL($conexao, $query);
			
			$query = "SELECT SUM(valor) AS valor FROM teste_costalis.clientes";
			if(($categoriaSelecionada != "todos" and $categoriaSelecionada != "") or (implode("", $mesesSelecionados) != ""))
			{
				$query .= " WHERE";
				if($categoriaSelecionada != "todos" and $categoriaSelecionada != "")
				{
					$query .= " categoria = '" . $categoriaSelecionada . "'";
					if(implode("", $mesesSelecionados) != "")
					{
						$query .= " AND";
					}
				}
				if(implode("", $mesesSelecionados) != "")
				{
					$listaMeses = implode('", "', $mesesSelecionados);
					$query .= ' MONTH(data) in ("' . $listaMeses . '")';
				}
			}
			$totalGeral = mysqli_fetch_array(selectMySQL($conexao, $query))[0];
			
			fechaConexaoMySQL($conexao);
			
			$colunas = Array( 0 => "Categorias", 1 => "jan", 2 => "fev", 3 => "mar", 4 => "abr", 5 => "mai",
							  6 => "jun", 7 => "jul", 8 => "ago", 9 => "set", 10 => "Total Geral");
			$tabela = Array(Array());
			$todasAsCategorias = Array();
			$totalGeralMes = Array();			
			
			for($i = 0; $i < 27; $i++)
			{
				$todasAsCategorias[$i] = mysqli_fetch_row($selecao)[0];
			}
			
			while($valores = mysqli_fetch_row($categoria))
			{
				for($linha = 0; $linha < 27; $linha++)
				{
					if($todasAsCategorias[$linha] == $valores[0])
					{
						$categorias[$linha] = $valores[0];
					}
					else if($categoriaSelecionada == "todos")
					{
						$categorias[$linha] = $todasAsCategorias[$linha];
					}
				}
				
			}
			
			while($valores = mysqli_fetch_row($totalCategoria))
			{
				for($linha = 0; $linha < 27; $linha++)
				{
					if($valores[0] == $todasAsCategorias[$linha])
					{
						$totalGeralCategoria[$categorias[$linha]] = $valores[1];
						$achou = True;
					}
				}
				if(!$achou)
				{
					$totalGeralCategoria[$valores[0]] = "";
				}
			}
			
			for($linha = 0; $linha < count($categorias); $linha++)
			{
				$tabela[$categorias[$linha]][0] = $categorias[$linha];
				if(isset($totalGeralCategoria[$categorias[$linha]]))
				{
					$tabela[$categorias[$linha]][10] = str_replace(".", ",", $totalGeralCategoria[$categorias[$linha]]);
				}
				else
				{
					$tabela[$categorias[$linha]][10] = "";
				}
			}
						
			while($dados = mysqli_fetch_row($consulta))
			{
				for($linha = 0; $linha < count($categorias); $linha++)
				{
					if($dados[0] == $categorias[$linha])
					{
						$tabela[$categorias[$linha]][$dados[1]] = str_replace(".", ",", $dados[2]);
					}
				}
			}			
			while($totalSomaMes = mysqli_fetch_row($totalMes))
			{				
				$totalGeralMes[$totalSomaMes[0]] = str_replace(".", ",", $totalSomaMes[1]);
			}
			$tabela["Total Geral"] = $totalGeralMes;
			$tabela["Total Geral"][0] = "Total Geral";
			if(implode("", $mesesSelecionados) == "")
			{
				$tabela["Total Geral"][10] = str_replace(".", ",", $totalGeral);
				
			}
			else
			{
				$mesesSelecionados[count($mesesSelecionados) + 1] = $colunas[10];
				$tabela["Total Geral"][count($mesesSelecionados)] = str_replace(".", ",", $totalGeral);
			}
			array_push($categorias, $tabela["Total Geral"][0]);			
		?>
		<h2>Compilado por Mês de cada Categoria</h2><br />
		
		<table>
			<form method="post" enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF'] ?>">
				<tr>			
					<td>
					<p>Para filtrar, selecione 1 categoria: </p><br />
						<select name="categorias">
							<option value="todos"> Todas </option>
						<?php
							for($i = 0; $i < 27; $i++)
							{
								$saida = '<option value="' . $todasAsCategorias[$i] . '"'; 
								if($categoriaSelecionada != "" and $todasAsCategorias[$i] == $categoriaSelecionada)
								{
									$saida .= ' selected';
								}
								$saida .= '>' . $todasAsCategorias[$i] . '</option>';
								echo $saida;
							}
						?>
						</select>
					</td>						
					<td>
					
					</td>						
					<td>
						<p>Selecione os meses que deseja ver:</p>
						<table>
							<tr>
								<td>
									<div>
										<input type="checkbox" id="1" name="1"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[1]==1 ? " checked" : "")?>>
										<label for="1">Janeiro</label>
									</div>
								</td>
								<td>
									<div>
										<input type="checkbox" id="4" name="4"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[4]==4 ? " checked" : "")?>>
										<label for="4">Abril</label>
									</div>
								</td>
								<td>
									<div>
										<input type="checkbox" id="7" name="7"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[7]==7 ? " checked" : "")?>>
										<label for="7">Julho</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div>
										<input type="checkbox" id="2" name="2"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[2]==2 ? " checked" : "")?>>
										<label for="2">Fevereiro</label>
									</div>
								</td>
								<td>
									<div>
										<input type="checkbox" id="5" name="5"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[5]==5 ? " checked" : "")?>>
										<label for="5">Maio</label>
									</div>
								</td>
								<td>
									<div>
										<input type="checkbox" id="8" name="8"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[8]==8 ? " checked" : "")?>>
										<label for="8">Agosto</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div>
										<input type="checkbox" id="3" name="3"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[3]==3 ? " checked" : "")?>>
										<label for="3">Março</label>
									</div>
								</td>
								<td>
									<div>
										<input type="checkbox" id="6" name="6"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[6]==6 ? " checked" : "")?>>
										<label for="6">Junho</label>
									</div>
								</td>
								<td>
									<div>
										<input type="checkbox" id="9" name="9"<?php echo !$selecaoMeses?" checked":($mesesSelecionados[9]==9 ? " checked" : "")?>>
										<label for="9">Setembro</label>
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<div>
							<label for="filtroValor">Digite o valor limite:</label><br />
							<?php
								$saida = '<input type="text" id="filtroValor" name="filtroValor"';
								if($filtrarLimite)
								{
									$saida .= ' value="' . $filtroValor .'"';
								}
								$saida .= '>';
								echo $saida;
							?>
							
							<br />
							<label style="font-size:11px;">Para limpar a marcação, basta</label><br />
							<label style="font-size:11px;">clicar em filtrar com a caixa</label><br />
							<label style="font-size:11px;">de texto acima vazia.</label>
						</div>
					</td>
					<td>					
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
						<input type="submit" value="Filtrar" />
					</td>
				</tr>						
			</form>
		</table>
		<br />
		<table border="1">
			<thead>
				<tr style="background-color:#00CCFF"> 
					<th>Categorias</th>
					<?php
						if(implode("", $mesesSelecionados) != "")
						{
							for($i = 1; $i < 10; $i++)
							{
								if($mesesSelecionados[$i] != "")
								{
									echo '<th>' . $colunas[$mesesSelecionados[$i]] . ' </th>';
								}
								else
								{
									echo '<th> </th>';
								}
							}
						}
						else
						{
							for($i = 1; $i < 10; $i++)
							{
								echo '<th>' . $colunas[$i] . ' </th>';
							}
						}
					?>
					<th>Total Geral</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php
					if($tabela)
					{
						for($linha = 0; $linha < count($categorias); $linha++)
						{
							if($linha % 2 == 0 and $linha < count($categorias) - 1)
							{
								echo '<tr style="background-color:#EEEEEE">';
							}
							else if($linha < count($categorias) - 1)
							{
								echo '<tr style="background-color:#00F0F0">';
							}
							else
							{
								echo '<tr style="background-color:#00CCFF">';
							}
							echo '<th style="text-align:Left">' . $tabela[$categorias[$linha]][0] . '</th>';
							if(implode("", $mesesSelecionados) != "")
							{
								for($coluna = 1; $coluna < 11; $coluna++)
								{
									if(isset($tabela[$categorias[$linha]][$coluna]) and $mesesSelecionados[$coluna] != "")
									{										
										if($linha < count($categorias) - 1)
										{
											$saida = '<td id=' . $linha . ' ' . $coluna .' style="text-align:right;';
											if($filtrarLimite and floatval($tabela[$categorias[$linha]][$coluna]) > floatval($filtroValor) and $coluna < 10)
											{
												$saida .= 'color:#FF0000;';
											}											
											$saida .= '" onmouseover="javascript:trocaCor(this)" onmouseout="javascript:destrocaCor(this)">' . $tabela[$categorias[$linha]][$coluna] . '</td>';
										}
										else
										{											
											$saida = '<th id=' . $linha . ' ' . $coluna .' style="text-align:right;" onmouseover="javascript:trocaCor(this)" onmouseout="javascript:destrocaCor(this)">' . $tabela[$categorias[$linha]][$coluna] . '</th>';
										}
										echo $saida;
									}
									else
									{
										echo "<td>    </td>";
									}
								}
							}
							else
							{
								for($coluna = 1; $coluna < 11; $coluna++)
								{
									if(isset($tabela[$categorias[$linha]][$coluna]))
									{
										if($linha < count($categorias) - 1)
										{
											$saida = '<td id=' . $linha . ' ' . $coluna .' style="text-align:right;';
											if($filtrarLimite and floatval($tabela[$categorias[$linha]][$coluna]) > floatval($filtroValor) and $coluna < 10)
											{
												$saida .= 'color:#FF0000;';
											}											
											$saida .= '" onmouseover="javascript:trocaCor(this)" onmouseout="javascript:destrocaCor(this)">' . $tabela[$categorias[$linha]][$coluna] . '</td>';
										}
										else
										{
											$saida = '<th id=' . $linha . ' ' . $coluna .' style="text-align:right" onmouseover="javascript:trocaCor(this)" onmouseout="javascript:destrocaCor(this)">' . $tabela[$categorias[$linha]][$coluna] . '</th>';
										}
										echo $saida;
									}
									else
									{
										echo "<td>    </td>";
									}
								}
							}
							echo "</tr>";
						}
					}
				?>
				</tr>
			</tbody>
		</table>
	</body>
</html>