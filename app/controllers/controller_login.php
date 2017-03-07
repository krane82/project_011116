<?php

class Controller_Login extends Controller {

	function __construct() {
		$this->model = new Model_Login();
		$this->view = new View();
	}
	
	function action_index() {
		session_start();
		if(isset($_POST['login']) && isset($_POST['password']))
		{
			$login = $_POST['login'];
			$password =$_POST['password'];
			$login = $this->model->check_data($login, $password);
			if($login){
				if($login["level"] === "1") {
					$_SESSION['admin'] = md5('admin');
					header('Location:/admin/dashboard');
				} else if($login["level"] === "3"){
					$_SESSION['user'] =  md5('user');
					header('Location:/client/dashboard');
				} else {
					$data["login_status"] = "access_denied";
				}
			} else {
				$data["login_status"] = "access_denied";
			}
		}
		else
		{
			$data["login_status"] = "";
		}
		$data["body_class"] = "page-login";
		$this->view->generate('login_view.php', 'login_template.php', $data);
	}
}
