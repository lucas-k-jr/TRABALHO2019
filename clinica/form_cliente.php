<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeOption.php");
	require_once("classeModal.php");
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_cliente","nome_cliente","email","id_cidade","estado","telefone","bairro");
		$tabelas[0][0]="cliente";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_cliente = $linha["id_cliente"];
		$value_nome_cliente = $linha["nome_cliente"];
		$value_email = $linha["email"];
		$selected_id_cidade = $linha["id_cidade"];
		$value_estado = $linha["estado"];
		$value_telefone = $linha["telefone"];
		$value_bairro = $linha["bairro"];
		$action = "altera.php?tabela=cliente";
	}
	else{
		$action = "insere.php?tabela=cliente";
		$value_id_cliente="";
		$value_nome_cliente="";
		$value_email="";
		$selected_id_cidade="";
		$value_estado="";
		$value_telefone="";
		$value_bairro="";
	}

	//---------------------------------------------------------------------------------------------

	//seleção dos valores que irão criar o <select> de Forma de Cidade//////
    $select = "SELECT ID_CIDADE AS value, NOME_CIDADE AS texto FROM cidade ORDER BY NOME_CIDADE";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_cidade[] = $linha;
	}

    //---------------------------------------------------------------------------------------------

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=cliente","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"text","name"=>"NOME_CLIENTE","placeholder"=>"NOME DO CLIENTE...","value"=>$value_nome_cliente);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"EMAIL","placeholder"=>"EMAIL...","value"=>$value_email);
	$f->add_input($v);

	$v = array("name"=>"ID_CIDADE","label"=>"Cidade");
	$f->add_select($v,$matriz_cidade);

	$v = array("type"=>"text","name"=>"ESTADO","placeholder"=>"ESTADO...","value"=>$value_estado);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"TELEFONE","placeholder"=>"TELEFONE...","value"=>$value_telefone);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"BAIRRO","placeholder"=>"BAIRRO...","value"=>$value_bairro);
	$f->add_input($v);
	$v = array("type"=>"button", "class"=>"cadastrar", "texto"=>"CADASTRAR");
	$f->add_button($v);


	$v["titulo"] = "Cadastrar Cliente";
	$v["acao"] = "Cadastrar";
	$v["form"] = $f;
	$m = new Modal($v);


?>
<div id="status"></div>
<hr />
<?php
		$m->exibe();

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
				data: {tabela: "cliente"},
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
						tabela: "cliente" 
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
									0:{0:"cliente",1:"cidade"}
								},
						colunas:{0:"id_cliente",1:"nome_cliente",2:"email",3:"nome_cidade",4:"estado",5:"telefone",6:"bairro"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_cliente+"</td>";
						tr += "<td>"+matriz[i].nome_cliente+"</td>";
						tr += "<td>"+matriz[i].email+"</td>";
						tr += "<td>"+matriz[i].nome_cidade+"</td>";
						tr += "<td>"+matriz[i].estado+"</td>";
						tr += "<td>"+matriz[i].telefone+"</td>";
						tr += "<td>"+matriz[i].bairro+"</td>";
						tr += "<td><button value='"+matriz[i].id_cliente+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_cliente+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "cliente"},
				success: function(dados){
					$("input[name='ID_CLIENTE']").val(dados.ID_CLIENTE);
					$("input[name='NOME_MEDICO']").val(dados.NOME_CLIENTE);
					$("input[name='EMAIL']").val(dados.EMAIL);
					$("select[name='ID_CIDADE']").val(dados.ID_CIDADE);
					$("input[name='ESTADO']").val(dados.ESTADO);
					$("input[name='TELEFONE']").val(dados.TELEFONE);
					$("input[name='BAIRRO']").val(dados.BAIRRO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=cliente",
					type: "post",
					data: {
						ID_CLIENTE: id_alterar,
						NOME_CLIENTE: $("input[name='NOME_CLIENTE']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						ID_CIDADE: $("select[name='ID_CIDADE']").val(),
						ESTADO: $("input[name='ESTADO']").val(),
						TELEFONE: $("input[name='TELEFONE']").val(),
						BAIRRO: $("input[name='BAIRRO']").val(),
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Cliente Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_CLIENTE']").val("");
							$("input[name='NOME_CLIENTE']").val("");
							$("input[name='EMAIL']").val("");
							$("select[name='ID_CIDADE']").val("");
							$("input[name='ESTADO']").val("");
							$("input[name='TELEFONE']").val("");
							$("input[name='BAIRRO']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Cliente Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=cliente",
				type: "post",
				data: {
						NOME_CLIENTE: $("input[name='NOME_CLIENTE']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						ID_CIDADE: $("select[name='ID_CIDADE']").val(),
						ESTADO: $("input[name='ESTADO']").val(),
						TELEFONE: $("input[name='TELEFONE']").val(),
						BAIRRO: $("input[name='BAIRRO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Cliente inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Cliente Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Cliente Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});

$(document).ready(function(){
$("#myBtn").click(function(){
$("#myModal").modal();
});
});
</script>
</body>
</html>