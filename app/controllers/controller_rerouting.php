<?php
class Controller_rerouting extends Controller
{
   function __construct()
  {
    $this->model = new Model_Rerouting();
    $this->view = new View();
  }

  function action_index()
  {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
      // $data["getleads"] = $this->model->getleads();
      $data["clients"] = $this->model->getAllClients();
      $this->view->generate('rerouting_view.php', 'template_view.php', $data);
    } else {
      session_destroy();
      $this->view->generate('danied_view.php', 'template_view.php', $data);
      //Route::ErrorPage404();
    }
  }

  function action_leadall()
  {
    $res = $this->model->leadall();
    echo $res;
    exit();
  }

  


  

}
