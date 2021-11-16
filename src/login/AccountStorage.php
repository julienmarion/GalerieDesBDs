<?php
interface AccountStorage{

	public function checkAuth($pseudo, $mdp);
	public function read($idUser);
	public function readAll();
	public function exists($idUser);
	public function create(Account $a);
}

?>
