<?php
class Controller_leads extends Controller
{
  function __construct()
  {
    require_once('app/models/model_api.php');
    $api = new Model_Api();
    $this->model = new Model_Leads($api);
    // $this->api = new Model_Api();
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
      $end = strtotime($_POST["end"])+ 86400;
      $state = $_POST["state"];
      $source=$_POST["source"];
      $client = $_POST["client"];
      $data=$this->model->getList($start, $end, $state, $source);
      $i=1;
      foreach($data as $item)
      {
        if($x=$this->model->senLead($client,$item)) print $x;
        $i++;
        usleep(250000);
      }
      print $i.' Leads done';
      // need to get leads from #start to #end
      // end automaticaly send each of this with $this->sendleads(0, $lead_id)
      //echo "Sending to $_POST[client] with " . date("Y-m-t", $start) . " - " .  date("Y-m-t", $end);
    } else {
      // Sending one lead to one client
      $client_id =$_POST["id"];
      $lead_id = $_POST["lead_id"];
      echo $this->model->senLead($client_id, $lead_id);
    }
  }
  

  function action_distribution()
  {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
      $this->view->generate('leads_delivery.php', 'template_view.php', $data);
    } else {
      session_destroy();
      $this->view->generate('danied_view.php', 'template_view.php', $data);
    }
  }

  function action_ajax_delivery()
  {
    $table = 'leads_delivery';
    $primaryKey = 'id';
    $columns = array(
      array( 'db' => '`ld`.`id`',          'dt' => 0, 'field' => 'id'  ),
      array( 'db' => '`ld`.`lead_id`',        'dt' => 1, 'field' => 'lead_id'),
      array( 'db' => '`ll`.`postcode`',        'dt' => 2, 'field'=> 'postcode' ),
      array('db'=>'`ld`.`timedate`', 'dt' => 3, 'formatter' => function( $d, $row ) {
        return date('d/m/Y', $d);
      }, 'field'=>'timedate'),
      array('db'=> '`c`.`campaign_name`', 'dt'=>4, 'field'=>'campaign_name'),
      array('db'=> '`ld`.`open_email`', 'dt'=>5, 'formatter'=>function($d, $row){
        if($d) {
          return $d;
        } else {
          return "not opened";
        }
      }, 'field'=>'open_email'),
      array('db'=> '`ld`.`open_time`', 'dt'=>6, 'formatter'=>function($d, $row){
        if($d) {
          return date('Y-m-d H:i:s', $d);
        } else {
          return "not opened";
        }
      }, 'field'=>'open_time')
    );

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );

    $joinQuery = "FROM `{$table}` AS `ld` INNER JOIN `clients` AS `c`  ON `c`.`id`=`ld`.`client_id` LEFT JOIN `leads_lead_fields_rel` as `ll` on `ll`.`id`=`ld`.`lead_id`  group by `ld`.`id`";
//    $where = ' (`l`.`datetime` BETWEEN '.$start.' AND '.$end.')';
  //  if($source) $where .= " AND `l`.`campaign_id`=".$campaign_id;
 //   if($state) $where .= " AND `lf`.`state`='$state'";


    echo json_encode(
      SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery )
    );

  }

  function action_getLeads()
  {
    session_start();
    $source = $_POST["source"];
    $start = strtotime($_POST["st"]);
    $end = strtotime($_POST["en"]) + 86400;
    $state = $_POST["state"];
    $campaign_id = getCampaignID($source);
    $table = 'leads';
    // print_r($_POST); exit();


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
    $where = ' (`l`.`datetime` BETWEEN '.$start.' AND '.$end.')';
    if($source) $where .= " AND `l`.`campaign_id`=".$campaign_id;
    if($state) $where .= " AND `lf`.`state`='$state'";


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
