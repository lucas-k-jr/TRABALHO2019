<?php

    //require_once("interfaceExibicao.php");

    class Option implements Exibicao{
        private $value;
        private $texto;

        public function __construct($vetor){
            if(isset($vetor["value"])){
                $this->value=$vetor["value"];
            }
            $this->texto=$vetor["texto"];
        }

        public function Exibe(){
            echo "<option";
            if($this->value != null){
                echo " value='$this->value'";
            }
            echo ">$this->texto</option>";
        }

    }


?>