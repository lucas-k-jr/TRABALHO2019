<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_medico","nome_medico","email","telefone","cidade","crm");
		$tabelas[0][0]="medico";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_medico = $linha["id_medico"];
		$value_nome_medico = $linha["nome_medico"];
		$value_email = $linha["email"];
		$value_telefone = $linha["telefone"];
		$value_cidade = $linha["cidade"];
		$value_crm = $linha["crm"];
		$action = "altera.php?tabela=medico";
	}
	else{
		$action = "insere.php?tabela=medico";
		$value_id_medico="";
		$value_nome_medico="";
		$value_email="";
		$value_telefone="";
		$value_cidade="";
		$value_crm="";
	}

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=medico","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"text","name"=>"NOME_MEDICO","placeholder"=>"NOME DO MEDICO...","value"=>$value_nome_medico);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"EMAIL","placeholder"=>"EMAIL...","value"=>$value_email);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"TELEFONE","placeholder"=>"TELEFONE...","value"=>$value_telefone);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"CIDADE","placeholder"=>"CIDADE...","value"=>$value_cidade);
	$f->add_input($v);
	$v = array("type"=>"number","name"=>"CRM","placeholder"=>"CRM...","value"=>$value_crm);
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

	pagina_atual = 1;

$(function(){

	carrega_botoes();
		
		function carrega_botoes(){
			
			$.ajax({
				url: "quantidade_botoes.php",
				type: "post",
				data: {tabela: "medico"},
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
					tabela: "medico" 
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
									0:{0:"medico",1:null}
								},
						colunas:{0:"id_medico",1:"nome_medico",2:"email",3:"telefone",4:"cidade",5:"crm"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_medico+"</td>";
						tr += "<td>"+matriz[i].nome_medico+"</td>";
						tr += "<td>"+matriz[i].email+"</td>";
						tr += "<td>"+matriz[i].telefone+"</td>";
						tr += "<td>"+matriz[i].cidade+"</td>";
						tr += "<td>"+matriz[i].crm+"</td>";
						tr += "<td><button value='"+matriz[i].id_medico+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_medico+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "medico"},
				success: function(dados){
					$("input[name='ID_MEDICO']").val(dados.ID_MEDICO);
					$("input[name='NOME_MEDICO']").val(dados.NOME_MEDICO);
					$("input[name='EMAIL']").val(dados.EMAIL);
					$("input[name='TELEFONE']").val(dados.TELEFONE);
					$("input[name='CIDADE']").val(dados.CIDADE);
					$("input[name='CRM']").val(dados.CRM);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=medico",
					type: "post",
					data: {
						ID_MEDICO: id_alterar,
						NOME_MEDICO: $("input[name='NOME_MEDICO']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						TELEFONE: $("input[name='TELEFONE']").val(),
						CIDADE: $("input[name='CIDADE']").val(),
						CRM: $("input[name='CRM']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						console.log(d);
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Cliente Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_MEDICO']").val("");
							$("input[name='NOME_MEDICO']").val("");
							$("input[name='EMAIL']").val("");
							$("input[name='TELEFONE']").val("");
							$("input[name='CIDADE']").val("");
							$("input[name='CRM']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Medico Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=medico",
				type: "post",
				data: {
						ID_MEDICO: $("input[name='ID_MEDICO']").val(),
						NOME_MEDICO: $("input[name='NOME_MEDICO']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						TELEFONE: $("input[name='TELEFONE']").val(),
						CIDADE: $("input[name='CIDADE']").val(),
						CRM: $("input[name='CRM']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Medico inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Medico Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Medico Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});
</script>
</body>
</html>