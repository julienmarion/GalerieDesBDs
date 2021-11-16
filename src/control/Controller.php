<?php

require_once "model/BdBuilder.php";
require_once "login/AccountStorageMySQL.php";

class Controller {
	
  public $view;
  protected $DB;
  protected $currentBdBuilder;

  public function __construct($view,BdStorage $DB,AccountStorage $AS) {
    $this->view = $view;
    $this->DB=$DB;
    $this->currentBdBuilder=key_exists('currentNewBd',$_SESSION)? $_SESSION['currentNewBd']: null;
    $this->AS=$AS;
  }

  public function __destruct() {
		$_SESSION['currentBdBuilder'] = $this->currentBdBuilder;
	
	}
  
  public function showInformation($id) {
	if ($this->DB->exists($id)){
		$this->view->makeBdPage($this->DB->read($id));
	}
	else {
		$this->view->makeUnknownBdPage();
	}
  }
  
  public function makePageAccueil() {
	  $this->view->makePageAccueil();
  }
  
  public function showList() {
	  $this->view->makeListPage($this->DB->readAll());
  }
  
  public function makeDebugPage($var){
	  $this->view->makeDebugPage($var);
	}
	
	public function makeLoginFormPage(){
		
		$this->view->makeLoginFormPage();
	}
	
	public function makeInscriptionPage(){
		
		$this->view->makeInscriptionPage();
	}
	
	public function connexion(array $tab){
		if (isset($tab['pseudo']) && isset($tab['mdp'])) {
			
			$log = $this->AS->checkAuth($tab['pseudo'],$tab['mdp']);	
			if ($log===null) {
				$this->view->displayLoginFailure();
			} else {
				$_SESSION['user']=$log;
				$this->view->displayLoginSuccess();
			}
		} else {
			$this->view->displayLoginFailure();
		}
	}

	public function inscription(array $tab){
		if (isset($tab['pseudo']) && isset($tab['mdp'])) {
			if ($this->AS->exists($tab['pseudo'])){
				$this->view->displayPseudoDejaPris();
			} else {
				$a = new Account($tab['pseudo'],$tab['mdp'],"membre");
				$this->AS->create($a);
				$this->view->displayInscriptionSuccess();
			}
		} else {
			$this->view->displayInscriptionFailure();
		}
	}

	public function saveNewBd(array $data){
		
		$this->currentBdBuilder = new BdBuilder($data);
		if ($this->currentBdBuilder->isValid()){
			if ($this->DB->bdDejaCreee($data["nom"])) {
				$this->view->displayBdDejaExistante();
			} else {
				$this->currentBdBuilder->createBd();
				$id=$this->DB->create($this->currentBdBuilder->getBd());
				$this->currentBdBuilder=null;
				$this->view->displayBdCreationSuccess($id);
			}
		} else {
			$this->view->displayBdCreationFailure();
		}
	}

	public function deleteBd(array $data){
		$id = $this->DB->getId($data['supprimer']);
		if ($this->DB->getBdCreatorId($data['supprimer'])===$this->DB->getIdUser($_SESSION['user'])) {
			if (isset($data['supprimer'])) {
				$nom = $data['supprimer'];
				$this->DB->delete($nom);

				if ($this->DB->exists($id)) {
					$this->view->displayDeleteFailure2($id);
				} else {
					$this->view->displayDeleteSuccess();
				}
			}
		} else {
			$id = $this->DB->getId($data['supprimer']);
			$this->view->displayDeleteFailure($id);
		}
		
	}

	public function newModifBd(array $data){
		if ($this->DB->getBdCreatorId($data['modifier'])===$this->DB->getIdUser($_SESSION['user'])) {
			if (isset($data['modifier'])) {
				$id = $this->DB->getId($data['modifier']);
				$bd = $this->DB->read($id);
				$this->view->makeModificationPage($bd);
			} else {
				$this->view->displayModificationFailure();
			}
		} else {
			$this->view->displayModificationFailure2();
		}
	}

	public function saveModifBd(array $data){
		if (isset($data['nom'])) {

			$id = $this->DB->getId($data["nom"]);
			
			if ($_FILES['image']['tmp_name']!="") {
				$this->moveFile();
				$bd2 = new Bd($data["nom"],$data["auteur"],$data["annee"],'upload/'.$_FILES['image']['name'],$data["description"]);
				$this->DB->update($bd2);
				unset($_FILES['image']['tmp_name']);
			} else {
				$bd2 = new Bd($data["nom"],$data["auteur"],$data["annee"],$data["image"],$data["description"]);
				$this->DB->update($bd2);
			}
			
			
			$this->view->displayModificationSuccess($id);
		} else {
			$this->view->displayModificationFailure();
		}
	}

	public function newBd(){
		if($this->currentBdBuilder===null){
			$this->currentBdBuilder=new BdBuilder(array("nom" => "", "auteur" => "", "annee" => "", "image" => "", "description" => ""));
		}
		$this->makeBdCreationPage($this->currentBdBuilder);
	}
	

	public function makeBdCreationPage(BdBuilder $bdb){
		$this->view->makeBdCreationPage($bdb);
	}

	public function makePageApropos(){
		$this->view->makePageApropos();
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
