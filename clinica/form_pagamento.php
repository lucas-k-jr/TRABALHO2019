<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
    require_once("classeLayout/classForm/classeButton.php");
    require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeOption.php");

	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_pagamento","id_forma_pagamento","id_grupo","id_cliente");
		$tabelas[0][0]="pagamento";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_pagamento = $linha["id_pagamento"];
        $selected_id_forma_pagamento = $linha["id_forma_pagamento"];
		$selected_id_grupo = $linha["id_grupo"];
        $selected_id_cliente = $linha["id_cliente"];
		$action = "altera.php?tabela=pagamento";
	}
	else{
		$action = "insere.php?tabela=pagamento";
		$value_id_pagamento="";
        $selected_id_forma_pagamento="";
		$selected_id_grupo="";
        $selected_id_cliente="";
    }
    //---------------------------------------------------------------------------------------------

	
    //seleção dos valores que irão criar o <select> de Forma de Pagamento//////
    $select = "SELECT ID_FORMA_PAGAMENTO AS value, TIPO_PAGAMENTO AS texto FROM forma_pagamento ORDER BY TIPO_PAGAMENTO";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_forma_pagamento[] = $linha;
	}

    //---------------------------------------------------------------------------------------------

	//seleção dos valores que irão criar o <select> de Grupo//////
	$select = "SELECT ID_GRUPO AS value, DESCRICAO AS texto FROM grupo ORDER BY DESCRICAO";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_grupo[] = $linha;
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
    

	$v = array("action"=>"insere.php?tabela=pagamento","method"=>"post");
    $f = new Form($v);
    
    $v = array("name"=>"ID_CLIENTE","label"=>"Cliente");
	$f->add_select($v,$matriz_cliente);
    
    $v = array("name"=>"ID_GRUPO","label"=>"Valores");
    $f->add_select($v,$matriz_grupo);
    
    $v = array("name"=>"ID_FORMA_PAGAMENTO","label"=>"Forma de Pagamento");
	$f->add_select($v,$matriz_forma_pagamento);
    
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
				data: {tabela: "pagamento"},
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
						tabela: "pagamento" 
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
									0:{0:"pagamento",1:"forma_pagamento"},
									1:{0:"pagamento",1:"grupo"},
									2:{0:"pagamento",1:"cliente"}
								},
						colunas:{0:"id_pagamento",1:"tipo_pagamento",2:"descricao",3:"nome_cliente"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_pagamento+"</td>";
						tr += "<td>"+matriz[i].tipo_pagamento+"</td>";
						tr += "<td>"+matriz[i].descricao+"</td>";
						tr += "<td>"+matriz[i].nome_cliente+"</td>";
						tr += "<td><button value='"+matriz[i].id_pagamento+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_pagamento+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "pagamento"},
				success: function(dados){
					$("input[name='ID_PAGAMENTO']").val(dados.ID_PAGAMENTO);
					$("select[name='ID_FORMA_PAGAMENTO']").val(dados.ID_FORMA_PAGAMENTO);
					$("select[name='ID_GRUPO']").val(dados.ID_GRUPO);
					$("select[name='ID_CLIENTE']").val(dados.ID_CLIENTE);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

		$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=pagamento",
					type: "post",
					data: {
						ID_PAGAMENTO: $("input[name='ID_PAGAMENTO']").val(),
						ID_FORMA_PAGAMENTO: $("select[name='ID_FORMA_PAGAMENTO']").val(),
						ID_GRUPO: $("select[name='ID_GRUPO']").val(),
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val()
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
							$("value[name='ID_PAGAMENTO']").val("");
							$("select[name='ID_FORMA_PAGAMENTO']").val("");
							$("select[name='ID_GRUPO']").val("");
							$("select[name='ID_CLIENTE']").val("");
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
				url: "insere.php?tabela=pagamento",
				type: "post",
				data: {
						ID_FORMA_PAGAMENTO: $("select[name='ID_FORMA_PAGAMENTO']").val(),
						ID_GRUPO: $("select[name='ID_GRUPO']").val(),
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					console.log(d);
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Pagamento inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(0);
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