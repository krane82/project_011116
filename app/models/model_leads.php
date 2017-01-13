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
    $sql = "SELECT * FROM `leads_lead_fields_rel` WHERE `id`=$id";
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
    if($client_id !== 0){
      $leadInfo = $this->getLeadInfo($lead_id);
      $prepearedinfo = prepareLeadInfo($leadInfo);
      $passedcaps = $this->api->checkClientsLimits($client_id);
      $c = $this->getClientById($client_id);
      if($passedcaps) {
        $sended = $this->sendToClient($c["email"], $prepearedinfo, $c["full_name"]);
        if($sended) {
          $this->api->addToDeliveredTable($id, $lead_id, $readyLeadInfo);
          return "Lead sended.";
        } else {
          return "mail error: $sended";
        }
      } else {
        return "Cannot send over client caps...";
      }
    }
    if($client_id === 0){
      $leadInfo = $this->getLeadInfo($lead_id);
      $clients = $this->api->getClients($leadInfo);
      // print_r($clients);
      // exit;
      $result = $this->sendToClients($clients, $lead_id, $leadInfo);
      return $result;
    }
  }

  private function sendToClients($clients, $lead_id ,$p){
    $counter = 0;
    $sendedTo = '';
    foreach ($clients as $c) {
      $id = $c["id"];
      $passedcaps = $this->checkClientsLimits($id);
      if($passedcaps AND $counter < 4) {
        $readyLeadInfo = prepareLeadInfo($p);
        $sended = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"]);
        if($sended) {
          $counter++;
          $sendedTo .= "Lead #$lead_id sended to $c[full_name] : $c[email]<br>\n";
          $this->api->addToDeliveredTable($id, $lead_id, $readyLeadInfo);
        }
      }
    }
    return $sendedTo;
  }

  private function sendToClient($mail, $p, $client_name)
  {
    if($mail) {
      send_m($mail, $p, $client_name);
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