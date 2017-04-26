<?php

class Controller_Login extends Controller {

	function __construct() {
		$this->model = new Model_Login();
		$this->view = new View();

	}

	function action_index() {

        if(!isset($_POST['rem_sys'])){
            setcookie("hash_sys", "", time() - 100);

        }

        if(isset($_COOKIE['hash_sys'])){
            $res = $this->model->rem_in_sys();
            if($res != 'error'){
            session_start();
                if($res["level"]==="1"){
                    $_SESSION['admin'] = md5('admin');
                    header('Location:/admin/dashboard');
                }else if($res["level"]==="3"){
                    $_SESSION['user'] =  md5('user');
                    header('Location:/client_leads');
                }else if($res["level"]==="4"){
                    $_SESSION['user'] =  md5('manager');
                    header('Location:/manager/');
                }else{
                    $data["login_status"] = "access_denied";
                }
            }
        }
//

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
				}
				else if($login["level"] === "3"){
					$_SESSION['user'] =  md5('user');
					header('Location:/client_leads');
				}
				else if($login["level"] === "4"){
					$_SESSION['user'] =  md5('manager');
					header('Location:/manager/');
				}
				else {
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
