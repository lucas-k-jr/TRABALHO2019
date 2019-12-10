<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_grupo","descricao");
		$tabelas[0][0]="grupo";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_grupo = $linha["id_grupo"];
		$value_descricao = $linha["descricao"];
		$action = "altera.php?tabela=grupo";
	}
	else{
		$action = "insere.php?tabela=grupo";
		$value_id_grupo="";
		$value_descricao="";
	}

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=grupo","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"text","name"=>"DESCRICAO","placeholder"=>"ESPECIFICACOES COM VALORES...","value"=>$value_descricao);
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
				data: {tabela: "grupo"},
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
						tabela: "grupo" 
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
									0:{0:"grupo",1:null}
								},
						colunas:{0:"id_grupo",1:"descricao"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_grupo+"</td>";
						tr += "<td>"+matriz[i].descricao+"</td>";
						tr += "<td><button value='"+matriz[i].id_grupo+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_grupo+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "grupo"},
				success: function(dados){
					$("input[name='ID_GRUPO']").val(dados.ID_GRUPO);
					$("input[name='DESCRICAO']").val(dados.DESCRICAO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=grupo",
					type: "post",
					data: {
						ID_GRUPO: id_alterar,
						DESCRICAO: $("input[name='DESCRICAO']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Grupo Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_GRUPO']").val("");
							$("input[name='DESCRICAO']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Grupo Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=grupo",
				type: "post",
				data: {
						ID_GRUPO: $("input[name='ID_GRUPO']").val(),
						DESCRICAO: $("input[name='DESCRICAO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Grupo inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Grupo Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Grupo Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});
</script>
</body>
</html>