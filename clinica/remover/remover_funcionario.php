<?php
	include("classeLayout/classeCabecalhoHTML.php");
	$c = new CabecalhoHTML();
	$c->exibe();
	
	include("conexao.php");
	
	$ID_FUNCIONARIO = $_POST["ID_FUNCIONARIO"];
	
	$delete = "DELETE FROM funcionario WHERE ID_FUNCIONARIO=:id_funcionario";
	
	$stmt = $conexao->prepare($delete);
	
	$stmt->bindValue(":id_funcionario",$ID_FUNCIONARIO);
	$stmt->execute();
	
	
	echo "Função removida com sucesso!";
	
?>
<hr />
	<a href='lista_funcionario.php'>Voltar Para a listagem</a>
</body>
</html>