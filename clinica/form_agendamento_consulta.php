<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
    require_once("classeLayout/classForm/classeButton.php");
    require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeOption.php");

	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_consulta","id_cliente","id_medico","id_grupo","dia","horario");
		$tabelas[0][0]="agendamento_consulta";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_diagnostico = $linha["id_consulta"];
        $selected_id_cliente = $linha["id_cliente"];
		$selected_id_medico = $linha["id_medico"];
        $selected_id_grupo = $linha["id_grupo"];
		$value_dia = $linha["dia"];
		$value_horario = $linha["horario"];
		$action = "altera.php?tabela=agendamento_consulta";
	}
	else{
		$action = "insere.php?tabela=agendamento_consulta";
		$value_id_consulta="";
        $selected_id_cliente="";
		$selected_id_medico="";
        $selected_id_grupo="";
		$value_dia="";
		$value_horario="";
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

    //seleção dos valores que irão criar o <select> de Grupo//////
	$select = "SELECT ID_GRUPO AS value, DESCRICAO AS texto FROM grupo ORDER BY DESCRICAO";
	
	$stmt = $conexao->prepare($select);
	$stmt->execute();
	
	while($linha=$stmt->fetch()){
		$matriz_grupo[] = $linha;
    }
    
    //---------------------------------------------------------------------------------------------
    

	$v = array("action"=>"insere.php?tabela=agendamento_consulta","method"=>"post");
    $f = new Form($v);
    
    $v = array("name"=>"ID_MEDICO","label"=>"Medico");
	$f->add_select($v,$matriz_medico);
    
    $v = array("name"=>"ID_CLIENTE","label"=>"Cliente");
    $f->add_select($v,$matriz_cliente);
    
    $v = array("name"=>"ID_GRUPO","label"=>"Nomes e Valores");
	$f->add_select($v,$matriz_grupo);
    
	$v = array("type"=>"date","name"=>"DIA","placeholder"=>"Dia da Semana...","value"=>$value_dia);
    $f->add_input($v);

    $v = array("type"=>"time","name"=>"HORARIO","placeholder"=>"Horario da Semana...","value"=>$value_horario);
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
					data: {tabela: "agendamento_consulta"},
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
							tabela: "agendamento_consulta" 
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
									0:{0:"agendamento_consulta",1:"cliente"},
									1:{0:"agendamento_consulta",1:"medico"},
									2:{0:"agendamento_consulta",1:"grupo"}
								},
						colunas:{0:"id_consulta",1:"nome_cliente",2:"nome_medico",3:"descricao",4:"dia",5:"horario"}, 
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_consulta+"</td>";
						tr += "<td>"+matriz[i].nome_cliente+"</td>";
						tr += "<td>"+matriz[i].nome_medico+"</td>";
						tr += "<td>"+matriz[i].descricao+"</td>";
						tr += "<td>"+matriz[i].dia+"</td>";
						tr += "<td>"+matriz[i].horario+"</td>";
						tr += "<td><button value='"+matriz[i].id_consulta+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_consulta+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "agendamento_consulta"},
				success: function(dados){
					$("input[name='ID_CONSULTA']").val(dados.ID_CONSULTA);
					$("select[name='ID_CLIENTE']").val(dados.ID_CLIENTE);
					$("select[name='ID_MEDICO']").val(dados.ID_MEDICO);
					$("select[name='ID_GRUPO']").val(dados.ID_GRUPO);
					$("input[name='DIA']").val(dados.DIA);
					$("input[name='HORARIO']").val(dados.HORARIO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

		$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=agendamento_consulta",
					type: "post",
					data: {
						ID_CONSULTA: $("input[name='ID_CONSULTA']").val(),
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val(),
						ID_MEDICO: $("select[name='ID_MEDICO']").val(),
						ID_GRUPO: $("select[name='ID_GRUPO']").val(),
						DIA: $("input[name='DIA']").val(),
						HORARIO: $("input[name='HORARIO']").val()
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
							$("input[name='ID_CONSULTA']").val("");
							$("select[name='ID_CLIENTE']").val("");
							$("select[name='ID_MEDICO']").val("");
							$("select[name='ID_GRUPO']").val("");
							$("input[name='DIA']").val("");
							$("input[name='HORARIO']").val("");
							paginacao(0);
						}
						else{
							console.log(d);
							$("#status").html("Consulta Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

		//defina a seguinte regra para o botao de envio
		$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=agendamento_consulta",
				type: "post",
				data: {
						ID_CLIENTE: $("select[name='ID_CLIENTE']").val(),
						ID_MEDICO: $("select[name='ID_MEDICO']").val(),
						ID_GRUPO: $("select[name='ID_GRUPO']").val(),						
						DIA: $("input[name='DIA']").val(),
						HORARIO: $("input[name='HORARIO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					console.log(d);
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Consulta inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(0);
					}
					else if(d=='0'){
						$("#status").html("Consulta Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Consulta Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
	});
</script>
</body>
</html>