<?php

require_once "BdStorage.php";
require_once "index.php";

class BdStorageMySQL implements BdStorage {
	
	private $db;
	
	public function __construct($db) {
		$this->db = $db;
	}


	public function read($id){
		$stmt = $this->db->prepare("SELECT * FROM bds WHERE id=:id;");
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		$ligne = $stmt->fetch();
		$bd = new Bd($ligne['nom'],$ligne['auteur'],$ligne['annee'],$ligne['image'],$ligne['description']);
		return $bd;
		
	}
	
	
	public function readAll(){
		$tabBd = array();
		$requete = 'SELECT * FROM bds;';
		$stmt = $this->db->query($requete);
		$tabDB = $stmt->fetchAll();
		foreach ($tabDB as $ligne) {
			$tabBd[$ligne['id']]=new Bd($ligne['nom'],$ligne['auteur'],$ligne['annee'],$ligne['image'],$ligne['description']);
		}
		return $tabBd;
		
	}
	
	
	public function exists($id){
		
		$tabBd = array();
		$requete = 'SELECT * FROM bds;';
		$stmt = $this->db->query($requete);
		$tabDB = $stmt->fetchAll();
		foreach ($tabDB as $ligne) {
			$tabBd[$ligne['id']]=new Bd($ligne['nom'],$ligne['auteur'],$ligne['annee'],$ligne['image'],$ligne['description']);
		}
		if (key_exists($id,$tabBd)) {
			return true; }
		return false;
		
	}

	public function bdDejaCreee($nom){
		$tabBd = array();
		$stmt = $this->db->prepare("SELECT * FROM bds;");
		$stmt->execute();
		$tabDB = $stmt->fetchAll();
		foreach ($tabDB as $ligne) {
			$tabBd[$ligne['nom']]=new Bd($ligne['nom'],$ligne['auteur'],$ligne['annee'],$ligne['image'],$ligne['description']);
		}
		if (key_exists($nom,$tabBd)) {
			return true; }
		return false;

	}


	public function getIdUser(Account $a){
		$pseudo=$a->getPseudo();
		$stmt = $this->db->prepare("SELECT * FROM accounts WHERE pseudo=:pseudo;");
		$stmt->bindParam(':pseudo',$pseudo);
		$stmt->execute();
		$ligne=$stmt->fetch();
		return $ligne['idUser'];
	}

	public function getBdCreatorId($nom){
		$stmt = $this->db->prepare("SELECT * FROM bds WHERE nom=:nom;");
		$stmt->bindParam(':nom',$nom);
		$stmt->execute();
		$ligne = $stmt->fetch();
		return $ligne['idUser'];
	}

	public function getId($nom){
		$stmt = $this->db->prepare("SELECT id FROM bds WHERE nom=:nom;");
		$stmt->bindParam(':nom',$nom);
		$stmt->execute();
		$ligne = $stmt->fetch();
		return $ligne['id'];
	}

	
	public function create(Bd $b){
		$stmt = $this->db->prepare("INSERT INTO bds (nom, auteur, annee,idUser,image,description) VALUES (:nom, :auteur, :annee,:idUser,:image,:description);");
		$nom = $b->getNom();
		$auteur = $b->getAuteur();
		$annee = $b->getAnnee();
		$idUser = $this->getIdUser($_SESSION['user']);
		$image = $b->getImage();
		$description = $b->getDescription();
		$stmt->bindParam(':nom',$nom);
		$stmt->bindParam(':auteur',$auteur);
		$stmt->bindParam(':annee',$annee);
		$stmt->bindParam(':idUser',$idUser);
		$stmt->bindParam(':image',$image);
		$stmt->bindParam(':description',$description);
		$stmt->execute();
		$stmt2 = $this->db->prepare("SELECT * FROM bds WHERE nom=:nom;");
		$stmt2->bindParam(':nom',$nom);
		$stmt2->execute();
		
		$ligne = $stmt2->fetch();
		return $ligne['id'];
			
		
	}

	public function delete($nom){
		$requete = 'DELETE FROM bds WHERE nom="'.$nom.'";';
		$this->db->query($requete);
	}

	public function update($bd){
		$stmt = $this->db->prepare("UPDATE bds SET auteur=:auteur, annee=:annee, image=:image, description=:description WHERE nom=:nom");
		$nom = $bd->getNom();
		$auteur = $bd->getAuteur();
		$annee = $bd->getAnnee();
		$image = $bd->getImage();
		$description = $bd->getDescription();
		$stmt->bindParam(':nom',$nom);
		$stmt->bindParam(':auteur',$auteur);
		$stmt->bindParam(':annee',$annee);
		$stmt->bindParam(':image',$image);
		$stmt->bindParam(':description',$description);
		$stmt->execute();
	}
	
}
?>
