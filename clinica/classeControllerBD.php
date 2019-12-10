<?php
	class ControllerBD{
		
		public $conexao;
		
		public function __construct(PDO $c){
			$this->conexao = $c;
		}
		
		public function erro_bd(){
			return $this->conexao->errorInfo();
		}
		
		public function alterar($campos,$tabela){
			
			$update = "UPDATE ".$tabela." SET ";
			$i=0;
			foreach($campos as $coluna=>$valor){
				if($i!=0){
					$update.= ", ";
				}
				$update.= "$coluna=:$coluna";
				$i++;
			}
			
			$update .= " WHERE id_$tabela=:id_$tabela";
		
			$stmt= $this->conexao->prepare($update);
			
			foreach($campos as $coluna=>$valor){
				$stmt->bindValue(":$coluna",$valor);
			}
			$stmt->bindValue(":id_$tabela",$campos[strtoupper("id_$tabela")]);
			
			//print_r($campos);
			//die($update);
			$stmt->execute();
			
			return(true);
		}
		
		public function remover($id,$tabela){
			$delete = "DELETE FROM $tabela WHERE id_$tabela=:id";
			$stmt = $this->conexao->prepare($delete);
			$stmt->bindValue(":id",$id);
			$stmt->execute();			
		}
		
		public function inserir($campos,$tabela){
			
			$insert = "INSERT INTO $tabela (";
			$i=0;
			foreach($campos as $indice=>$valor){
				if($i==0){
					$insert .= $indice;
					$i++;
				}
				else{
					$insert .= ",".$indice;
				}
			}
			
			$insert .= ") VALUES (";
			
			$i=0;
			foreach($campos as $indice=>$valor){
				if($i==0){
					$insert .= ":".$indice;
					$i++;
				}
				else{
					$insert .= ",:".$indice;
				}
			}
			$insert .= ")";
			
			$stmt = $this->conexao->prepare($insert);
			
			foreach($campos as $indice=>$valor){
				$stmt->bindValue(":".$indice,$valor);
			}
			//print_r($campos);
			//die($insert);
			$r = $stmt->execute();
			
			return($r);
			
		}
		
		
		public function selecionar($colunas,$tabelas,$ordenacao,$condicao,$limite=null){
			$sql = "SELECT ";
			foreach($colunas as $i=>$v){
				if($i!=0){
					$sql .= ", ";
				}
				$sql .= $v;
			}
			
			$sql .= " FROM ";
			
			if($tabelas[0][1]==null){
				$sql .= $tabelas[0][0];
			}
			else{
				foreach($tabelas as $i=>$v){
					if($i==0){
						$sql .= $v[0];
					}
					$sql .= " INNER JOIN ".$v[1];
					$sql .= " ON 
						".$v[0].".id_".$v[1]."=".$v[1].".id_".$v[1];
				}
			}
			if($condicao!=null){
				if(is_array($condicao)){
					$sql .= " WHERE ";
					$i=0;
					foreach($condicao as $vetor){
						
						if($i!=0){
							$sql .=" AND ";
						}
						$sql .= $vetor["coluna"]."='".$vetor["valor"]."'";
						$i++;
						
					}					
				}
				else{
					$sql .= " WHERE ".$tabelas[0][0].
					".id_".$tabelas[0][0]."='$condicao'";
				}
			}
			
			if($ordenacao!=null){
				$sql .= " ORDER BY ".$ordenacao;
			}
			
			if($limite!=null){
				$sql .= " $limite";
			}
			
			//die($sql);
			$stmt = $this->conexao->prepare($sql);
	
			$stmt->execute();
			return($stmt);
		}
		
	}
?>