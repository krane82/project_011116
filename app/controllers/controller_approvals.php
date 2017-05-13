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
    session_start();
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
      array( 'db' => '`a`.`lead_id`',          'dt' => 0, 'formatter'=>function($d, $row)
      {
        if($row[11]==NULL)
        {
          return $d.'<div><button type="button" data-act="conversation" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalka" value="'.$row[9].'">open</button></div>';
        }
        $whoSeen=explode(',',$row[11]);
        if(!in_array($_SESSION['user_id'],$whoSeen))
        {
          $str=$d.'<div><button type="button" data-act="conversation" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalka" value="'.$row[9].'">open</button></div>';
        }
        else {
          $str = $d . '<div><button type="button" data-act="conversation" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalka" value="' . $row[9] . '">open</button></div>';
        }
          return $str;
      },
    'field' => 'lead_id'  ),
      array( 'db' => '`c`.`campaign_name`',          'dt' => 1, 'field' => 'campaign_name'  ),
//      array('db'=>'`a`.`date`', 'dt' => 2, 'formatter' => function( $d, $row ) {
//        return date('m/d/Y', $d);
//      }, 'field'=>'date'),
//      array( 'db' => '`c`.`email`',        'dt' => 2, 'field'=> 'email' ),
      array('db'=>'`ld`.`timedate`',       'dt' => 2, 'formatter' => function( $d, $row ) {
        return date('d/m/Y h:i:s A', $d);
      }, 'field'=>'timedate'),
      array('db'=>'`a`.`date`', 'dt' => 3, 'formatter' => function( $d ) {
        return date('d/m/Y h:i:s A', $d);
      }, 'field'=>'date'),
      array( 'db' => '`a`.`reason`',        'dt' => 4, 'field' => 'reason'),
      array( 'db' => '`a`.`note`',        'dt' => 5, 'field' => 'note'),
      array('db'=>'`a`.`decline_reason`', 'dt'=>6, 'formatter'=>function($d, $row){
        return $d;
      }, 'field'=>'decline_reason'),

//      array('db'=>'`a`.`id`', 'dt'=>4, 'formatter'=>function($d, $row){
//        return "";
//      }),
      array( 'db' => '`a`.`approval`',        'dt' => 7, 'formatter'=>function($d){
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
            return "<span class=\"bg-info pdfive\">More info required</span>";
          case 5:
            return "<span class='hidden'>5</span>";
          default:
            return "";
        }
      },
        'field' => 'approval'),
        array( 'db' => '`a`.`audiofile`',  'dt' => 8,'formatter' => function( $d, $row ) {
          if($d) {
            $str="<tr><td><form method='POST' action='". __HOST__ ."/docs/audios/download.php'>
            <input type='hidden' name='file' value='".basename($d)."'>
            <input type='hidden' name='folder' value='".$row[10]."'>
            <input type='submit' class='btn btn-xs btn-success' value='Download file'></form><br><br>
            <button type='button' class='btn btn-xs btn-danger' onclick='delbutfile(this)' value='".$d."'>Delete file</button></td></tr>";
            return $str;
           // return "<a href='" . __HOST__ . "/docs/audios/" . $row[10] . "/" . basename($d) . "' download class='btn btn-success btn-xs'>audio</a>";
          }
          return '';
        }, 'field' => 'audiofile'),
        array('db'=> '`a`.`id`', 'dt'=> 9, 'formatter'=>function($d, $row){
          return "<a href='#' class='viewLeadInfo btn btn-info' attr-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\">View</a>
";
        }, 'field'=>'id'),

      array('db'=>'`a`.`client_id`', 'dt'=>10, 'formatter'=>function($d, $row){
        return '<a href="#" role="button" onclick="rejectLead('.$row[0]. ', '. $d .');" class="btn btn-small btn-danger hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Disapprove Request </a><br>
						    <a href="#" role="button" onclick="acceptLead('.$row[0]. ', '. $d .');" class="btn btn-small btn-success hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Approve Request</a><br>  
						    <a href="#" role="button" onclick="moreInfo('.$row[0]. ', '. $d .');" class="btn btn-small btn-info hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Request More Info</a>';
      }, 'field'=>'client_id'),
        array('db'=> '`con`.`seen`', 'dt'=> 11, 'field'=>'seen')//,
        //array('db'=> '`con`.`lead_id`', 'dt'=> 12, 'field'=>'lead_id')*/
    )
    ;

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );

    $joinQuery = "FROM `{$table}` AS `a` INNER JOIN `leads_delivery` as `ld` ON (`a`.`lead_id` = `ld`.`lead_id` AND a.client_id=ld.client_id) INNER JOIN clients as c ON a.client_id=c.id LEFT JOIN lead_conversations con ON `a`.`id`=`con`.`lead_id`";
    $where = "`a`.`approval` != 1 ";
    $groupBy="`a`.`id`";
    echo json_encode(
      SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupBy )
    );
  }

  public function action_decline()
  {
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
      if (isset($_REQUEST["decline"]) AND isset($_REQUEST["lead_id"]) AND isset($_REQUEST["client_id"]) )
      {
        //return var_dump($_FILES);
        $con = $this->db();
        $decline = mysqli_real_escape_string($con, $_REQUEST["decline"]);
        $id = $_REQUEST["lead_id"];
        $client_id = $_REQUEST["client_id"];
        $destination=NULL;
        if($_FILES['audiofile']['tmp_name'])
        {
          $filename=$_FILES["audiofile"]['tmp_name'];
          $dir=$_SERVER['DOCUMENT_ROOT'].'/docs/audios/'.$client_id;
          if(!is_dir ($dir))
          {
            mkdir($dir, 0777);
          }
          $destination=$dir.'/'.$_FILES["audiofile"]["name"];
          move_uploaded_file($filename, $destination);
          $httpPath=__HOST__.'/docs/audios/'.$client_id.'/'.$_FILES["audiofile"]["name"];
        }
        //HERE IS ABILITY TO DELETE PREVIOUS FILE IF DOWNLOAD NEW ONE
        $sql1="SELECT audiofile from leads_rejection WHERE lead_id=$id AND client_id=$client_id";
        $res1 = $con->query($sql1);
        $result1 = $res1->fetch_assoc();
        if(is_file($result1['audiofile']))
        {
          unlink($result1['audiofile']);
        }
        //
        $sql = "UPDATE `leads_rejection` SET approval=3, decline_reason='$decline'";
        if($httpPath) $sql.=", audiofile='$destination'";
        $sql.="WHERE lead_id=$id AND client_id=$client_id";
        $res = $con->query($sql);
        $con->close();
        return $this->action_index();
      }
    }
  }
 function action_deleteFile()
 {
   print $this->model->deleteFile();
 }
  function action_LeadInfo(){
    if($id = $_POST["id"]){
      $con = $this->db();
      $sql = "SELECT * FROM `leads_lead_fields_rel` WHERE id=$id";
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
