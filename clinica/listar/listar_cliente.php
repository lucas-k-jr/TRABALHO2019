<?php
	include("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");
	
	include("conexao.php");
	
	$sql = "SELECT * FROM cliente ORDER BY ID_CLIENTE";
	
	$stmt = $conexao->prepare($sql);
	
	$stmt->execute();
	
	echo "<table border='1'>";
	echo "<thead>
			<tr>
				<th>ID CLIENTE</th>
				<th>NOME CLIENTE</th>
				<th>EMAIL</th>
				<th>CIDADE</th>
				<th>ESTADO</th>
				<th>TELEFONE</th>
				<th>BAIRRO</th>
			</tr>
		  </thead>
		  <tbody>
		  ";
	while($linha=$stmt->fetch()){
		echo "<tr>
				<td>".$linha["ID_CLIENTE"]."</td>
				<td>".$linha["NOME_CLIENTE"]."</td>
				<td>".$linha["EMAIL"]."</td>
				<td>".$linha["CIDADE"]."</td>
				<td>".$linha["ESTADO"]."</td>
				<td>".$linha["TELEFONE"]."</td>
				<td>".$linha["BAIRRO"]."</td>
				<td>
					<form method='post' action='remover_cliente.php'>
						<input type='hidden' name='tabela' value='cliente' />
						<input type='hidden' name='id' value='".$linha["ID_CLIENTE"]."' />
						<button>Remover</button>
					</form>
					
				</td>
		      </tr>";
	}
	echo "</tbody>
		</table>";
?>