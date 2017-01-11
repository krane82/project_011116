<?php

class Controller_Client_Reports extends Controller
{
  function action_index() {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ($_SESSION['user'] == md5('user')) {
      $this->view->generate('client_reports_view.php', 'client_template_view.php', $data);
    }
    else
    {
      session_destroy();
      $this->view->generate('danied_view.php', 'client_template_view.php', $data);
    }
  }

  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

}
