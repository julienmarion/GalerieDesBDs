<?php
class Account{
	private $pseudo;
	private $mdp;
	private $statut;
	
	public function __construct($pseudo,$mdp,$statut){
		$this->pseudo = $pseudo;
		$this->mdp = $mdp;
		$this->statut = $statut;
	}
		
	public function getPseudo() {
		return $this->pseudo;
	}
		
	public function getMdp() {
		return $this->mdp;
	}
		
	public function getStatut() {
		return $this->statut;
	}
	

	



}


?>