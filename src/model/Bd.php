<?php
class Bd {
  private $nom;
  private $auteur;
  private $annee;
  private $image;
  private $description;

  public function __construct($nom,$auteur,$annee,$image,$description) {
    $this->nom = $nom;
    $this->auteur = $auteur;
    $this->annee = $annee;
    $this->image = $image;
    $this->description = $description;
  }
  
  public function getNom() {
	  return $this->nom;
  }
  
  public function getAuteur() {
	  return $this->auteur;
  }
  
  public function getAnnee() {
	  return $this->annee;
  }
  
  public function getImage() {
    return $this->image;
  }

  public function getDescription() {
    return $this->description;
  }
  
}
?>
