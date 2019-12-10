<?php
	
include("conexao.php");
if(!empty($_POST)){
	include("classeControllerBD.php");
	
	$c = new ControllerBD($conexao);
	$c->alterar($_POST,$_GET["tabela"]) 
		or die("Erro ao alterar ".$_GET["tabela"]);
	echo "1";
}
else{
	echo "0";
}
?>