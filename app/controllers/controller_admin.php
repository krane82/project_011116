<?php

class Controller_Admin extends Controller
{
  function __construct()
  {
    $this->model = new Model_Admin();
    $this->view = new View();
//      var_dump($_COOKIE);
  }
  function action_index() {

    $data["body_class"] = "page-header-fixed";
    $data["title"] = "Dashboard";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      header('Location:/admin/dashboard');
      $this->view->generate('admin_view.php', 'template_view.php', $data);
    } else if ($_SESSION['user'] == md5('user')) {
      $data["user_id"] = $_SESSION["id"];

      header('Location:/client/dashboards');
      $this->view->generate('main_view.php', 'client_template_view.php', $data);
    } else {
      session_destroy();
      header('Location:/login');
      $this->view->generate('danied_view.php', 'template_view.php', $data);
    }
  }

  function action_dashboard()
  {
//     var_dump($_COOKIE);
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
      $data["body_class"] = "page-header-fixed";
      $data["title"] = "Dashboard";
      $data["order"] = $this->model->DistributionOrder();
      $data["MonthlyStats"] = $this->model->getMonthlyStats();
      $data['pendingPercent']=$this->model->pendingPercent();
      $this->view->generate('dashboard_view.php', 'template_view.php', $data);
    } else {
      session_destroy();
      $this->view->generate('danied_view.php', 'template_view.php', '');
    }
  }

  function action_changemails() {
    $con = $this->db();
    $sql = "SELECT id from `clients`";
    if($res = $con->query($sql)){
      foreach ($res as $r){
        $result[] = $r;
      }
    }
    foreach ($result as $r) {
      $id = $r["id"];
      $sql = "UPDATE `clients` SET `email` = 'sergiy.tonkoshkuryk@jointoit.com' WHERE `clients`.`id` =".$id;
      $res = $con->query($sql);
      if($res) echo "clients.true";
    }
  }
  function action_test()
  {
//    $con = $this->db();
//    $sql = "SELECT * FROM `leads` LEFT JOIN `leads_lead_fields_rel` ON leads.id=leads_lead_fields_rel.id WHERE `leads`.`datetime` BETWEEN 1480550400 AND 1483142400";
//    if($res = $con->query($sql)){
//      foreach ($res as $r){
//        $result[] = $r;
//      }
//      $count = 0;
//      foreach ($result as $t){
//        if($count < 15){
//          $this->sendTest($t);
//          var_dump($t);
//          $count++;
//        }
//      }
//    }
  }
  private function sendTest($p){
    $ch = curl_init();
    $formpost = http_build_query($p);
    curl_setopt($ch, CURLOPT_URL,"http:/leadpoint.dev/api/in");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
      $formpost);

    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    var_dump($server_output);
    curl_close ($ch);
  }

  public function action_postcodes(){

  }


  function action_logout()
  {
    setcookie("hash_sys", "", time() - 100);
    session_start();
    session_destroy();
    header('Location:/login');
  }

}
