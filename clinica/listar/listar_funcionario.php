<?php
	include("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");
	
	include("conexao.php");
	
	$sql = "SELECT * FROM funcionario ORDER BY ID_FUNCIONARIO";
	
	$stmt = $conexao->prepare($sql);
	
	$stmt->execute();
	
	echo "<table border='1'>";
	echo "<thead>
			<tr>
				<th>ID FUNCIONARIO</th>
				<th>NOME FUNCIONARIO</th>
				<th>EMAIL</th>
				<th>TELEFONE</th>
				<th>CIDADE</th>
				<th>DATA DE CONTRATACAO</th>
				<th>SALARIO</th>
			</tr>
		  </thead>
		  <tbody>
		  ";
	while($linha=$stmt->fetch()){
		echo "<tr>
				<td>".$linha["ID_FUNCIONARIO"]."</td>
				<td>".$linha["NOME_FUNCIONARIO"]."</td>
				<td>".$linha["EMAIL"]."</td>
				<td>".$linha["TELEFONE"]."</td>
				<td>".$linha["CIDADE"]."</td>
				<td>".$linha["DATA_CONTRATACAO"]."</td>
				<td>".$linha["SALARIO"]."</td>
				<td>
					<form method='post' action='remover.php'>
						<input type='hidden' name='tabela' value='funcionario' />
						<input type='hidden' name='id' value='".$linha["ID_FUNCIONARIO"]."' />
						<button>Remover</button>
					</form>
					
				</td>
		      </tr>";
	}
	echo "</tbody>
		</table>";
?>