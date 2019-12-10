<?php
	session_start();
	include("conexao.php");
	
		if(!empty($_POST)){
			include("classeControllerBD.php");
			
			$c = new ControllerBD($conexao);
			
			if($c->inserir($_POST,$_GET["tabela"])){
				echo "1";
			}
			else
				echo "0";
		}
		else{
			echo "-1";
		}
?>