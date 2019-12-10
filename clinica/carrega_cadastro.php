<?php
	header("Content-type: application/json");
	include("conexao.php");
	
	$p = $_POST["pg"];
	
	$sql = "SELECT * FROM cadastro LIMIT $p,5";
	$resultado = mysqli_query($conexao,$sql);
	while($linha=mysqli_fetch_assoc($resultado)){
		$matriz[] = $linha;
	}
	
	echo json_encode($matriz);
	
?>