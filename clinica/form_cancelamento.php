<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeOption.php");

	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_cancelamento","id_cliente","motivo");
		$tabelas[0][0]="cancelamento";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_cancelamento = $linha["id_cancelamento"];
		$selected_id_cliente = $linha["id_cliente"];
		$value_motivo = $linha["motivo"];
	}
	else{
		$action = "insere.php?tabela=cancelamento";
		$value_id_cancelamento="";
		$selected_id_cliente="";
		$value_motivo="";
	}

    //---------------------------------------------------------------------------------------------

	//seleção dos valores que irão criar o <select> de Forma de Cliente//////
    $select = "SELECT ID_CLIENTE AS value, NOME_CLIENTE AS texto FROM cliente ORDER BY NOME_CLIENTE";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_cliente[] = $linha;
	}

    //---------------------------------------------------------------------------------------------
		
	$v = array("action"=>"insere.php?tabela=cancelamento","method"=>"post");
	$f = new Form($v);
	
	$v = array("name"=>"ID_CLIENTE","label"=>"Cliente");
	$f->add_select($v,$matriz_cliente);

	$v = array("type"=>"text","name"=>"MOTIVO","placeholder"=>"MOTIVO DO CANCELAMENTO...","value"=>$value_motivo);
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
			data: {tabela: "cancelamento"},
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
						tabela: "cancelamento" 
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
									0:{0:"cancelamento",1:"cliente"}
								},
						colunas:{0:"id_cancelamento",1:"nome_cliente",2:"motivo"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_cancelamento+"</td>";
						tr += "<td>"+matriz[i].nome_cliente+"</td>";
						tr += "<td>"+matriz[i].motivo+"</td>";
						tr += "<td><button value='"+matriz[i].id_cancelamento+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_cancelamento+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "cancelamento"},
				success: function(dados){
					$("input[name='ID_CANCELAMENTO']").val(dados.ID_CANCELAMENTO);
					$("select[name='ID_CLIENTE']").val(dados.ID_CLIENTE);
					$("input[name='MOTIVO']").val(dados.MOTIVO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

		$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=cancelamento",
					type: "post",
					data: {
						ID_CANCELAMENTO: $("input[name='ID_CANCELAMENTO']").val(),
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val(),
						MOTIVO: $("input[name='MOTIVO']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Cancelamento Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_CANCELAMENTO']").val("");
							$("select[name='ID_CLIENTE']").val("");
							$("input[name='MOTIVO']").val("");
							paginacao(0);
						}
						else{
							console.log(d);
							$("#status").html("Cancelamento Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

		//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=cancelamento",
				type: "post",
				data: {
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val(),
						MOTIVO: $("input[name='MOTIVO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					console.log(d);
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Cancelamento inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(0);
					}
					else if(d=='0'){
						$("#status").html("Cancelamento Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Cancelamento Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
	});
</script>
</body>
</html>