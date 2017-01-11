<?php
class Controller_leads extends Controller
{
  function __construct()
  {
    $this->model = new Model_Leads();
    $this->api = new Model_Api();
    $this->view = new View();
  }

  function action_index()
  {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
      $data["LeadSources"] = $this->model->getLeadSources();
      $data["clients"] = $this->model->getAllClients();
      $this->view->generate('leads_view.php', 'template_view.php', $data);
    } else {
      session_destroy();
      $this->view->generate('danied_view.php', 'template_view.php', $data);
      //Route::ErrorPage404();
    }
  }
  function action_LeadInfo(){
    if($id = $_POST["id"]){
      $con = $this->db();
      $sql = "SELECT * from `leads_lead_fields_rel` WHERE id=$id";
      if($res = $con->query($sql)){
       $leadinfo = $res->fetch_assoc();
        $prepearedinfo = prepareLeadInfo($leadinfo);
        $content = '<table class="table">';
//        echo "<pre>" . print_r($prepearedinfo) . "</pre>";
        foreach ($prepearedinfo as $v){
          $content .= '<tr><td>'.$v["field_name"].'</td><td>'.$v["val"].'</td></tr>';
        }
        $content .= '</table>';
        echo $content;
      }
    } else {
      echo "lead not found";
    }
  }
  
  function action_sendLead()
  {
    if(isset($_POST["client"])) {
     
      $start = strtotime($_POST["start"]);
      $end = strtotime($_POST["end"]);
       echo "Sending to $_POST[client] with $start $end";
    } else {
      $client_id =(int)$_POST["id"];
      $lead_id = (int)$_POST["lead_id"];
      if($client_id AND $lead_id) {
        $this->model->senLead($client_id, $lead_id);
      }
    }
  }

  function action_getLeads()
  {
    $source = $_POST["source"];
    $start = strtotime($_POST["st"]);
    $end = strtotime($_POST["en"]) + 86400;
    $campaign_id = getCampaignID($source);
    $table = 'leads';
  //    $table = <<<EOT
  //    SELECT  l.id, lf.state AS state, c.name AS campaign_name, l.datetime as date FROM leads l
  //    LEFT JOIN campaigns c
  //    ON l.campaign_id = c.id
  //    LEFT JOIN leads_lead_fields_rel lf
  //    ON lf.id=l.id
  //    WHERE (l.datetime BETWEEN $start AND $end)
  //EOT;
//    if($campign_id){
//      $table = <<<EOT
// (
//    SELECT  l.id, lf.state AS state, c.name AS campaign_name FROM leads l
//    LEFT JOIN campaigns c
//    ON l.campaign_id = c.id
//    LEFT JOIN leads_lead_fields_rel lf
//    ON lf.id=l.id
//    WHERE l.campaign_id = $campign_id
//    AND (l.datetime BETWEEN $start AND $end)
// ) temp
//EOT;
//    }

    $primaryKey = 'id';

    $columns = array(
      array( 'db' => '`l`.`id`',          'dt' => 0, 'field' => 'id'  ),
      array( 'db' => '`c`.`name`',        'dt' => 1, 'field' => 'name'),
      array( 'db' => '`lf`.`state`',        'dt' => 2, 'field'=> 'state' ),
      array('db'=>'`l`.`datetime`', 'dt' => 3, 'formatter' => function( $d, $row ) {
        return date('m/d/Y', $d);
      }, 'field'=>'datetime'),
      array('db'=> '`l`.`id`', 'dt'=>4, 'formatter'=>function($d, $row){
        return "<a href='#' class='viewLeadInfo btn btn-info' attr-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\">View</a>";
      }, 'field'=>'id'),
      array('db'=> '`l`.`id`', 'dt'=>5, 'formatter'=>function($d, $row){
        return "<a href='#' class='sendLead btn btn-info' attr-id='$row[0]' data-toggle=\"modal\" data-target=\"#sendLead\">Send</a>";
      }, 'field'=>'id')
    );

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );

    $joinQuery = "FROM `{$table}` AS `l` LEFT JOIN `campaigns` AS `c` ON (`l`.`campaign_id` = `c`.`id`) LEFT JOIN `leads_lead_fields_rel` AS `lf` ON `lf`.`id`=`l`.`id`";
    $where = ' (`l`.`datetime` BETWEEN '.$start.' AND '.$end.') ';
    if($source) $where .= "AND `l`.`campaign_id`=".$campaign_id;

    echo json_encode(
      SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where )
    );

  }

  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

}
