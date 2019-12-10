<?php
	include("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");
	
	include("conexao.php");
	
	$sql = "SELECT * FROM medico ORDER BY NOME_MEDICO";
	
	$stmt = $conexao->prepare($sql);
	
	$stmt->execute();
	
	echo "<table border='1'>";
	echo "<thead>
			<tr>
				<th>CRM</th>
				<th>NOME MEDICO</th>
				<th>TELEFONE</th>
				<th>CIDADE</th>
			</tr>
		  </thead>
		  <tbody>
		  ";
	while($linha=$stmt->fetch()){
		echo "<tr>
				<td>".$linha["CRM"]."</td>
				<td>".$linha["NOME_MEDICO"]."</td>
				<td>".$linha["TELEFONE"]."</td>
				<td>".$linha["CIDADE"]."</td>
				<td>
					<form method='post' action='remover_medico.php'>
						<input type='hidden' name='tabela' value='medico' />
						<input type='hidden' name='id' value='".$linha["CRM"]."' />
						<button>Remover</button>
					</form>
					
				</td>
		      </tr>";
	}
	echo "</tbody>
		</table>";
?>