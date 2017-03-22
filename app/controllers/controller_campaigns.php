<?php
class Controller_Campaigns extends Controller {

  function __construct() {
    $this->model = new Model_Campaigns();
    $this->view = new View();
  }

  function action_index() {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      $data["table"] = $this->model->get_data();
      $this->view->generate('campaigns_view.php', 'template_view.php', $data);
    }
    else
    {
      session_destroy();
      header('Location:/login');
      //    echo "Access Denied! Please <a href='/login'>login</a>";
      $this->view->generate('danied_view.php', 'template_view.php', $data);
      //    Route::ErrorPage404();
    }
  }

  function action_planing() {
    $data=$this->model->getPlans();
    $this->view->generate('planing_view.php', 'template_view.php', $data);
  }

  function action_planing_ajax()
  {
    $data = $this->model->getPlans();
    $arr=array();
    foreach ($data as $item => $key) {
      $val='<td>' . $item . '</td>';
      $val.='<td style="width:6%">' . $key['NSW']['count'] . '</td><td style="width:6%"><input type="text" maxlength="3" class="NSW" style="width:2em" value="' . $key['NSW']['plan'] . '"></td>';
      $val.='<td style="width:6%">' . $key['QLD']['count'] . '</td><td style="width:6%"><input type="text" maxlength="3" class="QLD" style="width:2em" value="' . $key['QLD']['plan'] . '"></td>';
      $val.='<td style="width:6%">' . $key['SA']['count'] . '</td><td style="width:6%"><input type="text" maxlength="3" class="SA" style="width:2em" value="' . $key['SA']['plan'] . '"></td>';
      $val.='<td style="width:6%">' . $key['TAS']['count'] . '</td><td style="width:6%"><input type="text" maxlength="3" class="TAS" style="width:2em" value="' . $key['TAS']['plan'] . '"></td>';
      $val.='<td style="width:6%">' . $key['VIC']['count'] . '</td><td style="width:6%"><input type="text" maxlength="3" class="VIC" style="width:2em" value="' . $key['VIC']['plan'] . '"></td>';
      $val.='<td style="width:6%">' . $key['WA']['count'] . '</td><td style="width:6%"><input type="text" maxlength="3" class="WA" style="width:2em" value="' . $key['WA']['plan'] . '"></td>';
      $val.='</td>';
      $arr[]=$val;
    }
    print json_encode($arr);
  }

  function action_plans_update() {
  $this->model->plans_update();
  }
  
  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

  function action_get_embed()
  {
    $id = $_POST["campaign_id"];
    $embed = $this->model->generateembed($id);
    echo $embed;
  }

  function action_ajax_get()
  {
    $table = 'campaigns';
    $primaryKey = 'id';
    $columns = array(
      array('db' => 'id', 'dt' => 0 ),
      array('db' => 'name', 'dt' => 1 ),
      array('db'=> 'source', 'dt'=>2),
      array('db' => 'cost', 'dt'=> 3),
      array('db' => 'status',  'dt' => 4, 'formatter' => function( $d, $row ) {
          if($d==0){ $string =  "<input type=\"checkbox\" value=\"0\"  >" ; }
          if($d==1){ $string =  "<input type=\"checkbox\" value=\"1\"  checked>" ; }
          return  $string ;
        }
      ),
      array('db'=>'id', 'dt'=>5, 'formatter' => function() {
        $string = '<a href="#" class="edit-campaign" title="Edit campaign" data-toggle="modal" class="edit-button" data-target="#editCampaign"><i class="fa fa-pencil" aria-hidden="true"></i> </a>';
        $string .= ' <a href="#" class="delete-campaign" title="Delete campaign" data-toggle="modal" data-target="#editFields"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        return $string;
      }),
      array('db'=>'id', 'dt'=>6, 'formatter' => function() {
        $string = ' <a href="#" class="edit-campaign-fields" title="Edit campaign fields" data-toggle="modal" data-target="#editFields"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>';
        return $string;
      })
    );

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );
//    ob_clean();
    echo json_encode(
      SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
//    exit;
  }

  function action_addNewCampaign(){
    // var_dump($_POST);
    if(isset($_POST["name"])  && isset($_POST["cost"])) {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $name = $_POST["name"];
      $cost = $_POST["cost"];
      $source = "";
      $status = 1;
      $alphabet = "abcdef0123456789";
      $pass = array();
      $alphaLength = strlen($alphabet) - 1; 
      for ($i = 0; $i < 7; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
      }
      $source = implode($pass);
      $sql = 'INSERT INTO `campaigns`';
      $sql.= '(name, source, cost, status)';
      $sql.= ' VALUES ("'.$name.'","'.$source.'","'.$cost.'",'.$status.')';
      $con = $this->db();
      $result = $con->query($sql);
      if( $result ) {
        $last_id = $con->insert_id;
        $this->model->generetefields($last_id);
        echo "New campaign added ";
      }
      $con->close();
    }
  }
  
  function action_update_campaign_fields_rel(){
    if(!empty($_POST["attr_field_id"]) && !empty($_POST["campaign_id"])) {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $campaign_id = $_POST["campaign_id"];
      $field_id = $_POST["attr_field_id"];
      $newvalue = $_POST["value"];
      $con = $this->db();
      if(isset($_POST["mandatory"])) {
        $sql = "UPDATE `campaign_lead_fields_rel` SET mandatory=$newvalue WHERE campaign_id=$campaign_id";
        $sql.= " AND field_id=$field_id";
      } elseif(isset($_POST["isactive"])){
        $sql = "UPDATE `campaign_lead_fields_rel` SET is_active=$newvalue WHERE campaign_id=$campaign_id";
        $sql.= " AND field_id=$field_id";
      }
      if( $result = $con->query($sql) ) {
        print_r($result);
      }
      $con->close();
    }
  }

  function action_delete_campaign(){
    if(!empty($_POST["id"])) {
      $id = $_POST['id'];
      $sql = "delete from `campaigns` where id='$id'";
      $sql2 = "delete from `campaign_lead_fields_rel` where campaign_id = $id";
      $con = $this->db();
      $result = $con->query($sql);
      $result2 = $con->query($sql2);
      if($result && $result2) { echo "Campaign deleted"; }
    }
  }

  function action_generete_campaign_fields(){
    $campaign_id = $_POST['campaign-id'];
    $fields = $this->model->fields_model($campaign_id);
    echo $fields;
  }

  function action_update_campaign(){
    if(isset($_POST["id"]) && isset($_POST["name"])  && isset($_POST["cost"]) ) {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $status = $_POST["status"];
      if($status == "on" ) $status = 1 ;
      if(empty($status))  $status = 0;
      $id = $_POST["id"];
      $name = $_POST["name"];
      $cost = $_POST["cost"];
      $sql = 'UPDATE `campaigns` SET';
      $sql.= ' name = "'.$name.'"';
      $sql.= ', cost = "'.$cost.'"';
      $sql.= ', status = "'.$status.'"';
      $sql.= ' WHERE id = '.$id;
      $con = $this->db();
      $result = $con->query($sql);
      if($result) {
        echo "Success";
      }
      $con->close();
    }
  }

}
