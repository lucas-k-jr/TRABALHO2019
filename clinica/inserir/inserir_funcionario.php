<?php
	include("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");	
	
include("conexao.php");
if(!empty($_POST)){
	$ID_FUNCIONARIO=$_POST["ID_FUNCIONARIO"];
	$NOME_FUNCIONARIO=$_POST["NOME_FUNCIONARIO"];
	$EMAIL=$_POST["EMAIL"];
	$TELEFONE=$_POST["TELEFONE"];
	$CIDADE=$_POST["CIDADE"];
	$DATA_CONTRATACAO=$_POST["DATA_CONTRATACAO"];
	$SALARIO=$_POST["SALARIO"];

	$insert = "INSERT INTO funcionario VALUES (:id_funcionario,:nome_funcionario,:email,:telefone,:cidade,:data_contratacao,:salario)";
	$stmt = $conexao->prepare($insert);
	$stmt->bindValue(":id_funcionario",$ID_FUNCIONARIO);
	$stmt->bindValue(":nome_funcionario",$NOME_FUNCIONARIO);
	$stmt->bindValue(":email",$EMAIL);
	$stmt->bindValue(":telefone",$TELEFONE);
	$stmt->bindValue(":cidade",$CIDADE);
	$stmt->bindValue(":data_contratacao",$DATA_CONTRATACAO);
	$stmt->bindValue(":salario",$SALARIO);
	$stmt->execute();
	
	echo "Funcionario inserido com sucesso";
	
}
else{
	header("location: form_funcionario.php");
}
?>