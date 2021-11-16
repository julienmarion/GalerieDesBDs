<?php


class View {
  protected $style;
  protected $title;
  protected $content;
  
  protected $routeur;
  protected $menu;
  
  function __construct ($routeur,$feedback){
	  $this->routeur = $routeur;
	  $this->feedback= $feedback;
	  $this->menu = $this->getMenu();
  }
  
  public function getMenu() {
	  return '<ul id="navigation">
  <li><a href="'.$this->routeur->getAccueil().'" title="accueil">Accueil</a></li>
  <li><a href="'. $this->routeur->getBdURList().'" title="liste BD">Liste des BD</a></li>
  <li><a href="'.$this->routeur->getAPropos().'" title="A propos">A propos</a></li>
  <li class="connexion"><a href="'.$this->routeur->getLoginURL().'" title="Connexion">Se connecter</a></li>
  <li class="connexion"><a href="'.$this->routeur->getInscriptionURL().'" title="Inscription">Inscription</a></li>
  </ul>';
  }
  
  

  public function render() {
	  echo '<!DOCTYPE html>
	<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>BD Land</title>
        <link rel="stylesheet" href="style/main_style.css" />';
      echo $this->style;
      echo '        
    </head>
    <body>';
	  echo $this->menu;
	  echo '<main>';
	  if ($this->feedback !== '') { 
	  echo'<div class="feedback">'.$this->feedback.'</div>';
		}	
	  echo "<h1>".$this->title."</h1>";
	  echo $this->content;
	  echo '</main>';
	  echo "
			</body>
			</html>";
  }
  
  public function makeTestPage() {
	  $this->title = "Le titre";
	  $this->content = "Le contenu";
	  
  }
  
  public function makeBdPage($bd) {
	  $this->style = '<link rel="stylesheet" href="style/style_bd.css" />';
	  $this->title = "La BD ".$bd->getNom();
	  $this->content = "<p>".$bd->getNom()." est une BD créée par ".$bd->getAuteur()." en ".$bd->getAnnee().".</p>";
	  $this->content.= "<p class='descri'>".$bd->getDescription()."</p>";
	  if ($bd->getImage()!="") {
		$this->content.= "<img src='".$bd->getImage()."' alt='image' />";
	  }
	  // $this->content.= "<button type='button' onclick='".$this->routeur->POSTredirect($this->routeur->getBdURList())."'>Supprimer</button>";
	  $this->content.= '<form action="'.$this->routeur->getBdSuppressionURL().'" method="post" >
	  	<button class="envoi" name="supprimer" value="'.$bd->getNom().'">Supprimer</button>
	  </form>';
	  $this->content.= '<form action="'.$this->routeur->getBdModificationURL().'" method="post" >
	  	<button class="envoi" name="modifier" value="'.$bd->getNom().'">Modifier</button>
	  </form>';
  }
  
  public function makeUnknownBdPage() {
	  $this->title = "Cette BD n'existe pas encore !";
	  
  }
  
  public function makePageAccueil() {
	  $this->style = '<link rel="stylesheet" href="style/style_accueil.css" />';
	  $this->title = "BD Land !";
	  $this->content = "<img id='imaccueil' src='images/multibd02.jpg' alt='image'/>";
	  $this->content.= "<p id='textaccueil'>Le site des Bandes Dessinées !</p>";
	  
  }
  
  public function makeListPage($tabBd) {
	  $this->style = '<link rel="stylesheet" href="style/style_liste.css" />';
	  $this->title = "Liste de toutes les BD";
	  $this->content="<table><tr><th>nom</th><th>auteur</th><th>annee</th></tr>";
	  foreach($tabBd as $key => $bd) {
		$this->content .= "<tr><td><a href='".$this->routeur->getBdURL($key)."'>".$bd->getNom()."</a></td><td class='nolink'>".$bd->getAuteur()."</td><td class='nolink'>".$bd->getAnnee()."</td></tr>";
		}
		$this->content.="</table>";
	  
  }
  
  public function makeDebugPage($variable) {
	$this->title = 'Debug';
	$this->content = '<pre>'.var_export($variable, true).'</pre>';
	
  }

	public function makeBdCreationPage(BdBuilder $bdb){
		$this->style = '<link rel="stylesheet" href="style/style_ajoutbd.css" />';
		$this->title= "Ajoutez une nouvelle BD";
		$this->content = "<form enctype='multipart/form-data' action='".$this->routeur->getBdSaveURL()."' method='post' >
			<div class='champ'><p>Nom de la BD : </p><input type='text' name='nom' value='".$bdb->getData()['nom']."'/></div>
			<div class='champ'><p>Auteur de la BD : </p><input type='text' name='auteur' value='".$bdb->getData()['auteur']."'/></div>
			<div class='champ'><p>Année de création : </p><input type='number' name='annee' value='".$bdb->getData()['annee']."'/></div>
			<div class='champ'><p>Ajout d'une image (optionnel) : </p><input type='file' name='image'></div>
			<div class='champ'><p>Description (optionnel) : </p><input type='text' name='description' value='".$bdb->getData()['description']."'/></div>
			<input class='envoi' type='submit' value='Créer'/>
		</form>";
		if ($bdb->getError()!="") {
		$this->content.="<h4>".$bdb->getError()."</h4>";
		}
		
		
	}
	
	public function displayBdCreationSuccess($id){
		$this->routeur->POSTredirect($this->routeur->getBdURL($id),"Creation d'une BD reussie !");
	}

	public function displayBdCreationFailure(){
		$this->routeur->POSTredirect($this->routeur->getBdCreationURL(),"La creation de la BD n'a pas reussi ! Merci de bien vouloir recommencer :)");
	}

	public function displayBdDejaExistante(){
		$this->routeur->POSTredirect($this->routeur->getBdCreationURL(),"La BD existait déjà ! Merci de bien vouloir recommencer :)");
	}
	
	public function displayDeleteSuccess(){
		$this->routeur->POSTredirect($this->routeur->getBdURList(),"Suppression reussie");
	}
	
	public function displayDeleteFailure($id){
		$this->routeur->POSTredirect($this->routeur->getBdURL($id),"Vous n'êtes pas autorisé à supprimer cette BD");
	}

	public function displayDeleteFailure2($id){
		$this->routeur->POSTredirect($this->routeur->getBdURL($id),"Echec de la suppression");
	}

	public function displayModificationFailure(){
		$this->routeur->POSTredirect($this->routeur->getAccueil(),"<p class='echec'>Echec de la modification</p>");
	}

	public function displayModificationFailure2(){
		$this->routeur->POSTredirect($this->routeur->getAccueil(),"Vous n'êtes pas autorisé à modifier cette BD");
	}

	public function displayModificationSuccess($id){
		$this->routeur->POSTredirect($this->routeur->getBdURL($id),"Modification réussie");
	}
	
	public function displayLoginSuccess(){
		$this->routeur->POSTredirect($this->routeur->getAccueil(),"Authentification reussie");
	}
	
	public function displayLoginFailure(){
		$this->routeur->POSTredirect($this->routeur->getLoginURL(),"Echec d'authentification");
	}

	
	public function displayInscriptionSuccess(){
		$this->routeur->POSTredirect($this->routeur->getAccueil(),"Inscription reussie");
	}
	
	public function displayInscriptionFailure(){
		$this->routeur->POSTredirect($this->routeur->getInscriptionURL(),"Echec d'inscription");
	}

	public function displayPseudoDejaPris(){
		$this->routeur->POSTredirect($this->routeur->getInscriptionURL(),"Pseudo deja pris, choisissez-en un autre");
	}
	
	public function makeLoginFormPage() {
		$this->style = '<link rel="stylesheet" href="style/style_connexion.css" />';
		$this->title = "Connexion";
		$this->content = "<form action='".$this->routeur->getConnexionURL()."' method='post' >
			<div class='champ'><p>Pseudo : <input type='text' name='pseudo'/></div>
			<div class='champ'><p>Mot de passe : <input type='text' name='mdp'/></div>
			<input class='envoi' type='submit' value='Envoyer'/>
		</form>
		<p id='inscri'>Pas encore inscrit ? <a href='".$this->routeur->getInscriptionURL()."'>Inscrivez-vous !</a></p>";
		
		
	}

	public function makeInscriptionPage() {
		$this->style = '<link rel="stylesheet" href="style/style_inscription.css" />';
		$this->title = "Inscription";
		$this->content = "<form action='".$this->routeur->getInscritURL()."' method='post' >
			<div class='champ'><p>Pseudo : </p><input type='text' name='pseudo'/></div>
			<div class='champ'><p>Mot de passe : </p><input type='text' name='mdp'/></div>
			<input class='envoi' type='submit' value='Inscription'/>
		</form>";
		
	}

	public function makeModificationPage(Bd $bd) {
		$this->style = '<link rel="stylesheet" href="style/style_ajoutbd.css" />';
		$this->title = "Modifiez une BD";
		$this->content = "<form enctype='multipart/form-data' action='".$this->routeur->getSauverBdModificationURL()."' method='post' >
			<div class='champ'><p>Nom de la BD : </p><input type='text' name='nom' value='".$bd->getNom()."' readonly/></div>
			<div class='champ'><p>Auteur de la BD : </p><input type='text' name='auteur' value='".$bd->getAuteur()."'/></div>
			<div class='champ'><p>Année de création : </p><input type='number' name='annee' value='".$bd->getAnnee()."'/></div>
			<div class='champ'><p>Image de la BD : </p><input type='text' name='image' value='".$bd->getImage()."' readonly/>
			<input type='file' name='image'></div>
			<div class='champ'><p>Description : </p><input type='text' name='description' value='".$bd->getDescription()."'/></div>
			<input class='envoi' type='submit' value='Valider'/>
		</form>";
	}

	public function makePageApropos(){
		$this->style = '<link rel="stylesheet" href="style/style_apropos.css" />';
		$this->title = "A propos";
		$this->content =
		"<h2>Etudiants :</h2>
		<ul>
			<li>21609103</li>
			<li>21605651</li>
		</ul>";
		$this->content.=
		"<section id='rapport'>
		<h2>Description de notre site BD Land :</h2>
		<br/>
		<p>Sujet choisi : les Bandes Dessinées (BD), chacune ayant un Nom, un Auteur, une Année (de création), et éventuellement une image ainsi qu'une description.</p>
		<br/>
		<p>Possibilités sur le site :</p>
		<ul>
		<li>consulter la page listant toutes les BD, chaque nom de BD est unique</li>

		<li>s'inscrire / s'identifier (pseudo + mot de passe), chaque pseudo est unique</li>

		<li>consulter la page de chaque BD (à condition d'être identifié)</li>

		<li>ajouter de nouvelles BD (à condition d'être identifié)</li>

		<li>modifier ou supprimer les BD qu'on a ajoutées soi-même</li>
		</ul>
		<br/>
		<p>Le site s'adapte à différentes largeurs d'écran pour rester lisible même en réduisant la fenêtre.</p>
		<br/>
		<p>Nos requêtes SQL sont préparées, et la création des BD passe par un BD builder.</p>
		
		</section>";
		$this->content.=
		"<h2>Utilisateurs pré-enregistrés :</h2>
		<ul>
			<li>michou /// 123</li>
			<li>gigidu14 /// abc</li>
		</ul>";
		$this->content.=
		"<h2>Sources utilisées :</h2>
		<ul>
			<li>Image de fond : 2dgalleries.com</li>
			<li>Image d'accueil : argentdubeurre.com</li>
			<li>Image de Tintin : casterman.biz</li>
			<li>Image d'Asterix : amazon.com</li>
			<li>Image de Lucky Luke : cloudfront.net</li>
		</ul>";
	}
  
}
?>
