<?php
	include("classeLayout/classeCabecalhoHTML.php");
	$c = new CabecalhoHTML();
	$c->exibe();
	
	include("conexao.php");
	
	$ID_CLIENTE = $_POST["ID_CLIENTE"];
	
	$delete = "DELETE FROM cliente WHERE ID_CLIENTE=:id_cliente";
	
	$stmt = $conexao->prepare($delete);
	
	$stmt->bindValue(":id_cliente",$ID_CLIENTE);
	$stmt->execute();
	
	
	echo "Cliente removido com sucesso!";
	
?>
<hr />
	<a href='lista_cliente.php'>Voltar Para a listagem</a>
</body>
</html>