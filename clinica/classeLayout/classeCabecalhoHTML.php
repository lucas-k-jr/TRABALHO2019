<?php
	class CabecalhoHTML{
		private $menu;
		public function exibe(){
			echo '<!DOCTYPE html>
				  <html>
				     <head>
						<meta charset="utf-8" />
						<style>
							select, textarea, input{margin:5px;}							
						</style>						
						<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
						<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
						<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
						<style>
							body {font-family: Arial, Helvetica, sans-serif;}
				
							/* Modal Content */
							.modal-content {
							  position: relative;
							  background-color: #fefefe;
							  margin: auto;
							  padding: 0;
							  border: 1px solid #888;
							  width: 80%;
							  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
							  -webkit-animation-name: animatetop;
							  -webkit-animation-duration: 0.4s;
							  animation-name: animatetop;
							  animation-duration: 0.4s
							}
				
							/* Add Animation */
							@-webkit-keyframes animatetop {
							  from {top:-300px; opacity:0} 
							  to {top:0; opacity:1}
							}
				
							@keyframes animatetop {
							  from {top:-300px; opacity:0}
							  to {top:0; opacity:1}
							}
				
							/* The Close Button */
							.close {
							  color: white;
							  float: right;
							  font-size: 28px;
							  font-weight: bold;
							}
				
							.close:hover,
							.close:focus {
							  color: #000;
							  text-decoration: none;
							  cursor: pointer;
							}
				
							.modal-header {
							  padding: 2px 16px;
							  background-color: #5cb85c;
							  color: white;
							}
				
							.modal-body {padding: 2px 16px;}
				
							.modal-footer {
							  padding: 2px 16px;
							  background-color: #5cb85c;
							  color: white;
							}
							body{
								background-attachment: fixed;
								background-size:100%;
								
							}
						</style>
				
					 <body>
					 <nav>
					 <b>Listar:</b> <br />
			';

			if($this->menu!=null){
				foreach($this->menu as $tabela=>$texto){
					echo "| <a href='listar.php?t=$tabela'>$texto</a> ";
				}
				/*echo "<br /><br />
					  <b>Cadastrar:</b> <br />";
				foreach($this->menu as $tabela=>$texto){
					echo "| <a href='form_$tabela.php'>$texto</a>";
				}*/
				
				echo "</nav>
				<hr />";
				}
		}
		
		public function add_menu($tabela){
			$this->menu = $tabela;
		}
		
		
	}
?>