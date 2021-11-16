<?php

require_once "AccountStorage.php";
require_once "index.php";
require_once "Account.php";

class AccountStorageMySQL implements AccountStorage {
	
	private $db;
	
	public function __construct($db) {
		$this->db = $db; 
	}

	public function checkAuth($pseudo, $mdp){
		
		if ($this->exists($pseudo)) {
			$stmt = $this->db->prepare("SELECT mdp FROM accounts WHERE pseudo=:pseudo;");
			$stmt->bindParam(':pseudo',$pseudo);
			$stmt->execute();
			$ligne1 = $stmt->fetch();



			if ($ligne1["mdp"]===$mdp) {
				$stmt2 = $this->db->prepare("SELECT * FROM accounts WHERE pseudo=:pseudo;");
				$stmt2->bindParam(':pseudo',$pseudo);
				$stmt2->execute();
				$ligne2 = $stmt2->fetch();
				$account = new Account($ligne2['pseudo'],$ligne2['mdp'],$ligne2['statut']);
				return $account;
			} else {
				return null;
			} 
		} else {
			return null;
		}
	}


	public function read($idUser){
		$stmt = $this->db->prepare("SELECT * FROM accounts WHERE idUser=:idUser;");
		$stmt->bindParam(':idUser',$idUser);
		$stmt->execute();
		$ligne = $stmt->fetch();
		$account = new Account($ligne['pseudo'],$ligne['mdp'],$ligne['statut']);
		return $account;
		
	}
	
	
	public function readAll(){
		$tabAcc = array();
		$requete = 'SELECT * FROM accounts;';
		$stmt = $this->db->query($requete);
		$tabDB = $stmt->fetchAll();
		foreach ($tabAcc as $ligne) {
			$tabAcc[$ligne['idUser']]=new Account($ligne['pseudo'],$ligne['mdp'],$ligne['statut']);
		}
		return $tabAcc;
		
	}
	
	
	public function exists($pseudo){
		
		$tabAcc = array();
		$requete = 'SELECT * FROM accounts;';
		$stmt = $this->db->query($requete);
		$tabAcc = $stmt->fetchAll();
		foreach ($tabAcc as $ligne) {
			$tabAcc[$ligne['pseudo']]=new Account($ligne['pseudo'],$ligne['mdp'],$ligne['statut']);
		}
		if (key_exists($pseudo,$tabAcc)) {
			return true; }
		return false;
		
	}
	
	public function create(Account $a){
		$pseudo = $a->getPseudo();
		$mdp = $a->getMdp();
		$statut = strval($a->getStatut());
		$stmt = $this->db->prepare("INSERT INTO accounts (pseudo, mdp, statut) VALUES (:pseudo, :mdp, :statut);");
		$stmt->bindParam(':pseudo',$pseudo);
		$stmt->bindParam(':mdp',$mdp);
		$stmt->bindParam(':statut',$statut);
		$stmt->execute();
		$stmt2 = $this->db->prepare("SELECT * FROM accounts WHERE pseudo=:pseudo;");
		$stmt2->bindParam(':pseudo',$pseudo);
		$stmt2->execute();
		
		$ligne = $stmt2->fetch();
		return $ligne['idUser'];
			
		
	}

	
	
}
?>
