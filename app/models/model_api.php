<?php
class Model_Api extends Model {
  private $debug_api = FALSE;

  public function proccess_lead($post, $counter=0, $addToTable=true, $leadId=0) {
    $p = $this->checkdata($post);
    if ($addToTable) {
      $lead_id = $this->addleadtotable($p);
    } else {
      $lead_id=$leadId;
    } if (!$lead_id) {
      return FALSE;
    }
    $clients = $this->getClients($p);
    $resp =  $this->sendToClients($clients, $lead_id, $p, $counter);
    return $resp;
  }


  private function sendToClients($clients, $lead_id ,$p, $counter){
    $sended = '';
    foreach ($clients as $c ) {
      $client_id = $c["id"];
      //here will be checking if is already delivered to current client
      $clients = explode(',', $p['clients']);
      if (in_array($client_id, $clients)) continue;
      $passedcaps = $this->checkClientsLimits($client_id);
      if($passedcaps AND $counter < 4) {
        $readyLeadInfo = prepareLeadInfo($p);
        $delivery_id = $this->getLastDeliveryID() + 1;
        $sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"], $delivery_id);
        if($sent) {
          $counter++;
          $this->addToDeliveredTable($client_id, $lead_id, $readyLeadInfo);
          $sended .= "$c[full_name] : $c[email] \n <br>";
        }
      }
    }
    return "Sent to $counter clients \n";// . $sended;
  }

  private function getLastDeliveryID(){
    $sql = "SELECT `id` FROM leads_delivery ORDER BY `id` DESC LIMIT 1";
    $db  = DB::getInstance();
    $res = $db->get_row($sql);
    return $res[0];
  }


  public function addToDeliveredTable($client_id, $lead_id, $p){
    $con = $this->db();
    $now = time();
    $sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate) VALUES ('".$lead_id."', '".$client_id."', '".$now."')";
//    var_dump($sql);
    $sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval) VALUES ('".$lead_id."', '".$client_id."', '".$now."', '1')";
//    var_dump($sql_r);
    if($con->query($sql) && $con->query($sql_r)) { $delivered=1; }
    if($delivered){
      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function sendToClient($mail, $p, $client_name, $track_id)
  {
    if($mail) {
      send_m($mail, $p, $client_name, $track_id);
      return TRUE;
    }
    return FALSE;
  }

  public function checkClientsLimits($id)
  {
    $Monday = strtotime( "Monday this week" );
    $FirstOfMonth = strtotime(date('Y-m-01'));
    $now = time();
    $sqlM = "SELECT count(*) FROM `leads_delivery` WHERE client_id = $id AND (timedate BETWEEN $FirstOfMonth AND $now)";
    $sqlW = "SELECT count(*) FROM `leads_delivery` WHERE client_id = $id AND (timedate BETWEEN $Monday AND $now)";
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

    $sqlWr = $con->query($sqlW);
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

  public function getClients($post){
    $clients = array();
    $con = $this->db();
    if( !empty($post["state"]) || !empty($post["postcode"]) ){
      $sql = 'SELECT cc.id, c.email, c.full_name';
      $sql.= ' FROM `clients_criteria` as cc';
      $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
      if(!empty($post["state"]) AND !empty($post["postcode"])) {
        $sql .= ' WHERE ( cc.states_filter LIKE "%' . $post["state"] . '%" OR cc.postcodes LIKE "%'.$post["postcode"].'%" )';
        // } else if(!empty($post["state"])) {
        //   $sql .= ' WHERE cc.states_filter LIKE "%' . $post["state"] . '%"';
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

    if($this->debug_api) {

      // LOGS
      // #start buffering (all activity to the buffer)
      ob_start();
      $fileName = __FILE__ . "/logs/";
      $fileName .= date("Y-m-d-h-i-s");
      $fileName .= "log.html";

      $myfile = fopen($fileName, "w");


      echo '<!DOCTYPE html>
            <html>
            <head>
              <title>LOG</title>
            </head>
            <body>';

      echo "CLIENTS : \n";
      var_dump($clients);
      echo "===========\n";
      echo "===========POST===========\n";
      var_dump($_POST);
      echo "\n=================\n";
    }
    // GET destribution ORDER
    $now = time();
    $st = new DateTime(date('Y-m-01', $now));
    $start = $st->getTimestamp();
    $st->modify("+14 days");
    $end = $st->getTimestamp();
    if( $now < $end ) {
      // do nothing
    } else {
      $start = $end;
      $end = strtotime(date("Y-m-t", $now));
    }
    $sql =  'SELECT c.id as id, IFNULL(c.lead_cost,0) as lead_cost, IF(COUNT(*)=0,0,SUM(IF(lr.approval=0,1,0))/COUNT(*)) as percentage, ((COUNT(*) - SUM(IF(lr.approval=0,1,0)))*c.lead_cost) as revenue FROM `leads_delivery` as ld';
    $sql .= ' INNER JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' INNER JOIN clients as c ON ld.client_id=c.id';
    $sql .= ' WHERE `ld`.`timedate` BETWEEN '.$start.' AND '.$end.'';
    $sql .= ' GROUP BY ld.client_id';
    $sql .= ' ORDER BY revenue DESC, percentage ASC, lead_cost DESC';

    $res = $con->query($sql);
    if ($res) {
      while( $res->fetch_assoc())
      {
        foreach ($res as $v) {
          $order[] = $v["id"];
        }
      }
    }

    if(count($order)){

      usort($clients, function ($a, $b) use ($order) {
        $pos_a = array_search($a['id'], $order);
        $pos_b = array_search($b['id'], $order);
        return $pos_a - $pos_b;
      });

      if($this->debug_api) {
        echo "\n===============$order================\n";
        var_dump($order);
        echo "\n================================\n";

        echo "\n======CLIENTS SORTED===========\n";
        var_dump($clients);
        echo "\n================================\n";
        echo "</body></html>";


        # dump buffered $classvar to $outStringVar
        $outStringVar = ob_get_contents();

        fwrite($myfile, $outStringVar);
        fclose($myfile);
        # clean the buffer & stop buffering output
        ob_end_clean();
        // END LOGS
      }
      // function custom_compare($a, $b){
      //   global $order;
      //   $key_a = array_search($a["id"], $order);
      //   $key_b = array_search($b["id"], $order);
      //   if($key_a === false && $key_b === false) { // both items are dont cares
      //       return 0;                      // a == b
      //   }
      //   else if ($key_a === false) {           // $a is a dont care item
      //       return 1;                      // $a > $b
      //   }
      //   else if ($key_b === false) {           // $b is a dont care item
      //       return -1;                     // $a < $b
      //   }
      //   else {
      //       return $key_a - $key_b;
      //   }
      // }

      // usort($clients, "custom_compare");
    }
    // Returnig ordered clients
    return $clients;
  }

  private function checkdata($post){
    $p = array();
//    if ( empty($post["full_name"]) || empty($post["email"]) || empty($post["phone"]) || empty($post["address"]) || empty("state") || empty("postcode") || empty($post["suburb"]) )
//    {
//      return FALSE;
//    }
    foreach ($post as $k => $v) {
      if ($k=="phone") {
        $p["phone"] = phone_valid($v);
      } else if($k=="postcode"){
        $p["postcode"] = postcodes_valid($v);
        if(!$p["postcode"]) return;
      } else {
        $p["$k"] = trim($v);
      }
    }
    $p["state"] = $this->giveMeState($p["postcode"]);
    return $p;
  }
  private function giveMeState($code)
  {
    $con=$this->db();
    $sql="SELECT * FROM states_postcodes";
    $res=$con->query($sql);
    while ($row=$res->fetch_assoc())
    {
      $result[$row['state']][]=explode(':',$row['postcodes']);
    }
    foreach ($result as $item=>$key)
    {
      foreach ($key as $key1)
      {
        if ($code>=$key1[0] && $code<=$key1[1])
        {return $item;}
      }

    }
  }

  private function addleadtotable($post)
  {
    $con = $this->db();
    if ($con->connect_errno) {
      printf("Connect failed: %s\n", $con->connect_error);
      exit();
    }
    $id = getCampaignID($post['source']);
    if(!$id){
      return FALSE;
    }
    $now = time();
    $addlead = "INSERT INTO `leads` (campaign_id, datetime) VALUES ($id, $now)";
    $con->query($addlead);
    $lastid = $con->insert_id;
    $tt = "INSERT INTO `leads_lead_fields_rel` (id, ";
    $col = "";
    foreach ( $post as $k => $v) {
      $col .= "$k" . ", ";
    }
    $col = substr($col, 0, -2);
    $tt2 = ") VALUES ($lastid, ";
    $val = "";
    foreach ($post as $k => $v) {
      $v = trim($v);
      $val .= "'$v'" . ", ";
    }
    $val = substr($val, 0, -2);
    $tt3 = ")";
    $lead_fields_query = $tt . $col . $tt2 . $val . $tt3;
    $con->query($lead_fields_query);
    $con->close();
    return $lastid;
  }
  //  private function prepareLeadInfo($p){
//    $campaign_id = getCampaignID($p["source"]);
//    $readyLeadInfo = array();
//    $con = $this->db();
//
//    // query to get fields from campaign
//    $sql = 'SELECT lead_fields.id, lead_fields.key, lead_fields.name, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory';
//    $sql.= ' FROM `campaign_lead_fields_rel`';
//    $sql.= ' LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id';
//    $sql.= ' WHERE campaign_lead_fields_rel.campaign_id ='.$campaign_id;
//    $res = $con->query($sql);
//    if ($res) {
//      while( $res->fetch_assoc())
//      {
//        foreach ($res as $r) {
//          $key = $r["key"];
//          if($r["is_active"]){
//            $preArray["key"] = $key;
//            $preArray["val"] =  $p["$key"];
//            $preArray["field_name"] = $r["name"];
//            $readyLeadInfo[] = $preArray;
//            unset($preArray);
//          }
//        }
//      }
//    } else {
//      echo "<br>Something WRONG<br>";
//      return FALSE;
//    }
//    return $readyLeadInfo;
//  }
//Mironenko method - returns list of sent leads with count of delivers
  public function getSentLeads()
  {
    //this variable is for show how many days from query do we need, will be saved in DB and added from interface
    include "model_settings.php";
    $settings=new Model_Settings();
    $data=$settings->getSettings();
    $con = $this->db();
    $range = time() - ($data['days'] * 86400);
    $sql = "SELECT le_fi.*, count(led.lead_id) as 'count', group_concat(led.client_id) as 'clients' FROM leads lea left join leads_lead_fields_rel le_fi on lea.id=le_fi.id left join leads_delivery led on lea.id=led.lead_id where lea.datetime>'" . $range . "'
    group by(le_fi.id)";
    $res=$con->query($sql);
    $result = array();
    while ($row = $res->fetch_assoc()) {
      //if($row['count']<4) $result[] = $row;
      $result[] = $row;
    }
    return $result;
  }
}