<?php
class Model_Rerouting extends Model {
private $api;
function __construct($api)
{
  $this->api = $api;
}

private function getLeadInfo($id)
{
  $con = $this->db();
  $sql = "SELECT * FROM `leads_lead_fields_rel` WHERE `id` IN ($id)";
  $res = $con->query($sql);
  $info = array();
  if($res){
    while($all = $res->fetch_assoc()){
      $info[] = $all;
    }
    // $r = $res->fetch_assoc();
    return $info;
  } else
  return "Lead not found";
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

public function leadall()
{

  date_default_timezone_set('UTC'); //---убрать
if(isset($_POST)){
  $amount = $_POST['tes'];
  $start = $_POST['start'];
  $end = $_POST['end'];
  $id_cli = $_POST['id_cli'];
  $start = strtotime($start);
  $end = strtotime($end);
}
  $date = date('Y-m-d');
  $date = strtotime($date);
  $sqlamount = "SELECT `amount` FROM `leads_delivery`";
  $sqlamount .= "WHERE client_id='$id_cli' AND timedate='$date'";
  $con = $this->db();
  $amounts = $con->query($sqlamount);
  if($amounts->num_rows>0){

  $amountdb = $amounts->fetch_assoc();
 }else{
  $amountdb['amount'] = '0';
 }

  $sqllim = "SELECT count(*) as lim FROM `leads_delivery`";
  $sqllim .= "WHERE client_id='$id_cli' AND timedate='$date'";
  $con = $this->db();
  $countlim = $con->query($sqllim);
  $countlim = $countlim->fetch_assoc();

  if($countlim['lim']>=$amountdb['amount'] && $amountdb['amount'] != 0 ){
    return "Sorry, but sending disabilities limit for today";
  }

 
  $sqlcode = "SELECT `postcodes` FROM `clients_criteria`";
  $sqlcode .= "WHERE id='$id_cli'";
  $con = $this->db();
  $rescode = $con->query($sqlcode);

  $code = $rescode->fetch_assoc();
  if(empty($code["postcodes"])){
    return 'Postcodes is empty';    
  }
  if(substr($code["postcodes"], -1) == ','){
    $codepost = substr($code["postcodes"], 0, -1);
  }else{
    $codepost = $code["postcodes"];
  }
 
  $sql = "SELECT `lead_id` FROM `leads_delivery`";
  $sql .= " WHERE 1=1 AND (timedate BETWEEN ".$start." AND ".$end.") AND lead_id NOT IN (SELECT `lead_id` FROM `leads_delivery` WHERE client_id='$id_cli')";
  $sql .= " group by `lead_id` having count(*) < 4";
  $con = $this->db();
  $res = $con->query($sql);
  if($res->num_rows<1){
    return 'Not matching ID';
  }
  
  $lead = array();
  while($result = $res->fetch_assoc()){
    $lead[] = $result;
  }

  foreach($lead as $id){
    $all_id .= $id['lead_id'].', '; 
  }

  $all_id = substr($all_id, 0, -2);

  // $data[] = $all_id;

  $sqlid = "SELECT `id` FROM `leads_lead_fields_rel`";
  $sqlid .= "WHERE id IN ($all_id) AND postcode IN ($codepost)";
  $con = $this->db();
  $rescode = $con->query($sqlid);
  
  if($rescode->num_rows<1){
    return 'Not matching';
  }
  $id_lead_code = array();
  while($rescod = $rescode->fetch_assoc()){
    $id_lead_code[] = $rescod;
  }
  var_dump($id_lead_code);
  if(count($id_lead_code)>$amount){
    $difference = $amount - $countlim['lim'];

    $queue = $id_lead_code;

 
    $id_lead_code = array_chunk($id_lead_code, $difference);

    
    // $queue = $id_lead_code;
       $queue = array_splice($queue, $difference);

    // unset($queue[0]);
var_dump($amount);
var_dump(count($id_lead_code));
exit('777');

    foreach($queue as $id_que){
      $que_id_lead = $id_que['id'];
  
      // foreach($id_que as $val){

      //   $que_id_lead = $val['id'];
    
      $sql_queue = "INSERT INTO `queue`(id_lead, id_client, timedata, status) VALUES ($que_id_lead, $id_cli, $date, 1)";
  
        $con = $this->db();
        $res_queue = $con->query($sql_queue);
      // }
    }

     // $que_id_lead = substr($que_id_lead, 0, -2);
    //$que_id_lead ---- id лидов в очередь

    $id_lead_code = array_slice($id_lead_code, 0, true);
   
  }else{
// если уже были какие то отправлены
    $difference = $amount - $countlim['lim'];
   var_dump($amount);
exit('887');
    if(count($id_lead_code)>$difference){
      $diff = count($id_lead_code) - $difference;
      $id_lead_code = array_chunk($id_lead_code, $dif);
      $queue = $id_lead_code;
      unset($queue[0]);
    foreach($queue as $id_que){
      foreach($id_que as $val){
        $que_id_lead = $val['id'];
        $sql_queue = "INSERT INTO `queue`(id_lead, id_client, timedata, status) VALUES ($que_id_lead, $id_cli, $date, 1)";
        // $con = $this->db();
        // $res_queue = $con->query($sql_queue);
      }
    }

      $id_lead_code = array_slice($id_lead_code, 0, true);

    }else{

      // $diff = $difference - count($id_lead_code);
     
      // $id_lead_code = array_chunk($id_lead_code, $diff);
      
      // $queue = $id_lead_code;
     
      // var_dump($diff);
      // exit();
      // foreach($queue as $id_que){
      //   foreach($id_que as $val){
      //     $que_id_lead = $val['id'];
      //     $sql_queue = "INSERT INTO `queue`(id_lead, id_client, timedata, status) VALUES ($que_id_lead, $id_cli, $date, 1)";
        
      //     $con = $this->db();
      //     $res_queue = $con->query($sql_queue);
      //   }
      // }


      // $id_lead_code = array_slice($id_lead_code, 0, true);

    }
    
  }


 
  foreach($id_lead_code as $id_lead){ 
   
    foreach($id_lead as $id_leads){

    $id_leads_code .= $id_leads['id'].', '; 
    }
  }

  $id_leads_code = substr($id_leads_code, 0, -2);

   

  $sqlclient = "SELECT `email`, `full_name` FROM `clients`";
  $sqlclient .= "WHERE id='$id_cli'";
  $con = $this->db();
  $res_cli = $con->query($sqlclient);
  $res_client = $res_cli->fetch_assoc();



// $id_cli --- id выбранного клиента
// $id_lead_code --- id лидов которые совпадают по зип кодам выбранные по временным рамкам



  //API

  // private function sendToClients($clients, $lead_id ,$p){
    // $counter = 0;
    // foreach ($clients as $c ) {
        $id = $id_cli;
        $passedcaps = $this->checkClientsLimits($id);
        $leadinf = $this->getLeadInfo($id_leads_code);
        // $p['source'] = substr($p['sourse'], 0, -2);
        // $readyLeadInfo = prepareLeadInfo($p);
        $readyLeadInfo = $leadinf;
        
      $sended = $this->sendToClient($res_client["email"], $readyLeadInfo, $res_client["full_name"]);

      $id_leads_code = explode(',', $id_leads_code);
      $res = count($id_leads_code);
  // echo "<pre>";
  // var_dump($id_leads_code);
  // exit();
     
        if($sended) {
          // $counter++;

         $this->addToDeliveredTable($id, $id_leads_code, $readyLeadInfo, $amount);
        
        }
   
      // }
    // }
    return "Sent ".$res." leads to client";
  // }


}


  public function addToDeliveredTable($id, $lead_id, $p, $amount){

    $con = $this->db();
    $now = time();
// var_dump($lead_id);

//     $lead_id = explode(',', $lead_id);
  if(count($lead_id)>1){
  foreach($lead_id as $id_leads){

    $sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate, amount) VALUES ($id_leads, $id, $now, $amount)";
    $sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval) VALUES ($id_leads, $id, $now, 1)";


    if($con->query($sql) && $con->query($sql_r)) { $delivered=1; }
  }
 
    if($delivered){
      return TRUE;
    } else {
      return FALSE;
    }
}else{
    $lead_id = $lead_id[0];
    $sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate, amount) VALUES ($lead_id, $id, $now, $amount)";
    $sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval) VALUES ($lead_id, $id, $now, 1)";
   
}
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
//      send_m($mail, $p, $client_name);
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

  public function getClients($post){
    $clients = array();
    $con = $this->db();
    if( !empty($post["state"]) || !empty($post["postcode"]) ){
      $sql = 'SELECT cc.id, c.email, c.full_name';
      $sql.= ' FROM `clients_criteria` as cc';
      $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
      if(!empty($post["state"]) AND !empty($post["postcode"])) {
        $sql .= ' WHERE ( cc.states_filter LIKE "%' . $post["state"] . '%" OR cc.postcodes LIKE "%'.$post["postcode"].'%" )';
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


    // LOGS
    #start buffering (all activity to the buffer)
    ob_start() ;
    $fileName = date("Y-m-d-h-i-s");
    $fileName .= "log.html";

    $myfile = fopen($fileName, "w");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>LOG</title>
    </head>
    <body>
    


    <?php
                                                
    echo "CLIENTS : \n";
    var_dump($clients);
    echo "===========\n";
    echo "===========POST===========\n";
    var_dump($_POST);
    echo "\n=================\n";

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

    echo  "\n===============$order================\n";
    var_dump($order);
    echo  "\n================================\n";

    echo  "\n======CLIENTS SORTED===========\n";
    var_dump($clients);
    echo  "\n================================\n";
    echo "</body></html>";


    # dump buffered $classvar to $outStringVar
    $outStringVar = ob_get_contents();

    fwrite($myfile, $outStringVar);
    fclose($myfile);
    # clean the buffer & stop buffering output
    ob_end_clean() ;
    // END LOGS

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


}
