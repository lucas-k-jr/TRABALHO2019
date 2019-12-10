<?php
	require_once("classForm/interfaceExibicao.php");
	
	class Tabela implements Exibicao{
		private $matriz;
		private $tabela;
		
		public function __construct($matriz,$tabela){
			$this->matriz = $matriz;
			$this->tabela = $tabela;
		}
		
		public function exibe(){
			echo "<table border='1'>";
			
			foreach($this->matriz as $i=>$v){
				if($i==0){
					echo "<thead>";
					echo "<tr>";
					foreach($v as $j=>$d){
						echo "<th>".$j."</th>";
					}
					echo "<th>Ação</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
				}
					echo "<tr>";
					foreach($v as $j=>$d){
						echo "<td>".$d."</td>";
					}
					echo "<td>
							<button value='".$v["ID"]."' class='remover'>Remover</button>

							<button value='".$v["ID"]."' class='alterar'>Alterar</button>
							
							</td>";
					echo "</tr>";
			}
			echo "</tbody>";
			
			echo "</table>";
			echo "<div id='botoes'></div>";
		}


		
	}
?>