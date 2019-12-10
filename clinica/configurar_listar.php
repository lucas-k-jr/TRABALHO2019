<?php
if(isset($_GET["t"])){
	if($_GET["t"]=="usuario"){
		
		$colunas = array(   "id_usuario as ID",
								"nome as 'Nome'",
								"login as 'Email'",
								"senha as 'Senha'",
								"cpf as 'Cpf'",
								"email as 'Email'",
								"permissao as 'Permissao'"
							);				
				$t[0][0] = "usuario";
				$t[0][1] = null;
	}

	elseif($_GET["t"]=="funcionario"){
		
		$colunas = array(   "ID_FUNCIONARIO as ID",
									"NOME_FUNCIONARIO as 'Nome do funcionario'",
									"EMAIL as 'Email'",
									"TELEFONE as 'Telefone'",
									"CIDADE as 'Cidade'",
									"CPF as 'Cpf'",
									"SALARIO as 'Salario'"
								);
				$t[0][0] = "funcionario";
				$t[0][1] = null;
	}
	
	elseif($_GET["t"]=="notas"){
		
		$colunas = array(   "ID_NOTA as ID",
									"0 as 'Pessimo'",
									"1 as 'Ruim'",
									"2 as 'Bom'",
									"3 as 'Exelente'"
								);
				$t[0][0] = "notas";
				$t[0][1] = null;
	}

	elseif($_GET["t"]=="medico"){
		
		$colunas = array(   "ID_MEDICO as ID",
								"NOME_MEDICO as 'Nome do Medico'",
								"EMAIL as 'Email'",
								"TELEFONE as 'Telefone'",
								"CIDADE as 'Cidade'",
								"CRM as 'Crm'"
							);
				$t[0][0] = "medico";
				$t[0][1] = null;
	}

	else if($_GET["t"]=="cidade"){
		
		$colunas = array(   "ID_CIDADE as ID",
									"NOME_CIDADE as 'Nome da cidade'"
									);				
				$t[0][0] = "cidade";
				$t[0][1] = null;
	}
	
	else if($_GET["t"]=="cliente"){
		
		$colunas = array(   "ID_CLIENTE as ID",
									"NOME_CLIENTE as 'Nome do cliente'",
									"EMAIL as 'Email'",
									"cidade.NOME_CIDADE as 'Cidade'",
									"ESTADO as 'Estado'",
									"TELEFONE as 'Telefone'",
									"BAIRRO as 'Bairro'"
									);				
				$t[0][0] = "cliente";
				$t[0][1] = "cidade";
	}

	else if($_GET["t"]=="forma_pagamento"){
		
		$colunas = array(   "ID_FORMA_PAGAMENTO as ID",
									"TIPO_PAGAMENTO as 'Forma de Pagamento'"
									);				
				$t[0][0] = "forma_pagamento";
				$t[0][1] = null;
	}

	else if($_GET["t"]=="grupo"){
		
		$colunas = array(   "ID_GRUPO as ID",
									"DESCRICAO as 'Especificações com Valores'"
									);				
				$t[0][0] = "grupo";
				$t[0][1] = null;
	}

	else if($_GET["t"]=="avaliacao"){
		
		$colunas = array(   "ID_AVALIACAO as ID",
									"DESCRICAO as 'Avaiações'"
									);				
				$t[0][0] = "avaliacao";
				$t[0][1] = null;
	}

	else if($_GET["t"]=="cancelamento"){
		
		$colunas = array(   "ID_CANCELAMENTO as ID",
									"NOME_CLIENTE as 'Nome do Cliente'",
									"MOTIVO as 'Motivo do Cancelamento'"
									);
				$t[0][0] = "cancelamento";
				$t[0][1] = "cliente";

	}

	else if($_GET["t"]=="diagnostico"){
		
		$colunas = array(   "ID_DIAGNOSTICO as ID",
									"medico.NOME_MEDICO as 'Nome do Medico'",
									"cliente.NOME_CLIENTE as 'Nome do Cliente'",
									"DESCRICAO as 'Prescricao Medica'"
									);
				$t[0][0] = "diagnostico";
				$t[0][1] = "medico";
				$t[1][0] = "diagnostico";
				$t[1][1] = "cliente";
	}

	else if($_GET["t"]=="agendamento_consulta"){
		
		$colunas = array(   "ID_CONSULTA as ID",
									"cliente.NOME_CLIENTE as 'Nome do Cliente'",
									"medico.NOME_MEDICO as 'Nome do Medico'",
									"grupo.DESCRICAO as 'Valores'",
									"DIA as 'Data'",
									"HORARIO as 'Hora Marcada'"
									);
				$t[0][0] = "agendamento_consulta";
				$t[0][1] = "cliente";
				$t[1][0] = "agendamento_consulta";
				$t[1][1] = "grupo";
				$t[2][0] = "agendamento_consulta";
				$t[2][1] = "medico";


	}

	else if($_GET["t"]=="pagamento"){
		
		$colunas = array(   "ID_PAGAMENTO as ID",
									"forma_pagamento.TIPO_PAGAMENTO as 'Forma de Pagamento'",
									"grupo.DESCRICAO as 'Nomes e Valores'",
									"cliente.NOME_CLIENTE as 'Nome do Cliente'"
									);
				$t[0][0] = "pagamento";
				$t[0][1] = "forma_pagamento";
				$t[1][0] = "pagamento";
				$t[1][1] = "cliente";
				$t[2][0] = "pagamento";
				$t[2][1] = "grupo";
	}
}
?>