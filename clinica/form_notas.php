<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_nota","0","1","2","3");
		$tabelas[0][0]="notas";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_nota = $linha["id_nota"];
		$value_0 = $linha["0"];
		$value_1 = $linha["1"];
		$value_2 = $linha["2"];
		$value_3 = $linha["3"];
		$action = "altera.php?tabela=notas";
	}
	else{
		$action = "insere.php?tabela=avaliacao";
		$value_id_nota="";
		$value_0="";
		$value_1="";
		$value_2="";
		$value_3="";
	}

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=notas","method"=>"post");
	$f = new Form($v);
	
    $v = array("type"=>"radio","name"=>"0","placeholder"=>"pessimo...","value"=>$value_0);
    $f->add_input($v);
    $v = array("type"=>"radio","name"=>"1","placeholder"=>"Ruim...","value"=>$value_1);
    $f->add_input($v);
    $v = array("type"=>"radio","name"=>"2","placeholder"=>"Bom...","value"=>$value_2);
    $f->add_input($v);
    $v = array("type"=>"radio","name"=>"3","placeholder"=>"Exelente...","value"=>$value_3);
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
				data: {tabela: "notas"},
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
						tabela: "notas" 
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
									0:{0:"notas",1:null}
								},
						colunas:{0:"id_nota",1:"0",2:"1",3:"2",4:"3"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_nota+"</td>";
						tr += "<td>"+matriz[i].0+"</td>";
						tr += "<td>"+matriz[i].1+"</td>";
						tr += "<td>"+matriz[i].2+"</td>";
						tr += "<td>"+matriz[i].3+"</td>";
						tr += "<td><button value='"+matriz[i].id_nota+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_nota+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "notas"},
				success: function(dados){
					$("input[name='ID_NOTA']").val(dados.ID_NOTA);
					$("input[name='0']").val(dados.0);
					$("input[name='1']").val(dados.1);
					$("input[name='2']").val(dados.2);
					$("input[name='3']").val(dados.3);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=notas",
					type: "post",
					data: {
						ID_NOTAS: id_alterar,
						0: $("input[name='0']").val(),
						1: $("input[name='1']").val(),
						2: $("input[name='2']").val(),
						3: $("input[name='3']").val()
					 },
					beforeSend:function(){
						$("button").attr("disabled",true);
					},
					success: function(d){
						$("button").attr("disabled",false);
						if(d=='1'){
							$("#status").html("Notas Alterado com sucesso!");
							$("#status").css("color","green");
							$(".alterando").attr("class","cadastrar");
							$(".cadastrar").html("CADASTRAR");
							$("input[name='ID_NOTAS']").val("");
							$("input[name='0']").val("");
							$("input[name='1']").val("");
							$("input[name='2']").val("");
							$("input[name='3']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Notas Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=notas",
				type: "post",
				data: {
                        ID_NOTAS: $("input[name='ID_NOTAS']").val(),
						0: $("input[name='0']").val(),
						1: $("input[name='1']").val(),
						2: $("input[name='2']").val(),
						3: $("input[name='3']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Notas inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Notas Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Notas Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});
</script>
</body>
</html>