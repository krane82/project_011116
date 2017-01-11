<?php
class Model_Profile extends Model {

  public function get_profile_data($id) {
    
      $sql = ' SELECT clients.id, clients.email, clients.campaign_name, clients.full_name, clients.phone, clients.city, clients.state, clients.country, clients.lead_cost, clients_criteria.postcodes, clients_criteria.states_filter, clients_billing.xero_id, clients_billing.xero_name, clients_criteria.monthly ,clients_criteria.weekly'; 
      $sql.= ' FROM `clients`';
      $sql.= ' LEFT JOIN `clients_billing` ON clients.id = clients_billing.id';
      $sql.= ' LEFT JOIN `clients_criteria` ON clients.id = clients_criteria.id';
      $sql.= ' LEFT JOIN `users` ON clients.id = users.id';
      $sql.= ' WHERE clients.id = '.$id;
      
      $form_keys = array(
            'id' => '',
            'campaign_name' => 'Capaing name',
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
      
      $profile = '<div class="success">';
      $con = $this->db();
      $res = $con->query($sql);
      while($row = $res->fetch_assoc()){
          foreach ($row as $k => $v) {
              if($k == "id"){
              $profile.= "<input type='hidden' name='$k' value='$v' />";
              }elseif ($k == "password") {
              $profile.= "<input type='password' name='$k' value=''/>";
              }elseif($k == "lead_cost" || $k == "xero_id" || $k == "xero_name" ) {

              }else{
              $profile.= "<div class='form-group'>";
              $profile.= "<label for='$k'>".$form_keys["$k"]."</label>";
              $profile.= '<input class="form-control" type="text" name="'.$k.'" value="'.$v.'" readonly="readonly" > ' ;
              $profile.= "</div>";
              }
          }
      }
      $profile .= "</div>";
    return $profile;
  }
  public function checkdata($post){
    $p = array();
    foreach ($post as $k => $v) {
      if ($k=="phone") {
        $p["phone"] = phone_valid($v);
      } else if($k=="postcode"){
        $p["postcode"] = (int)postcodes_valid($v);
      }
      else {
        $p["$k"] = trim($v);
      }
    }
    return $p;
  }
}

