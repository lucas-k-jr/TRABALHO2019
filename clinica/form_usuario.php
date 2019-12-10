<?php
	
	require_once("classeLayout/classForm/classeInput.php");	
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeLayout/classForm/classeButton.php");
	include("conexao.php");
	
	if(isset($_POST["id"])){
		$c = new ControllerBD($conexao);
		$colunas=array("id_usuario","nome","login","senha","cpf","email","permissao");
		$tabelas[0][0]="usuario";
		$tabelas[0][1]=null;
		$ordenacao = null;
		$condicao = $_POST["id"];
		
		$stmt = $c->selecionar($colunas,$tabelas,$ordenacao,$condicao);
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);
		$value_id_usuario = $linha["id_usuario"];
		$value_nome = $linha["nome"];
		$value_login = $linha["login"];
		$value_senha = $linha["senha"];
		$value_cpf = $linha["cpf"];
		$value_email = $linha["email"];
		$value_permissao = $linha["permissao"];
		$action = "altera.php?tabela=usuario";
	}
	else{
		$action = "insere.php?tabela=usuario";
		$value_id_usuario="";
		$value_nome="";
		$value_login="";
		$value_senha="";
		$value_cpf="";
		$value_email="";
		$value_permissao="";
	}

	////////////////////////////////////////////////////	
	$v = array("action"=>"insere.php?tabela=usuario","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"text","name"=>"NOME","placeholder"=>"NOME...","value"=>$value_nome);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"LOGIN","placeholder"=>"LOGIN...","value"=>$value_login);
	$f->add_input($v);
	$v = array("type"=>"password","name"=>"SENHA","placeholder"=>"SENHA...","value"=>$value_senha);
	$f->add_input($v);
	$v = array("type"=>"number","name"=>"CPF","placeholder"=>"CPF...","value"=>$value_cpf);
	$f->add_input($v);
	$v = array("type"=>"text","name"=>"EMAIL","placeholder"=>"EMAIL...","value"=>$value_email);
    $f->add_input($v);
    $v = array("type"=>"number","name"=>"PERMISSAO","placeholder"=>"PERMISSAO...","value"=>$value_permissao);
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
				data: {tabela: "usuario"},
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
					tabela: "usuario" 
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
									0:{0:"usuario",1:null}
								},
						colunas:{0:"id_usuario",1:"nome",2:"login",3:"senha",4:"cpf",5:"email",6:"permissao"},
						pagina: b
					  },
				success: function(matriz){
					console.log(matriz);
					$("tbody").html("");
					for(i=0;i<matriz.length;i++){
						tr = "<tr>";
						tr += "<td>"+matriz[i].id_usuario+"</td>";
						tr += "<td>"+matriz[i].nome+"</td>";
						tr += "<td>"+matriz[i].login+"</td>";
						tr += "<td>"+matriz[i].senha+"</td>";
						tr += "<td>"+matriz[i].cpf+"</td>";
						tr += "<td>"+matriz[i].email+"</td>";
						tr += "<td>"+matriz[i].permissao+"</td>";
						tr += "<td><button value='"+matriz[i].id_usuario+"' class='remover'>Remover</button>";
						tr += "<button value='"+matriz[i].id_usuario+"' class='alterar'>Alterar</button></td>";
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
				data: {id: id_alterar, tabela: "usuario"},
				success: function(dados){
					$("input[name='ID_USUARIO']").val(dados.ID_USUARIO);
					$("input[name='NOME']").val(dados.NOME);
					$("input[name='LOGIN']").val(dados.LOGIN);
					$("input[name='SENHA']").val(dados.SENHA);
					$("input[name='CPF']").val(dados.CPF);
					$("input[name='EMAIL']").val(dados.EMAIL);
					$("input[name='PERMISSAO']").val(dados.PERMISSAO);
					$(".cadastrar").attr("class","alterando");
					$(".alterando").html("ALTERAR");
				}
			});
		});

	$(document).on("click",".alterando",function(){
				
				$.ajax({
					url:"altera.php?tabela=usuario",
					type: "post",
					data: {
						ID_USUARIO: id_alterar,
						NOME: $("input[name='NOME']").val(),
						LOGIN: $("input[name='LOGIN']").val(),
						SENHA: $("input[name='SENHA']").val(),
						CPF: $("input[name='CPF']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						PERMISSAO: $("input[name='PERMISSAO']").val()
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
							$("input[name='ID_USUARIO']").val("");
							$("input[name='NOME']").val("");
							$("input[name='LOGIN']").val("");
							$("input[name='SENHA']").val("");
							$("input[name='CPF']").val("");
							$("input[name='EMAIL']").val("");
							$("input[name='PERMISSAO']").val("");
							paginacao(pagina_atual);
						}
						else{
							console.log(d);
							$("#status").html("Usuario Não Alterado! Código já existe!");
							$("#status").css("color","red");
						}
					}
				});
			});

	//defina a seguinte regra para o botao de envio
	$(document).on("click",".cadastrar",function(){
			
			$.ajax({
				url: "insere.php?tabela=usuario",
				type: "post",
				data: {
                        ID_USUARIO: $("input[name='ID_USUARIO']").val(),
						NOME: $("input[name='NOME']").val(),
						LOGIN: $("input[name='LOGIN']").val(),
						SENHA: $("input[name='SENHA']").val(),
						CPF: $("input[name='CPF']").val(),
						EMAIL: $("input[name='EMAIL']").val(),
						PERMISSAO: $("input[name='PERMISSAO']").val()
					 },
				beforeSend:function(){
					$("button").attr("disabled",true);
				},
				success: function(d){
					$("button").attr("disabled",false);
					if(d=='1'){
						$("#status").html("Usuario inserido com sucesso!");
						$("#status").css("color","green");
						carrega_botoes();
						paginacao(pagina_atual);
					}
					else if(d=='0'){
						$("#status").html("Usuario Não inserido! Você não tem permissão!");
						$("#status").css("color","red");
					}
					else{
						console.log(d);
						$("#status").html("Usuario Não inserido! Código já existe!");
						$("#status").css("color","red");
					}
				}
			});
		});
});
</script>
</body>
</html>