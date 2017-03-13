<?php
class Model_Leads extends Model {
  private $api;
  function __construct($api)
  {
    $this->api = $api;
  }

  public function getLeadSources() {
    $con = $this->db();
    $LeadSources = array();
    $sql = "SELECT `source`, `name` FROM `campaigns`";
    $res = $con->query($sql);
    while ($row = $res->fetch_assoc()){
      $LeadSources[] = $row;
    }
    return $LeadSources;
  }
  public function getAllClients()
  {
    $con = $this->db();
    $clients = array();
    $sql = "SELECT `id`, `campaign_name` FROM `clients`";
    $res = $con->query($sql);
    while ($row = $res->fetch_assoc()) {
      $clients[] = $row;
    }
    $con->close();
    return $clients;
  }

  private function getLeadInfo($id)
  {
    $con = $this->db();
    $sql = "SELECT * FROM leads_lead_fields_rel WHERE id='".$id."'";
    $res = $con->query($sql);
    if($res){
      $r = $res->fetch_assoc();
      return $r;
     } else
    return "Lead not found";
  }

  private function getClientById($id)
  {
    $con = $this->db();
    $sql = 'SELECT cc.id, c.email, c.full_name';
    $sql.= ' FROM `clients_criteria` as cc';
    $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
    $sql.= " WHERE cc.id=$id";
    $res = $con->query($sql);
    if ($res) {
      $client = $res->fetch_assoc();
    } else {
      return 'Db error when trying to get client email';
    }
    return $client;
  }
  
  public function senLead($client_id, $lead_id)
  {
    $receivers=$this->getLeadFromDelivered($lead_id);
    if($client_id != 0){
      if(in_array($client_id, $receivers ))
      {
        return "This client already has this lead";
      }
      $leadInfo = $this->getLeadInfo($lead_id);
      $readyLeadInfo = prepareLeadInfo($leadInfo);
      $passedCaps = $this->api->checkClientsLimits($client_id);
      $c = $this->getClientById($client_id);
      if($passedCaps) {
        $delivery_id = $this->getLastDeliveryID() + 1;
        $sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"],$delivery_id);
        if($sent) {
          $this->api->addToDeliveredTable($client_id, $lead_id, $readyLeadInfo);
          return "Lead sent.";
        } else {
          return "mail error: $sent";
        }
      } else {
        return "Cannot send over client caps...";
      }
    }
    if($client_id == 0)
    {
      $leadInfo = $this->getLeadInfo($lead_id);
      $clients = $this->api->getClients($leadInfo);
      // print_r($clients);
      // exit;
      $result = $this->sendToClients($clients, $lead_id, $leadInfo);
      return $result;
    }
  }

  private function getLeadFromDelivered($id)
  {
    $con = $this->db();
    $sql="SELECT client_id FROM leads_delivery WHERE lead_id=".$id;
    $res=$con->query($sql);
    $result=array();
    if($res)
    {
      while ($row = $res->fetch_array())
      {
        $result[] = $row['client_id'];
      }
    return $result;
    }
    return false;
  }

  public function getList($start, $end, $state=false, $source=false)
  {
    $con = $this->db();
    $sql = "SELECT l.id FROM leads AS `l` LEFT JOIN `leads_lead_fields_rel` AS `lf` ON `lf`.`id`=`l`.`id` WHERE l.datetime BETWEEN '".$start."' AND '".$end."'";
    if ($state) $sql.=" AND lf.state='".$state."'";
    if ($source) $sql.=" AND lf.source='".$source."'";
    $res=$con->query($sql);
    //return $sql;
    if($res) {
      while ($row = $res->fetch_assoc()) {
        $result[] = $row['id'];
      }
      return $result;
    }
    return false;
  }

  private function sendToClients($clients, $lead_id ,$p){
    $receivers=$this->getLeadFromDelivered($lead_id);
    $counter = count($receivers);
    if($counter>=4)
    {
      return 'This lead already sent 4 times';
    }
    $sentTo = '';
    foreach ($clients as $c) {
      $id = $c["id"];
      if(in_array($id,$receivers))
      {
        continue;
      }
      $passedCaps = $this->checkClientsLimits($id);
      if($passedCaps AND $counter < 4) {
        $readyLeadInfo = prepareLeadInfo($p);
        $delivery_id = $this->getLastDeliveryID() + 1;
        $sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"],$delivery_id);
        if($sent) {
          $counter++;
          $sentTo .= "Lead #$lead_id sent to $c[full_name] : $c[email]<br>\n";
          $this->api->addToDeliveredTable($id, $lead_id, $readyLeadInfo);
        }
      }
    }
    return $sentTo;
  }

  private function getLastDeliveryID(){
    $sql = "SELECT `id` FROM leads_delivery ORDER BY `id` DESC LIMIT 1";
    $db  = DB::getInstance();
    $res = $db->get_row($sql);
    return $res[0];
  }

  private function sendToClient($mail, $p, $client_name, $track_id)
  {
    if($mail) {
      send_m($mail, $p, $client_name, $track_id);
      return TRUE;
    }
    return FALSE;
  }

  private function checkClientsLimits($id)
  {
    $Monday = strtotime( "Monday this week" );
    $FirstOfMonth = strtotime(date('Y-m-01'));
    $now = time();
    $sqlM = "select count(*) from `leads_delivery` where client_id = $id AND (timedate BETWEEN $FirstOfMonth AND $now)";
    $sqlW = "select count(*) from `leads_delivery` where client_id = $id AND (timedate BETWEEN $Monday AND $now)";
    $sqlCaps = "SELECT weekly, monthly  FROM `clients_criteria` WHERE id=$id";

    $con = $this->db();

    $capsr = $con->query($sqlCaps);
    $caps = $capsr->fetch_assoc();

    $sqlMr = $con->query($sqlM);
    $sqlMM = $sqlMr->fetch_assoc();

    if(!$caps["monthly"]){
      $caps["monthly"] = 999999999;
    }

    if(!$caps["weekly"]){
      $caps["weekly"] = 999999999;
    }


    if( $sqlMM["count(*)"] <= $caps["monthly"]){
      $id_passed = $id;
    } else {
      echo "monthly not passed!";
      $con->close();
      return FALSE;
    }

    $sqlWr = $con->query($sqlM);
    $sqlWW = $sqlWr->fetch_assoc();

    if($sqlWW["count(*)"] <= $caps["weekly"]){
      $id_passed = $id;
      $con->close();
      return $id_passed;
    } else {
      $con->close();
      return FALSE;
    }
  }

  private function getClients($post){
    $clients = array();
    $con = $this->db();
    if( !empty($post["state"]) || !empty($post["postcode"]) ){
      $sql = 'SELECT cc.id, c.email, c.full_name';
      $sql.= ' FROM `clients_criteria` as cc';
      $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
      if(!empty($post["state"]) AND !empty($post["postcode"])) {
        $sql .= ' WHERE cc.states_filter LIKE "%' . $post["state"] . '%" OR cc.postcodes LIKE "%'.$post["postcode"].'%"';
      } else if(!empty($post["state"])) {
        $sql .= ' WHERE cc.states_filter LIKE "%' . $post["state"] . '%"';
      } else if(!empty($post["postcode"])){
        $sql.= ' WHERE cc.postcodes LIKE "%'.$post["postcode"].'%"';
      }
      $sql .= ' AND c.status = 1';
      $sql .= ' ORDER BY c.lead_cost DESC';
    } else {
      return FALSE;
    }


    $res = $con->query($sql);
    if ($res) {
      while( $res->fetch_assoc())
      {
        foreach ($res as $k=>$v) {
          $clients["$k"] = $v;
        }
      }
    } else {
      echo "<br>no clients for this lead criteria<br>";
      return FALSE;
    }
    return $clients;
  }

  private function checkdata($post){
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

?>