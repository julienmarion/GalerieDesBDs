<?php 
interface BdStorage{

	public function read($id);
	public function readAll();
	public function exists($id);
	public function create(Bd $b);
	
}
?>
