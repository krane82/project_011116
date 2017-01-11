<?php
//include("../libs/DataTables/DataTables.php");

class Controller_approvals extends Controller
{
  function __construct()
  {
    $this->model = new Model_Approvals();
    $this->view = new View();
  }

  function action_index()
  {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
      $data["LeadSources"] = $this->model->getLeadSources();
      $this->view->generate('approvals_view.php', 'template_view.php', $data);
    } else {
      session_destroy();
      $this->view->generate('danied_view.php', 'template_view.php', $data);
      //Route::ErrorPage404();
    }
  }
  function action_accept_lead(){
    $id = $_POST["id"];
    $client_id = $_POST["client_id"];
    if($id){
      $con = $this->db();

      $sql = "UPDATE `leads_rejection` SET approval=0 WHERE lead_id=$id AND client_id=$client_id";
      $con->query($sql);

      $con->close();
    }
  }
  function action_rejectLead(){
    $id = $_POST["id"];
    $client_id = $_POST["client_id"];
    if($id){
      $con = $this->db();

      $sql = "UPDATE `leads_rejection` SET approval=3 WHERE lead_id=$id AND client_id=$client_id";
      $con->query($sql);

      $con->close();
    }
  }
  function action_moreInfo(){
    $id = $_POST["id"];
    $client_id = $_POST["client_id"];
    if($id){
      $con = $this->db();

      $sql = "UPDATE `leads_rejection` SET approval=4 WHERE lead_id=$id AND client_id=$client_id";
      $con->query($sql);

      $con->close();
    }
  }
  function action_GetApprovals(){
    $table = 'leads_rejection';
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
      array( 'db' => '`a`.`lead_id`',          'dt' => 0, 'field' => 'lead_id'  ),
      array( 'db' => '`c`.`campaign_name`',          'dt' => 1, 'field' => 'campaign_name'  ),
//      array('db'=>'`a`.`date`', 'dt' => 2, 'formatter' => function( $d, $row ) {
//        return date('m/d/Y', $d);
//      }, 'field'=>'date'),
//      array( 'db' => '`c`.`email`',        'dt' => 2, 'field'=> 'email' ),
      array('db'=>'`ld`.`timedate`',       'dt' => 2, 'formatter' => function( $d, $row ) {
        return date('m/d/Y', $d);
      }, 'field'=>'timedate'),


      array('db'=>'`a`.`date`', 'dt' => 3, 'formatter' => function( $d ) {
        return date('m/d/Y', $d);
      }, 'field'=>'date'),
      array( 'db' => '`a`.`reason`',        'dt' => 4, 'field' => 'reason'),

//      array('db'=>'`a`.`id`', 'dt'=>4, 'formatter'=>function($d, $row){
//        return "";
//      }),
      array( 'db' => '`a`.`approval`',        'dt' => 5, 'formatter'=>function($d){
        switch ($d) {
          case 0:
            return "<span class=\"bg-primary pdfive\">Reject accepted</span>";
            break;
          case 1:
            return "<span class=\"bg-success pdfive\">Approved</span>";
            break;
          case 2:
            return "<span class=\"bg-warning\">Requested to Reject</span>";
            break;
          case 3:
            return "<span class=\"bg-danger pdfive\">Reject not Approved</span>";
            break;
          case 4:
            return "<span class=\"bg-info pdfive\">Requested More info</span>";
          case 5:
            return "<span class='hidden'>5</span>";
          default:
            return "";
        }
      },
        'field' => 'approval'),
      array('db'=> '`a`.`id`', 'dt'=> 6, 'formatter'=>function($d, $row){
        return "<a href='#' class='viewLeadInfo btn btn-info' attr-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\">View</a>";
      }, 'field'=>'id'),
      array('db'=>'`a`.`client_id`', 'dt'=>7, 'formatter'=>function($d, $row){
        return '<a href="#" role="button" onclick="rejectLead('.$row[0]. ', '. $d .');" class="btn btn-small btn-danger hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Disapprove Request </a><br>
						    <a href="#" role="button" onclick="acceptLead('.$row[0]. ', '. $d .');" class="btn btn-small btn-success hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Approve Request</a><br>  
						    <a href="#" role="button" onclick="moreInfo('.$row[0]. ', '. $d .');" class="btn btn-small btn-info hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Request More Info</a>';
      }, 'field'=>'client_id'),

    );

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );

    $joinQuery = "FROM `{$table}` AS `a` INNER JOIN `leads_delivery` as `ld` ON (`a`.`lead_id` = `ld`.`lead_id` AND a.client_id=ld.client_id) INNER JOIN clients as c ON a.client_id=c.id";
    $where = "`a`.`approval` != 1 ";
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

  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

}
