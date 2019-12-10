<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_forma_pagamento","tipo_pagamento");
		$tabelas[0][0]="forma_pagamento";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_forma_pagamento = $linha["id_forma_pagamento"];
		$value_tipo_pagamento = $linha["tipo_pagamento"];
		$action = "altera.php?tabela=forma_pagamento";
	}
	else{
		$action = "insere.php?tabela=forma_pagamento";
		$value_id_forma_pagamento="";
		$value_tipo_pagamento="";
	}

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=forma_pagamento","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"text","name"=>"TIPO_PAGAMENTO","placeholder"=>"FORMA DE PAGAMENTO...","value"=>$value_tipo_pagamento);
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
		elseif{
			echo "permissao=1;";
		}
		elseif(isset($_SESSION["funcionario"]) && $_SESSION["funcionario"]["permissao"]==2){
			echo "permissao=2;";
		}
		elseif{
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
				data: {tabela: "forma_pagamento"},
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
						tabela: "forma_pagamento" 
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
									0:{0:"forma_pagamento",1:null}
								},
						colunas:{0:"id_forma_pagamento",1:"tipo_pagamento"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_forma_pagamento+"</td>";
						tr += "<td>"+matriz[i].tipo_pagamento+"</td>";
						tr += "<td><button value='"+matriz[i].id_forma_pagamento+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_forma_pagamento+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "forma_pagamento"},
				success: function(dados){
					$("input[name='ID_FORMA_PAGAMENTO']").val(dados.ID_FORMA_PAGAMENTO);
					$("input[name='TIPO_PAGAMENTO']").val(dados.TIPO_PAGAMENTO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=forma_pagamento",
					type: "post",
					data: {
						ID_FORMA_PAGAMENTO: id_alterar,
						TIPO_PAGAMENTO: $("input[name='TIPO_PAGAMENTO']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Pagamento Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_FORMA_PAGAMENTO']").val("");
							$("input[name='FORMA_PAGAMENTO']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Pagamento Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=forma_pagamento",
				type: "post",
				data: {
						ID_FORMA_PAGAMENTO: $("input[name='ID_FORMA_PAGAMENTO']").val(),
						TIPO_PAGAMENTO: $("input[name='TIPO_PAGAMENTO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Pagamento inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Pagamento Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Pagamento Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});
</script>
</body>
</html>