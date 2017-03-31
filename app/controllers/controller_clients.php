<?php
class Controller_CLients extends Controller {
  function __construct() {
    $this->model = new Model_Clients();
    $this->view = new View();
  }

  function action_index() {

    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      $data["table"] = $this->model->get_data();
      $this->view->generate('clients_view.php', 'template_view.php', $data);
    }
    else
    {
      session_destroy();
      //    echo "Access Denied! Please <a href='/login'>login</a>";
      $this->view->generate('danied_view.php', 'template_view.php', $data);
      //    Route::ErrorPage404();
    }
  }


  function action_ajax_get()
  {
    $table = 'clients';
    $primaryKey = 'id';
    $columns = array(
      array( 'db' => '`a`.`id`', 'dt' => 0 , 'field'=>'id'),
      array( 'db' => '`a`.`campaign_name`', 'dt' => 1, 'field'=>'campaign_name' ),
      array('db'  =>  '`a`.`email`', 'dt'=>2, 'field'=>'email'),
      array('db'  => '`a`.`full_name`', 'dt'=> 3, 'field'=>'full_name'),
      array('db'  => '`a`.`lead_cost`', 'dt'=> 4, 'field'=>'lead_cost'),
      array('db'  => '`a`.`status`',  'dt' => 5, 'formatter' => function( $d, $row ) {
          if($d == 0){ $string =  "<input attr-id='$row[0]' class='delivery_status'  data-size='mini' type=\"checkbox\" value=\"0\"  >" ; }
          if($d == 1){ $string =  "<input attr-id='$row[0]' class='delivery_status'  data-size='mini' type=\"checkbox\" value=\"1\"  checked>" ; }
          return  $string;
        }, 'field'=>'status'
      ),
      array('db'  => '`u`.`active`',  'dt' => 6, 'formatter' => function( $d, $row ) {
        if($d == 0){ $string =  "<input attr-id='$row[0]' class='status_client'  data-size='mini' type=\"checkbox\" value=\"0\"  >" ; }
        if($d == 1){ $string =  "<input attr-id='$row[0]' class='status_client'  data-size='mini' type=\"checkbox\" value=\"1\"  checked>" ; }
        return  $string;
      }, 'field'=>'active'
      ),
      array('db'=>'`a`.`id`', 'dt'=>7, 'formatter' => function($d, $row) {
        $string = '<a href="#" class="edit-client" attr-id="'. $row[0] .'" attr-name="'. $row[1] .'" title="Edit campaign" data-toggle="modal" class="edit-button" data-target="#editClient"><i class="fa fa-pencil" aria-hidden="true"></i> </a>';
        $string .= ' <a href="#" class="delete-client" attr-id="'. $row[0] .'" attr-name="'. $row[1] .'" title="Delete campaign"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        return $string;
      }, 'field'=> 'id')
    );
    $joinQuery = "FROM `{$table}` as `a` LEFT JOIN `users` AS `u` ON (`a`.`id` = `u`.`id`)";

    $sql_details = array(
      'user' => DB_USER,
      'pass' => DB_PASS,
      'db'   => DB_NAME,
      'host' => DB_HOST
    );
//    ob_clean();
    echo json_encode(
      SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery)
    );
//    exit;
  }

  function action_update_clients_fields_rel(){
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $id = $_POST["id"];
    $status = $_POST["status"];
    $con = $this->db();
    if(isset($_POST["id"])) {
      $sql = "UPDATE `clients` SET status=$status WHERE id=$id";
    }

    if( $result = $con->query($sql) ) {
      print_r($result);
    }
    $con->close();
  }
  
  function action_update_user_active(){
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $id = $_POST["id"];
    $status = $_POST["status"];
    $con = $this->db();
    if(isset($_POST["id"])) {
      $sql = "UPDATE `users` SET active=$status WHERE id=$id";
    }

    if( $result = $con->query($sql) ) {
      print_r($result);
    }
    $con->close();
//    echo "hi there";
  }

  function action_editClient(){
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
//    var_dump($_POST);
    if($_POST["id"]){
      $id = $_POST["id"];
      $sql = ' SELECT clients.id, clients.email, clients.campaign_name, users.password, clients.full_name, clients.phone, clients.city, clients.state, clients.country, clients.lead_cost, clients_criteria.postcodes, clients_criteria.states_filter, clients_billing.xero_id, clients_billing.xero_name, clients_criteria.weekly';
      $sql.= ' FROM `clients`';
      $sql.= ' LEFT JOIN `clients_billing` ON clients.id = clients_billing.id';
      $sql.= ' LEFT JOIN `clients_criteria` ON clients.id = clients_criteria.id';
      $sql.= ' LEFT JOIN `users` ON clients.id = users.id';
      $sql.= ' WHERE clients.id = '.$id;
      $form_keys = array(
            'id' => '',
            'campaign_name' => 'Campaign name',
            'email' => 'Email',
            'password' => 'Password',
            'full_name' => 'Full name',
            'phone' => 'Phone',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'lead_cost' => 'Lead cost',
            'postcodes' => 'Postcodes',
            'states_filter' => 'States filter',
            'xero_id' => 'Xero id',
            'xero_name' => 'Xero name',
            'weekly' => 'Weekly'
      );
//      echo $sql;
//      $sql = "SELECT * FROM `clients` WHERE id='$id'";
      $con = $this->db();
      $res = $con->query($sql);
      while($row = $res->fetch_assoc()) {
        foreach ($row as $k=>$v) {
            if($k == "id") {
                echo "<input type='hidden' name='id' value='$v' />";
            } else if($k=="password") {
              echo "<div class='form-group'>";
              echo "<label for='$k'>".$form_keys["$k"]."</label>";
              echo '<input class="form-control" type="password" name="'.$k.'"  > ' ;
              echo "</div>";
            } else if($k=="postcodes") {
              echo '<div class="form-group">
                <textarea class="form-control" placeholder="Post codes" type="text" id="postcode" name="postcodes">'. $v .'</textarea> 
              </div>';
            } else {
                echo "<div class='form-group'>";
                echo "<label for='$k'>".$form_keys["$k"]."</label>";
                echo '<input class="form-control" type="text" name="'.$k.'" value="'.$v.'"  > ' ;
                echo "</div>";
            }

 //           echo "'$k'" ."<br>";
//            $table.= '<input class="form-control" type="text" name="'.$k.'" value="'.$v.'" required > ' ;
            }
//            echo $table;
      }
    }
  }

  function action_UpdateClients(){
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $id = $_POST["id"];
      $client_name = $_POST["campaign_name"];
      $status = 1;
      $email = $_POST["email"];
      $full_name = $_POST["full_name"];
      $lead_cost = $_POST["lead_cost"];
      $password = md5($_POST["password"]);
      $xero_id = $_POST["xero_id"];
      $xero_name = $_POST["xero_name"];
      $phone = phone_valid($_POST["phone"]);
      $city = $_POST["city"];
      $state = $_POST["state"];
      $postcodes = postcodes_valid($_POST["postcodes"]);
      $country = $_POST["country"];
      $states_filter = $_POST["states_filter"];
      $weekly = (int)$_POST["weekly"];

//      echo '<pre>';
//      var_dump($_POST);
//       echo '</pre>';


        $sql = "UPDATE `clients`";
        $sql.= " SET campaign_name='$client_name', email='$email', full_name='$full_name', lead_cost=$lead_cost, phone='$phone', city='$city', state='$state', country='$country', status=$status";
        $sql.= " WHERE id='$id'";
        $con = $this->db();
        $res = $con->query($sql);


      if($con->query($sql)) $res = 1;

      $sql1 = "UPDATE `clients_billing`";
      $sql1.= " SET xero_id = '$xero_id', xero_name='$xero_name'";
      $sql1.= " WHERE id='$id'";
      if($con->query($sql1)) $res1 = 1;

      $sql2 = "UPDATE `clients_criteria`";
      $sql2.= " SET weekly = $weekly, states_filter='$states_filter', postcodes='$postcodes'";
      $sql2.= " WHERE id='$id'";
      if($con->query($sql2)) $res2 = 1;

      if(trim($_POST["password"]) === "") {
        $sql3 = "UPDATE `users`";
        $sql3 .= " SET email = '$email', active='$status', full_name='$full_name'";
        $sql3 .= " WHERE id='$id'";
        $res3 = $con->query($sql3);
      } else {
        $sql3 = "UPDATE `users`";
        $sql3 .= " SET email = '$email', password='$password', active='$status', full_name='$full_name'";
        $sql3 .= " WHERE id='$id'";
        $res3 = $con->query($sql3);
      }
      if($con->query($sql3)) $res3 = 1;
      if($res && $res1 && $res2 && $res3 ) {
        echo "Success";
      } else {
        echo "Client not added! DB error";
        $con->close();
        exit();
     }
      $con->close();
  }

  function action_delete_clients(){
    if(!empty($_POST["id"])) {
      $id = $_POST['id'];
      $sql = "delete from `clients` where id='$id'";
      $sql1 = "delete from `clients_billing` where id = $id";
      $sql2 = "delete from `clients_criteria` where id = $id";
      $sql3 = "delete from `users` where id = $id";
      $con = $this->db();
      $result = $con->query($sql);
      $result1 = $con->query($sql1);
      $result2 = $con->query($sql2);
      $result3 = $con->query($sql3);
      if($result && $result1 && $result2 && $result3) { echo "Client deleted"; }
      $con->close();
    }
  }

  function action_addNewClient(){
      include "app/models/model_leads.php";
      $lead=new Model_Leads();
      if(isset($_POST["campaign_name"])  && isset($_POST["email"])) {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $client_name = $_POST["campaign_name"];
      $status = 1;
      $email = $_POST["email"];
      $full_name = $_POST["full_name"];
      $lead_cost = $_POST["lead_cost"];
      $password = md5($_POST["password"]);
      $xero_id = $_POST["xero_id"];
      $xero_name = $_POST["xero_name"];
      if($_POST['limits']=='limited')
      {
            $weekly = (int)$_POST["weekly"];
      } else {
            $weekly = 'null';
      }
      $phone = phone_valid($_POST["phone"]);
      $city = $_POST["city"];
      $state = $_POST["state"];
      $postcodes = postcodes_valid($_POST["postcodes"]);
      $country = $_POST["country"];
      $states_filter = $_POST["states_filter"];
      
      $level = 3;
      
      $sql = 'INSERT INTO `users`';
      $sql.= '(email, password, level, active, full_name )';
      $sql.= " VALUES ('$email', '$password', '$level', '$status', '$full_name' )";
      
      $con = $this->db();

      if($con->query($sql)) $user_added = 1;
      
      $last_id = $con->insert_id;

      $sql1 = 'INSERT INTO `clients_billing`';
      $sql1.= '(id, xero_id, xero_name)';
      $sql1.= " VALUES ($last_id, '$xero_id','$xero_name')";
      
      if($con->query($sql1)) $billing_added = 1;

      $sql2 = 'INSERT INTO `clients`';
      $sql2.= '(id, campaign_name, email, full_name, lead_cost , status, phone, city, state, country )';
      $sql2.= " VALUES ('$last_id', '$client_name','$email','$full_name', '$lead_cost', '$status', '$phone', '$city', '$state', '$country' )";

      if($con->query($sql2)) $clients_aded = 1;

      $sql3 = 'INSERT INTO `clients_criteria`';
      $sql3.= '(id, weekly, states_filter, postcodes )';
      $sql3.= " VALUES ($last_id, $weekly, '$states_filter', '$postcodes' )";
      
      if($con->query($sql3)) $criteria_added = 1;
      if($user_added && $billing_added && $clients_aded && $criteria_added ) { 
        echo "Success";
      } else {
        echo "Clients not added! DB error";
        exit;
     }
     $start=time()-86400*7;
     $end=time();
     print '<br>Trying to send existing leads. . .<br>';
     print($lead->senManyLeads($last_id, $start, $end, '',0));
     $con->close();
     }
    }
  function action_delete_campaign(){
    if(!empty($_POST["id"])) {
      $id = $_POST['id'];
      $sql = "delete from `campaigns` where id='$id'";
      $result = $this->model->sql($sql);
      if($result) { echo "Campaign deleted"; }
    }
  }
  function action_update_campaign(){
    if(isset($_POST["id"]) && isset($_POST["name"])  && isset($_POST["cost"]) ) {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
     // var_dump($_POST);
     // exit();
      $status = $_POST["status"];
      if($status == "on" ) $status = 1 ;
      if(empty($status))  $status = 0;
      $id = $_POST["id"];
      $name = $_POST["name"];
      $cost = $_POST["cost"];
      // $source = "67f8442";
      $sql = 'UPDATE `campaigns` SET';
      $sql.= ' name = "'.$name.'"';
      // $sql.= ', source = "'.$source.'"';
      $sql.= ', cost = "'.$cost.'"';
      $sql.= ', status = "'.$status.'"';
      $sql.= ' WHERE id = '.$id;
      // echo $sql;
      $result = $this->model->sql($sql);
      if($result) {
        echo "Success";
      }
    }
  }

  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }
}
