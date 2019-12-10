<?php

	//include("interfaceExibicao.php");
	require_once("classeLayout/classeCabecalhoHTML.php");
	require_once("classeLayout/classeTabela.php");
	require_once("cabecalho.php");
	require_once("conexao.php");
	
	require_once("classeControllerBD.php");
	require_once("configurar_listar.php");


	if($_GET["t"]=="cliente"){
		require_once("form_cliente.php");
	}

	if($_GET["t"]=="medico"){
		require_once("form_medico.php");
	}
	
	if($_GET["t"]=="funcionario"){
		require_once("form_funcionario.php");
	}

	if($_GET["t"]=="pagamento"){
		require_once("form_pagamento.php");
	}

	if($_GET["t"]=="forma_pagamento"){
		require_once("form_forma_pagamento.php");
	}

	if($_GET["t"]=="grupo"){
		require_once("form_grupo.php");
	}

	if($_GET["t"]=="avaliacao"){
		require_once("form_avaliacao.php");
	}

	if($_GET["t"]=="diagnostico"){
		require_once("form_diagnostico.php");
	}

	if($_GET["t"]=="agendamento_consulta"){
		require_once("form_agendamento_consulta.php");
	}

	if($_GET["t"]=="cancelamento"){
		require_once("form_cancelamento.php");
	}
	
	if($_GET["t"]=="cidade"){
		require_once("form_cidade.php");
	}

	if($_GET["t"]=="usuario"){
		require_once("form_usuario.php");
	}

	if($_GET["t"]=="notas"){
		require_once("form_notas.php");
	}

	$c = new ControllerBD($conexao);
	
	$r = $c->selecionar($colunas,$t,null,null," LIMIT 0,5");
	
	if($r->rowCount()>0){
		while($linha = $r->fetch(PDO::FETCH_ASSOC)){
			$matriz[] = $linha;
		}
		
		$t = new Tabela($matriz,$t[0][0]);
		$t->exibe();
	}
	else{
		echo "Ainda não há registros para este cadastro.";
	}


?>