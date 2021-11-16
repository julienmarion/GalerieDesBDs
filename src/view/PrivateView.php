<?php
class PrivateView extends View {
  protected $title;
  protected $content;
  protected $routeur;
  protected $menu;
  protected $account;
  protected $style;

  function __construct ($routeur,$feedback,$account){
	  $this->routeur = $routeur;
	  $this->feedback= $feedback;
	  $this->account = $account;
	  $this->menu = $this->getMenu();
  }

 
  public function getMenu() {
	  return '<ul id="navigation">
  <li><a href="'.$this->routeur->getAccueil().'" title="accueil">Accueil</a></li>
  <li><a href="'. $this->routeur->getBdURList().'" title="liste BD">Liste des BD</a></li>
  <li><a href="'.$this->routeur->getBdCreationURL().'" title="nouvelle BD">Ajouter une BD</a></li>
  <li><a href="'.$this->routeur->getAPropos().'" title="A propos">A propos</a></li>
  <li class="connexion"><a href="'.$this->routeur->getDeconnexionURL().'" title="deconnexion">Deconnexion</a></li>
</ul>';
  }

	public function makePageAccueil() {
	  $this->style = '<link rel="stylesheet" href="style/style_accueil.css" />';
	  $this->title = "BD Land !";
	  $this->content = "<img id='imaccueil' src='images/multibd02.jpg' alt='image'/>";
	  $this->content.= "<p id='textaccueil'>Le site des bandes dessinées !"." Au fait, bienvenue ".$this->account->getPseudo()." !</p>";

  }

  
	
	
}

?>
