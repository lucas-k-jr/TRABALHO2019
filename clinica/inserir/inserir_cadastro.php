<?php
	include("conexao.php");

	$nome = $_POST["nome"];
	$email = $_POST["email"];
	$sexo = $_POST["sexo"];
	$insert = "INSERT INTO cadastro (nome,email, sexo) VALUES ('$nome','$email','$sexo')";
	mysqli_query($conexao,$insert) or die(mysql_error($conexao));
	echo "1";
?>