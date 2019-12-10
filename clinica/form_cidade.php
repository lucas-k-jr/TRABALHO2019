<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeOption.php");

	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_cidade","nome_cidade");
		$tabelas[0][0]="cidade";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_cidade = $linha["id_cidade"];
		$value_nome_cidade = $linha["nome_cidade"];
	}
	else{
		$action = "insere.php?tabela=cidade";
		$value_id_cidade="";
		$value_nome_cidade="";
	}

    //---------------------------------------------------------------------------------------------
		
	$v = array("action"=>"insere.php?tabela=cidade","method"=>"post");
	$f = new Form($v);

    $v = array("type"=>"text","name"=>"NOME_CIDADE","placeholder"=>"INSIRA A CIDADE...","value"=>$value_nome_cidade);
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
		//alert();
		$.ajax({
			url: "quantidade_botoes.php",
			type: "post",
			data: {tabela: "cidade"},
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
						tabela: "cidade" 
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
									0:{0:"cidade",1:null}
								},
						colunas:{0:"id_cidade",1:"nome_cidade"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_cidade+"</td>";
						tr += "<td>"+matriz[i].nome_cidade+"</td>";
						tr += "<td><button value='"+matriz[i].id_cidade+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_cidade+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "cidade"},
				success: function(dados){
					$("input[name='ID_CIDADE']").val(dados.ID_CIDADE);
					$("input[name='NOME_CIDADE']").val(dados.NOME_CIDADE);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

		$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=cidade",
					type: "post",
					data: {
						ID_CIDADE: id_alterar,
						NOME_CIDADE: $("input[name='NOME_CIDADE']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Cidade Alterada com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_CIDADE']").val("");
							$("input[name='NOME_CIDADE']").val("");
							paginacao(0);
						}
						else{
							console.log(d);
							$("#status").html("Cidade Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

		//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=cidade",
				type: "post",
				data: {
						NOME_CIDADE: $("input[name='NOME_CIDADE']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					console.log(d);
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Cidade inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(0);
					}
					else if(d=='0'){
						$("#status").html("Cidade Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Cidade Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
	});
</script>
</body>
</html>