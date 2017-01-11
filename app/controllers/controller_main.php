<?php
class Controller_Main extends Controller
{
	function action_index()
	{
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      // MIGRATION

//      $con = new mysqli(DB_HOST, DB_USER, DB_PASS, 'Lead_points');
//      if ($con->connect_errno) {
//        printf("Connect failed: %s\n", $con->connect_error);
//        exit();
//      }
//      $result = array();
//      $sql  = 'SELECT DISTINCT u.`user_id`, c.`company`, u.`email`, c.first_name, c.last_name, c.lead_cost, c.phone, c.city, c.state, c.country, c.status  FROM `clients` as c';
//      $sql .= ' LEFT JOIN `users` as u ON u.user_id=c.user_id ';
//      $sql .= ' LEFT JOIN `leadcaps` as lc ON lc.client_id = c.client_id';
//      $sql .= ' WHERE c.status=1';
//      $res = $con->query($sql);
//      while($row = $res->fetch_assoc()){
//        $result[] = $row;
//      }
//      $db = $this->db();
//
//      $previd = '';
//      $sql = 'INSERT INTO `clients` (`id`, `campaign_name`, `email`, `full_name`, `lead_cost`, `phone`, `city`, `state`, `country`, `status`)
//VALUES';
//      foreach ($result as $row){
//        if(!($row["user_id"] == $previd)) {
//          $sql .= " (". $row["user_id"] .",'". $row["company"] . "','" . $row["email"]. "','" . $row["first_name"] . " " . $row["last_name"] . "'," . (int)$row["lead_cost"] .
//           ",'" . $row["phone"] . "','" .$row["city"] . "','" . $row["state"] . "','" . $row["country"] . "'," . (int)$row["status"]. "),";
//        }
//        $previd = $row["user_id"];
//
//      }
//      $sql = rtrim($sql, ',');

//      $what = $db->query($sql);

//      $data["result"] = $result;
      header('Location:/admin/dashboard');
//      $this->view->generate('main_view.php', 'template_view.php', $data);
    } else if ($_SESSION['user'] == md5('user')) {
      $this->view->generate('main_view.php', 'client_template_view.php', $data);
    }
    else
    {
      session_destroy();
      header('Location:/login');
      $this->view->generate('main_view.php', 'template_view.php', $data);
    }
//		$this->view->generate('main_view.php', 'template_view.php');
	}


}
