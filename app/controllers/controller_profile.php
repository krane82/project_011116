<?php
class Controller_Profile extends Controller {

  function __construct() {
    $this->model = new Model_Profile();
    $this->view = new View();
  }

  function action_index() {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['user'] == md5('user'))
    {
      $id = $_SESSION["user_id"];
      $data["profile"] = $this->model->get_profile_data($id);
      $this->view->generate('profile_view.php', 'client_template_view.php', $data);
    }
    else
    {
      session_destroy();
      $this->view->generate('danied_view.php', 'client_template_view.php', $data);
    }
  }

  function action_UpdateProfile(){
    session_start();
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    if($id = $_SESSION["user_id"]){
      $sql = 'SELECT clients.id, users.password, clients.email, clients.campaign_name, clients.full_name, clients.phone, clients.city, clients.state, clients.country, clients.lead_cost, clients_criteria.postcodes, clients_criteria.states_filter, clients_billing.xero_id, clients_billing.xero_name, clients_criteria.monthly ,clients_criteria.weekly';
      $sql.= ' FROM `clients`';
      $sql.= ' LEFT JOIN `clients_billing` ON clients.id = clients_billing.id';
      $sql.= ' LEFT JOIN `clients_criteria` ON clients.id = clients_criteria.id';
      $sql.= ' LEFT JOIN `users` ON clients.id = users.id';
      $sql.= ' WHERE clients.id = '.$id;
//      dd($sql);
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
        'monthly' => 'Monthly caps',
        'weekly' => 'Weekly caps'
      );
      $con = $this->db();
      $res = $con->query($sql);
      echo "<form action='" .__HOST__. "/profile/UpdateProfileSuccess' method='post'>";
      while($row = $res->fetch_assoc()) {
        foreach ($row as $k=>$v) {
          if($k == "id"){
            echo "<input type='hidden' name='$k' value='$v' />";
          } elseif($k == "lead_cost" || $k == "xero_id" || $k == "xero_name" ) {
            // blank - only admin can change
            } elseif ($k == "password") {
            echo "<div class='form-group'>";
            echo "<label for='$k'>Password</label>";
            echo "<input type='password' class=\"form-control\" id='$k' name='$k' value='' placeholder='Leave blank if you dont wanna change it'/>";
            echo "</div>";
          } elseif ($k == "postcodes") {
            echo "<div class='form-group'>";
            echo "<label for='$k'>".$form_keys["$k"]."</label>";
            echo "<textarea class='form-control' name='postcodes' type='text'>$v</textarea>";
            echo "</div>";
          }elseif($k == "states_filter" OR $k == "postcodes"){
            echo "<div class='form-group'>";
            echo "<label for='$k'>". $form_keys["$k"] ."</label>";
            echo '<input class="form-control" type="text" name="'.$k.'" value="'.$v.'" readonly="readonly" > ' ;
            echo "</div>";
          } else {
            echo "<div class='form-group'>";
            echo "<label for='$k'>".$form_keys["$k"]."</label>";
            echo '<input class="form-control" type="text" name="'.$k.'" id="'.$k.'" value="'.$v.'" > ' ;
            echo "</div>";
          }
        }
      }
      echo '<button attr-id="'.$id.'" type="submit" class="btn btn-primary">Update profile</button>';
      echo '</form>';
    }
  }

  public function action_UpdateProfileSuccess(){
    $chekedPOST = $this->model->checkdata($_POST);
    $id = $chekedPOST["id"];
    $client_name = $chekedPOST["campaign_name"];
    $status = 1;
    $email = $chekedPOST["email"];
    $full_name = $chekedPOST["full_name"];
    $password = md5($chekedPOST["password"]);
    $phone = phone_valid($chekedPOST["phone"]);
    $city = $chekedPOST["city"];
    $state = $chekedPOST["state"];
    $country = $chekedPOST["country"];
    $postcodes = postcodes_valid($chekedPOST["postcodes"]);
    $country = $chekedPOST["country"];
    $states_filter = $chekedPOST["states_filter"];
    $weekly = (int)$chekedPOST["weekly"];
    $monthly = (int)$chekedPOST["monthly"];

    $con = $this->db();

    $sql1 = "UPDATE `clients`";
    $sql1.= " SET campaign_name='$client_name', email='$email', full_name='$full_name', phone='$phone', city='$city', state='$state', country='$country'";
    $sql1.= " WHERE id='$id'";

    if($con->query($sql1)) $res1 = 1;

    $sql2 = "UPDATE `clients_criteria`";
    $sql2.= " SET weekly = '$weekly', monthly='$monthly', states_filter='$states_filter', postcodes='$postcodes'";
    $sql2.= " WHERE id='$id'";

    if($con->query($sql2)) $res2 = 1;


    $sql3 = "UPDATE `users`";
    $sql3.= " SET email = '$email', password='$password', active='$status', full_name='$full_name'";
    $sql3.= " WHERE id='$id'";
    $res3 = $con->query($sql3);

    if(empty($_POST["password"])){
      $sql3 = "UPDATE `users`";
      $sql3.= " SET email = '$email', active='$status', full_name='$full_name'";
      $sql3.= " WHERE id='$id'";
    }else{
      $sql3 = "UPDATE `users`";
      $sql3.= " SET email = '$email', password='$password', active='$status', full_name='$full_name'";
      $sql3.= " WHERE id='$id'";
    }

    if($con->query($sql3)) $res3 = 1;

    if($res1 && $res2 && $res3 ) {
      echo $this->model->UserChangeNotif($chekedPOST);
      header('Location: /profile');
    } else {
      echo "<script>alert('DB error')</script>";
      $con->close();
      header('Location: /profile');
      exit;
    }
    $con->close();
  }


  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

}