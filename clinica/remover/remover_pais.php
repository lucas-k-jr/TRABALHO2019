<?php
	include("../classeLayout/classeCabecalhoHTML.php");
	$c = new CabecalhoHTML();
	$c->exibe();
	
	include("conexao.php");
	
	$CRM = $_POST["CRM"];
	
	$delete = "DELETE FROM medico WHERE CRM=:crm";
	
	$stmt = $conexao->prepare($delete);
	
	$stmt->bindValue(":crm",$CRM);
	$stmt->execute();
	
	
	echo "Medico removido com sucesso!";
	
?>
<hr />
	<a href='lista_medico.php'>Voltar Para a listagem</a>
</body>
</html>