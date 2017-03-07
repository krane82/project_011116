<?php

class Controller_client_leads extends Controller
{
  function __construct()
  {
    $this->model = new Model_Client_Leads();
    $this->view = new View();
  }

  function action_index()
  {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['user'] ==  md5('user')) {
      $this->view->generate('client_leads_view.php', 'client_template_view.php', $data);
    } else {
      // session_destroy();
      $this->view->generate('danied_view.php', 'client_template_view.php', $data);
      //Route::ErrorPage404();
    }
  }
  function action_reject_Lead(){
    $lead_id = $_POST["lead_id"];
    $client_id = $_SESSION["user_id"];
    $reason = $_POST["reject_reason"];
    $con = $this->db();
    $now = time();
    $sql = "UPDATE `leads_rejection` SET approval='2', reason='$reason', date='$now' WHERE client_id=$client_id AND lead_id=$lead_id";
    if($con->query($sql)){
      echo "Success";
    } else {
      echo $sql;
      return;
    }
  }
  function action_downloadleads()
  {
    session_start();
    if($client = $_SESSION["user_id"])
    {
      $now = time();
      $filename =  'Leads_From_' . date("Y_m_d", $now);
      $data = $this->model->getLeadsForClient($client);
      header('Content-type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=' . $filename .'.csv');
      $fp = fopen('php://output', 'w');
      foreach( $data as $line ) {
        fputcsv( $fp, $line );
      }
      fclose($fp);
    }
  }

  function action_getLeads(){
    session_start();
    $user_id = $_SESSION["user_id"];
    $table = 'leads_rejection';
    $primaryKey = 'id';
    $columns = array(
      array( 'db' => '`a`.`lead_id`', 'dt' => 0, 'field' => 'lead_id'  ),
      array( 'db' => '`llf`.`full_name`', 'dt' => 1, 'field' => 'full_name'  ),
      array('db'=>'`a`.`date`',       'dt' => 2, 'formatter' => function( $d, $row ) {
        return date('m/d/Y', $d);
      }, 'field'=>'date'),
      array( 'db' => '`a`.`approval`',  'dt' => 3, 'formatter'=>function($d){
        switch ($d) {
          case 0:
            return "<span class=\"bg-primary pdfive\">Reject Approved</span>";
            break;
          case 1:
            return "<span class=\"bg-success pdfive\">Approved</span>";
            break;
          case 2:
            return "<span class=\"bg-warning pdfive\">Reject requested</span>";
            break;
          case 3:
            return "<span class=\"bg-danger pdfive\">Reject not Approved</span>";
            break;
          case 4:
            return "<span class=\"bg-info pdfive\">Requested More info</span>";
            break;
          default:
            return "";
        }
      },'field'=> 'approval' ),
      array( 'db' => '`a`.`reason`',  'dt' => 4, 
        'field' => 'reason'),
      array('db'=> '`a`.`id`', 'dt'=>5, 'formatter'=>function($d, $row){
        if($row[3]>0) {
          return "<a href='#' class='btn leadreject btn-warning' attr-lead-id='$row[0]' attr-client='$row[1]' data-gb='$row[3]'> Reject</a>";
        } else {
         return "<button type=\"button\" class=\"btn btn-warning\" disabled=\"disabled\">Reject </button>";
        }
      }, 'field'=>'id'),
      array('db'=>'`a`.`id`', 'dt'=>6, 'formatter'=>function($d, $row){
        return "<a href='#' class='viewLeadInfo btn btn-primary' attr-lead-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\"  >View</a>";
      })
    );

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );

    $joinQuery = "FROM `{$table}` AS `a` LEFT JOIN `clients` AS `c` ON (`a`.`client_id` = `c`.`id`) LEFT JOIN `leads_lead_fields_rel` as `llf` ON ( `a`.`lead_id` = `llf`.`id` ) ";
    $joinQuery .= " LEFT JOIN `leads` as `l` ON `l`.`id`=`a`.`lead_id` ";
    $where = "`a`.`client_id` = $user_id";

    echo json_encode(
      SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where )
    );
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

  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

}
