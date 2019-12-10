<?php
	//include("interfaceExibicao.php");
	
	class Input implements Exibicao{
		private $type;
		private $name;
		private $placeholder;
		private $value;
		private $disabled=false;
		private $label=null;
		
		public function __construct($vetor){
			$this->type=$vetor["type"];
			$this->name=$vetor["name"];
			$this->value=$vetor["value"];
			if(isset($vetor["placeholder"])){
				$this->placeholder=$vetor["placeholder"];
			}
			if(isset($vetor["disabled"])){
				$this->disabled=$vetor["disabled"];
			}
			if(isset($vetor["label"])){
				$this->label=$vetor["label"];
			}
	}

	public function exibe(){
		echo"
		<label for='id_$this->name'><span class='glyphicon glyphicon-user'></span>$this->label</label>
		<input class='form-control' id='id_$this->name' type='$this->type' name='$this->name' value='$this->value' ";
		if($this->placeholder!=null){
			echo "placeholder='$this->placeholder' ";
		}
		if($this->disabled){
			echo "disabled";
		}
		echo " />";
		}
	}
?>