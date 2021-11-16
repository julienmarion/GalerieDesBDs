<?php


class BdBuilder {
	
	private $bd;
	private $dataEntreeParUser;
	private $errorB;
	
	public function __construct($dataEntreeParUser,$errorB=null){
		$this->dataEntreeParUser = $dataEntreeParUser;
		$this->errorB= $errorB;
	}
	
	public function getData(){
		return $this->dataEntreeParUser;
	}


	public function getError(){
		return $this->errorB;
	}
	
	
	public function createBd(){
		
		
		if (isset($_FILES['image']['tmp_name'])) {
			if ($this->moveFile()) {
					$this->bd=new Bd($this->dataEntreeParUser["nom"],$this->dataEntreeParUser["auteur"],$this->dataEntreeParUser["annee"],'upload/'.$_FILES['image']['name'],$this->dataEntreeParUser["description"]);
			} else {
				$this->bd=new Bd($this->dataEntreeParUser["nom"],$this->dataEntreeParUser["auteur"],$this->dataEntreeParUser["annee"],'',$this->dataEntreeParUser["description"]);
			}
			unset($_FILES['image']['tmp_name']);
		} else {
		$this->bd=new Bd($this->dataEntreeParUser["nom"],$this->dataEntreeParUser["auteur"],$this->dataEntreeParUser["annee"],'',$this->dataEntreeParUser["description"]);
		}
	}
	
	public function getBd(){
		return $this->bd;
	}
	
	public function isValid(){
		$this->errorB="";
		if( $this->dataEntreeParUser["nom"]==="") {
			$this->errorB .= "Indiquez le nom. ";
		}
		if ($this->dataEntreeParUser["auteur"]==="") {
			$this->errorB.= "Indiquez l'auteur. ";
		}
		if ($this->dataEntreeParUser["annee"]==="") {
			$this->errorB.= "Indiquez l'annee de création. ";
		} else if (intval($this->dataEntreeParUser["annee"])<0) {
			$this->errorB.= "L'annee doit etre positive.";
		}
		return $this->errorB==="";
	}
	
	public function moveFile(){
		if (move_uploaded_file($_FILES['image']['tmp_name'], 'upload/'.$_FILES['image']['name'])) {
			return true;
		} else {
			return false;
		}
	}

}




?>
