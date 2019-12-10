<?php
	include("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");
	
	
include("conexao.php");
if(!empty($_POST)){
	$CRM=$_POST["CRM"];
	$NOME_MEDICO=$_POST["NOME_MEDICO"];
	$TELEFONE=$_POST["TELEFONE"];
	$CIDADE=$_POST["CIDADE"];

	$insert = "INSERT INTO medico VALUES (:crm,:nome_medico,:email,:telefone)";
	$stmt = $conexao->prepare($insert);
	$stmt->bindValue(":crm",$CRM);
	$stmt->bindValue(":nome_medico",$NOME_MEDICO);
	$stmt->bindValue(":email",$EMAIL);
	$stmt->bindValue(":telefone",$TELEFONE);

	$stmt->execute();
	
	echo "Medico inserido com sucesso";
	
}
else{
	header("location: form_medico.php");
}
?>