  <?php

	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_funcionario","nome_funcionario","email","telefone","cidade","cpf","salario");
		$tabelas[0][0]="funcionario";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_funcionario = $linha["id_funcionario"];
		$value_nome_funcionario = $linha["nome_funcionario"];
		$value_email = $linha["email"];
		$value_telefone = $linha["telefone"];
		$value_cidade = $linha["cidade"];
		$value_cpf = $linha["cpf"];
		$value_salario = $linha["salario"];
		$action = "altera.php?tabela=funcionario";
	}
	else{
		$action = "insere.php?tabela=funcionario";
		$value_id_funcionario="";
		$value_nome_funcionario="";
		$value_email="";
		$value_telefone="";
		$value_cidade="";
		$value_cpf="";
		$value_salario="";
	}

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=funcionario","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"text","name"=>"NOME_FUNCIONARIO","placeholder"=>"NOME DO FUNCIONARIO...","value"=>$value_nome_funcionario);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"EMAIL","placeholder"=>"EMAIL...","value"=>$value_email);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"TELEFONE","placeholder"=>"TELEFONE...","value"=>$value_telefone);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"CIDADE","placeholder"=>"CIDADE...","value"=>$value_cidade);
	$f->add_input($v);
	$v = array("type"=>"number","name"=>"CPF","placeholder"=>"CPF...","value"=>$value_cpf);
	$f->add_input($v);
	$v = array("type"=>"number","name"=>"SALARIO","placeholder"=>"SALARIO...","value"=>$value_salario);
	$f->add_input($v);	
	$v = array("type"=>"button", "class"=>"cadastrar", "texto"=>"CADASTRAR");
	$f->add_button($v);
?>
<div id="status"></div>
<hr />
<?php
	echo "<fieldset>";
	echo "<legend>Formulario</legend>";
	echo "<br />";

		$f->exibe();
		
	echo "</fieldset>";
?>
<script>
id_alterar = null;
	<?php 
		if(isset($_SESSION["usuario"]) && $_SESSION["usuario"]["permissao"]==1){
			echo "permissao=1;";
		}
		else{
			echo "permissao=1;";
		}
	?>
	pagina_atual = 0;

$(function(){

	carrega_botoes();
		
		function carrega_botoes(){
			
			$.ajax({
				url: "quantidade_botoes.php",
				type: "post",
				data: {tabela: "funcionario"},
				success: function(q){
					console.log(q);
					$("#botoes").html("");
					for(i=1;i<=q;i++){
						botao = " <button type='button' class='pg'>" + i + "</button>";
						$("#botoes").append(botao);
					}
				}
			});
		}

		$(document).on("click",".remover",function(){
			id_remover = $(this).val();
			
			$.ajax({
				url: "remover.php",
				type: "post",
				data: {
						id: id_remover,
						tabela: "funcionario" 
					  },
				success: function(d){					
					if(d=='1'){
						$("#status").html("Removido com sucesso");
						carrega_botoes();
						qtd = $("tbody tr").length;
						if(qtd=="1"){
							pagina_atual--;
						}
						paginacao(pagina_atual);
					}
					else if(d=="0"){
						$("#status").html("Você não tem permissão para remover este dado.");
					}
					else {
						console.log(d);
						$("#status").html("Você não está logado.");
					}	
				}
			});
		});
		
		$(document).on("click",".pg",function(){
			valor_botao = $(this).html();
			paginacao(valor_botao);
		});
		
		function paginacao(b){
			$.ajax({
				url: "carrega_dados.php",
				type: "post",
				data: {
						tabelas:{
									0:{0:"funcionario",1:null}
								},
						colunas:{0:"id_funcionario",1:"nome_funcionario",2:"email",3:"telefone",4:"cidade",5:"cpf",6:"salario"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_funcionario+"</td>";
						tr += "<td>"+matriz[i].nome_funcionario+"</td>";
						tr += "<td>"+matriz[i].email+"</td>";
						tr += "<td>"+matriz[i].telefone+"</td>";
						tr += "<td>"+matriz[i].cidade+"</td>";
						tr += "<td>"+matriz[i].cpf+"</td>";
						tr += "<td>"+matriz[i].salario+"</td>";
						tr += "<td><button value='"+matriz[i].id_funcionario+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_funcionario+"' class='alterar'>Alterar</button></td>";
						tr += "</tr>";	
						$("tbody").append(tr);
					}
				}
			});
		}

	$(document).on("click",".alterar",function(){ 
			id_alterar = $(this).val();			
			$.ajax({
				url: "get_dados_form.php",
				type: "post",
				data: {id: id_alterar, tabela: "funcionario"},
				success: function(dados){
					$("input[name='ID_FUNCIONARIO']").val(dados.ID_FUNCIONARIO);
					$("input[name='NOME_FUNCIONARIO']").val(dados.NOME_FUNCIONARIO);
					$("input[name='EMAIL']").val(dados.EMAIL);
					$("input[name='TELEFONE']").val(dados.TELEFONE);
					$("input[name='CIDADE']").val(dados.CIDADE);
					$("input[name='CPF']").val(dados.CPF);
					$("input[name='SALARIO']").val(dados.SALARIO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=funcionario",
					type: "post",
					data: {
						ID_FUNCIONARIO: id_alterar,
						NOME_FUNCIONARIO: $("input[name='NOME_FUNCIONARIO']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						TELEFONE: $("input[name='TELEFONE']").val(),
						CIDADE: $("input[name='CIDADE']").val(),
						CPF: $("input[name='CPF']").val(),
						SALARIO: $("input[name='SALARIO']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Funcionario Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_FUNCIONARIO']").val("");
							$("input[name='NOME_FUNCIONARIO']").val("");
							$("input[name='EMAIL']").val("");
							$("input[name='TELEFONE']").val("");
							$("input[name='CIDADE']").val("");
							$("input[name='CPF']").val("");
							$("input[name='SALARIO']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Funcionario Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=funcionario",
				type: "post",
				data: {
						ID_FUNCIONARIO: $("input[name='ID_FUNCIONARIO']").val(),
						NOME_FUNCIONARIO: $("input[name='NOME_FUNCIONARIO']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						TELEFONE: $("input[name='TELEFONE']").val(),
						CIDADE: $("input[name='CIDADE']").val(),
						CPF: $("input[name='CPF']").val(),				
						SALARIO: $("input[name='SALARIO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Funcionario inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Funcionario Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Funcionario Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});
</script>
</body>
</html>