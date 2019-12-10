<?php
	$c = new CabecalhoHTML();
	$v = array(				
				"cliente"=>"Cliente",
				"funcionario"=>"Funcionario",
				"medico"=>"Medico",
				"cidade"=>"Cidade",
				"cancelamento"=>"Cancelamento",
				"forma_pagamento"=>"Formas de Pagamento",
				"grupo"=>"Especificações com Valores",
				"diagnostico"=>"Diagnosticos",
				"avaliacao"=>"Avaliações de Consultas",
				"agendamento_consulta"=>"Agendamento de Consultas",
				"pagamento"=>"Pagamento de Consultas",
				"usuario"=>"Cadastro de Usuarios",
				"notas"=>"Cadastro de Notas"
				);
				
	$c->add_menu($v);
	$c->exibe();
?>