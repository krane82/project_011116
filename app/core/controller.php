<?php 
class Controller {
	
	public $model;
	public $view;
	
	function __construct()
	{
		$this->view = new View();
	}
	
	function action_index()
	{

	}
	public function db()
	{
			$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($con->connect_errno) {
				printf("Connect failed: %s\n", $con->connect_error);
				exit();
			}
			return $con;
	}
}
?>
