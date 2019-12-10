<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
    require_once("classeLayout/classForm/classeButton.php");
    require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeOption.php");

	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_diagnostico","id_medico","id_cliente","descricao");
		$tabelas[0][0]="diagnostico";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_diagnostico = $linha["id_diagnostico"];
        $selected_id_medico = $linha["id_medico"];
		$selected_id_cliente = $linha["id_cliente"];
		$value_descricao = $linha["descricao"];
		$action = "altera.php?tabela=diagnostico";
	}
	else{
		$action = "insere.php?tabela=diagnostico";
		$value_id_diagnostico="";
        $selected_id_medico="";
		$selected_id_cliente="";
		$value_descricao="";
    }
    //---------------------------------------------------------------------------------------------

	
    //seleção dos valores que irão criar o <select> de Cliente//////
    $select = "SELECT ID_CLIENTE AS value, NOME_CLIENTE AS texto FROM cliente ORDER BY NOME_CLIENTE";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_cliente[] = $linha;
	}

    //---------------------------------------------------------------------------------------------

	//seleção dos valores que irão criar o <select> de Medico//////
	$select = "SELECT ID_MEDICO AS value, NOME_MEDICO AS texto FROM medico ORDER BY NOME_MEDICO";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_medico[] = $linha;
    }

    //---------------------------------------------------------------------------------------------

	
	$v = array("action"=>"insere.php?tabela=diagnostico","method"=>"post");
    $f = new Form($v);
    
    $v = array("name"=>"ID_MEDICO","label"=>"Medico");
	$f->add_select($v,$matriz_medico);
    
    $v = array("name"=>"ID_CLIENTE","label"=>"Cliente");
	$f->add_select($v,$matriz_cliente);
    
	$v = array("type"=>"text","name"=>"DESCRICAO","placeholder"=>"PRESCRICAO MEDICA...","value"=>$value_descricao);
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
			//alert();
			$.ajax({
				url: "quantidade_botoes.php",
				type: "post",
				data: {tabela: "diagnostico"},
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
							tabela: "diagnostico" 
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
										
										0:{0:"diagnostico",1:"medico"},
										1:{0:"diagnostico",1:"cliente"}
									},
							colunas:{0:"id_diagnostico",1:"nome_medico",2:"nome_cliente",3:"descricao"}, 
							pagina: b
						},
					success: function(matriz){
						console.log(matriz);
						$("tbody").html("");
						for(i=0;i<matriz.length;i++){
							tr = "<tr>";
							tr += "<td>"+matriz[i].id_diagnostico+"</td>";
							tr += "<td>"+matriz[i].nome_medico+"</td>";
							tr += "<td>"+matriz[i].nome_cliente+"</td>";
							tr += "<td>"+matriz[i].descricao+"</td>";
							tr += "<td><button value='"+matriz[i].id_diagnostico+"' class='remover'>Remover</button>";
							tr += "<button value='"+matriz[i].id_diagnostico+"' class='alterar'>Alterar</button></td>";
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
					data: {id: id_alterar, tabela: "diagnostico"},
					success: function(dados){
						$("input[name='ID_DIAGNOSTICO']").val(dados.ID_DIAGNOSTICO);
						$("select[name='ID_CLIENTE']").val(dados.ID_MEDICO);
						$("select[name='ID_MEDICO']").val(dados.ID_CLIENTE);
						$("input[name='DESCRICAO']").val(dados.DESCRICAO);
						$(".cadastrar").attr("class","alterando");
						$(".alterando").html("ALTERAR");
					}
				});
			});

		$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=diagnostico",
					type: "post",
					data: {
						ID_DIAGNOSTICO: id_alterar,
						ID_MEDICO: $("select[name='ID_MEDICO']").val(),
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val(),
						DESCRICAO: $("input[name='DESCRICAO']").val()
					},
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Agendamento Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_DIAGNOSTICO']").val("");
							$("select[name='ID_MEDICO']").val("");
							$("select[name='ID_CLIENTE']").val("");
							$("input[name='DESCRICAO']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Diagnostico Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

			//defina a seguinte regra para o botao de envio
			$(document).on("click",".cadastrar",function(){
				
				$.ajax({
					url: "insere.php?tabela=diagnostico",
					type: "post",
					data: {
							ID_DIAGNOSTICO: $("select[name='ID_DIAGNOSTICO']").val(),
							ID_MEDICO: $("select[name='ID_MEDICO']").val(),
							ID_CLIENTE: $("select[name='ID_CLIENTE']").val(),						
							DESCRICAO: $("input[name='DESCRICAO']").val()
						},
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						console.log(d);
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Diagnostico inserido com sucesso!");
							$("#status").css("color","green");
							carrega_botoes();
							paginacao(0);
						}
						else if(d=='0'){
							$("#status").html("Diagnostico Não inserido! Você não tem permissão!");
							$("#status").css("color","red");
						}
						else{
							console.log(d);
							$("#status").html("Diagnostico Não inserido! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});
		});
</script>
</body>
</html>