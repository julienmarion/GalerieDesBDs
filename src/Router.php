<?php
require_once "view/View.php";
require_once "control/Controller.php";
require_once "model/Bd.php";
require_once "model/BdBuilder.php";
require_once "view/PrivateView.php";

class Router {
	
	
	public function main($BdStorage,$AccountStorage) {
		session_start();
		
		$feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';
		
		
		
		// Vérifie que le visiteur est identifié pour choisir la vue à créer
		if (isset($_SESSION['user'])){
			$p = new Controller(new PrivateView($this,$feedback,$_SESSION['user']),$BdStorage,$AccountStorage);
		} else {
			$p = new Controller(new View($this,$feedback),$BdStorage,$AccountStorage);
		}
		
		
		// Affiche la page d'un objet si le visiteur est identifié
		if (key_exists("id",$_GET)) {
			if (key_exists('user',$_SESSION)) {
				$p->showInformation($_GET["id"]);
			} else {
				$p->makeLoginFormPage();
			}
		}
		
		
		// Affiche la page de création d'un nouvel objet si le visiteur est identifié
		//(sinon on l'envoie à la page d'identification)
		else if (key_exists("action",$_GET)){
			if (key_exists('user',$_SESSION)) {
				if ($_GET["action"]==="nouveau"){
					$p->newBd();
				} else if ($_GET["action"]==="sauverNouveau") {
					
					$p->saveNewBd($_POST);
				} else if ($_GET["action"]==="supprimer") {

					$p->deleteBd($_POST);
				} else if ($_GET["action"]==="modifier") {

					$p->newModifBd($_POST);
				} else if ($_GET["action"]==="sauverModification") {

					$p->saveModifBd($_POST);
				}			
			} else {
				$p->makeLoginFormPage();
			}
		}
		
		
		// Affiche la page d'identification si le visiteur n'est pas identifié,
		// ou la page de déconnexion s'il est déjà identifié
		else if (key_exists("login",$_GET)){
			// accède à la page d'identification
			if ($_GET["login"]==="identification"){
				$p->makeLoginFormPage();
			}
			// l'utilisateur a cliqué sur le bouton de connexion
			else if ($_GET["login"]==="connexion"){
				$p->connexion($_POST);
			}
			// l'utilisateur identifié a cliqué sur le bouton de déconnexion
			else if ($_GET["login"]==="deconnexion"){
				unset($_SESSION['user']);
				$p = new Controller(new View($this,$feedback),$BdStorage,$AccountStorage);
				$p->makePageAccueil();
			}
			// l'utilisateur souhaite s'inscrire
			else if	($_GET["login"]==="inscription"){
				$p->makeInscriptionPage();
			}
			else if ($_GET["login"]==="inscrit"){
				$p->inscription($_POST);
			}
		}

		else if (key_exists("apropos",$_GET)){
			$p->makePageApropos();
		}
		
		
		// Affiche la page de la liste de tous les objets
		else {			
			if (key_exists("liste",$_GET)) {				
				$p->showList();
			}
			else {
				$p->makePageAccueil();
			}			
		}
		$p->view->render();
				
		
	}










	public function getAccueil(){
		return $_SERVER['PHP_SELF'];
	}
	
	public function getBdURList(){
		return $_SERVER['PHP_SELF']."?liste";
	}


	public function getBdURL($id) {
		return $_SERVER['PHP_SELF']."?id=".$id;
	}
	
	
	public function getBdCreationURL(){
		return $_SERVER['PHP_SELF']."?action=nouveau";
	}
	
	public function getBdSaveURL(){
		return $_SERVER['SCRIPT_NAME']."?action=sauverNouveau";
		
	}

	public function getBdSuppressionURL(){
		return $_SERVER['PHP_SELF']."?action=supprimer";
	}

	public function getBdModificationURL(){
		return $_SERVER['PHP_SELF']."?action=modifier";
	}

	public function getSauverBdModificationURL(){
		return $_SERVER['PHP_SELF']."?action=sauverModification";
	}
	
	public function getLoginURL(){
		return $_SERVER['PHP_SELF']."?login=identification";
	}
	
	public function getDeconnexionURL(){
		return $_SERVER['PHP_SELF']."?login=deconnexion";
	}

	public function getConnexionURL(){
		return $_SERVER['PHP_SELF']."?login=connexion";
	}

	public function getInscriptionURL(){
		return $_SERVER['PHP_SELF']."?login=inscription";
	}

	public function getInscritURL(){
		return $_SERVER['PHP_SELF']."?login=inscrit";
	}

	public function POSTredirect($url,$feedback){
		$_SESSION['feedback']=$feedback;
		header("Location: ".$url,true,303);
	}

	public function getAPropos(){
		return $_SERVER['PHP_SELF']."?apropos";
	}


}
