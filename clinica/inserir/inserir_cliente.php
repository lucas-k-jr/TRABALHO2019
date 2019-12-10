<?php
	include("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");
	
	
include("conexao.php");
if(!empty($_POST)){
	$ID_CLIENTE=$_POST["ID_CLIENTE"];
	$NOME_CLIENTE=$_POST["NOME_CLIENTE"];
	$EMAIL=$_POST["EMAIL"];
	$CIDADE=$_POST["CIDADE"];
	$ESTADO=$_POST["ESTADO"];
	$TELEFONE=$_POST["TELEFONE"];
	$BAIRRO=$_POST["BAIRRO"];

	$insert = "INSERT INTO cliente VALUES (:id_cliente,:nome_cliente,:email,:cidade,:estado,:telefone,:bairro)";
	$stmt = $conexao->prepare($insert);
	$stmt->bindValue(":id_cliente",$ID_CLIENTE);
	$stmt->bindValue(":nome_cliente",$NOME_CLIENTE);
	$stmt->bindValue(":email",$EMAIL);
	$stmt->bindValue(":cidade",$CIDADE);
	$stmt->bindValue(":estado",$ESTADO);
	$stmt->bindValue(":telefone",$TELEFONE);
	$stmt->bindValue(":bairro",$BAIRRO);

	$stmt->execute();
	
	echo "Cliente inserido com sucesso";
	
}
else{
	header("location: form_cliente.php");
}
?>