<?php
	require_once("classeLayout/classeCabecalhoHTML.php");
	include("cabecalho.php");
	require_once("classeLayout/classForm/interfaceExibicao.php");
	require_once("classeLayout/classForm/classeInput.php");
	require_once("classeLayout/classForm/classeOption.php");
	require_once("classeLayout/classForm/classeSelect.php");
	require_once("classeLayout/classForm/classeButton.php");
	require_once("classeLayout/classForm/classeForm.php");
	require_once("classeModal.php");
	include("conexao.php");
	
	$v = array("action"=>"validador_login.php","method"=>"post");
	$f = new Form($v);
	
	$v = array("type"=>"email","name"=>"login","placeholder"=>"login...","value"=>"");
	$f->add_input($v);
	$v = array("type"=>"password","name"=>"senha","placeholder"=>"senha...","value"=>"");
	$f->add_input($v);
	$v = array("type"=>"submit","texto"=>"Logar","id"=>"logar");
	$f->add_button($v);	

	$v["titulo"] = "Login";
	$v["acao"] = "Logar";
	$v["form"] = $f;
	$m = new Modal($v);


?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<style> input{margin:4px;}</style>
	</head>
<body>
<h3>LOGIN</h3>
<hr />
<?php
if(isset($_SESSION["msg_erro"])){
	echo $_SESSION["msg_erro"];
	unset($_SESSION["msg_erro"]);
}
?>
<hr />
<?php
	$m->exibe();
?>
 <script>
              $(document).ready(function(){
                $("#myBtn").click(function(){
                  $("#myModal").modal();
                });
              });
              </script>

</body>
</html>