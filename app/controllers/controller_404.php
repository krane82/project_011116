<?php
class Controller_404 extends Controller
{
	
	function action_index()
	{
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      $this->view->generate('404_view.php', 'template_view.php', $data);
    } else if ($_SESSION['user'] == md5('user')) {
      $data["user_id"] = $_SESSION["id"];
      header('Location:/client/dashboards');
      $this->view->generate('404_view.php', 'client_template_view.php', $data);
    }
    else
    {
      session_destroy();
      header('Location:/login');
      $this->view->generate('404_view.php', 'template_view.php', $data);
    }
//		$this->view->generate('404_view.php', 'template_view.php');
	}

}
