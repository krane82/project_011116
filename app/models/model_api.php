<?php
class Model_Api extends Model {

  public function proccess_lead($post) {
    $p = $this->checkdata($post);
    $lead_id = $this->addleadtotable($p);
    if(!$lead_id) {
      return FALSE;
    }
    $clients = $this->getClients($p);
    $this->sendToClients($clients, $lead_id, $p);
  }


  private function sendToClients($clients, $lead_id ,$p){
    $counter = 0;
    foreach ($clients as $c ) {
      $id = $c["id"];
      $passedcaps = $this->checkClientsLimits($id);
      if($passedcaps AND $counter < 4) {
        $readyLeadInfo = prepareLeadInfo($p);
        $sended = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"]);
        if($sended) {
          $counter++;
          $this->addToDeliveredTable($id, $lead_id, $readyLeadInfo);
        }
      }
    }
  }


  private function addToDeliveredTable($id, $lead_id, $p){
    $con = $this->db();
    $now = time();
    $sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate) VALUES ($lead_id, $id, $now)";
    $sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval) VALUES ($lead_id, $id, $now, 1)";
    if($con->query($sql) && $con->query($sql_r)) { $delivered=1; }
    if($delivered){
      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function sendToClient($mail, $p, $client_name)
  {
    if($mail) {
      send_m($mail, $p, $client_name);
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

    // echo "<pre>";
    // var_dump($clients);
    // echo "</pre>";

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


    // $order = [24, 62, 60];

    if(count($order)){

      usort($clients, function ($a, $b) use ($order) {
        $pos_a = array_search($a['id'], $order);
        $pos_b = array_search($b['id'], $order);
        return $pos_a - $pos_b;
      });

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

    // echo "<h3>Order list</h2><pre>";
    // print_r($order);
    // echo "</pre>";

    // echo "<h3>Clients sorted</h2><pre>";
    // var_dump($clients);
    // echo "</pre>";
    // exit();
    }

    // Returnig ordered clients
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

}
