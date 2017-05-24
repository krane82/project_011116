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
  public function getConvForLead()
  {
    $con=$this->db();
    //return var_dump($_POST);
    $leadId=$_POST['lead_id'];
	$author=$_SESSION['user_id'];
    $sql="SELECT us.full_name, con.id, con.author, con.message, con.seen, con.time FROM lead_conversations con JOIN users us ON con.author=us.id WHERE con.lead_id='$leadId' ORDER BY con.time";
    $result=array();
    $res=$con->query($sql);                  
    if($res)
    {
      while($row=$res->fetch_assoc())
      {
        $result['conversations'][]=$row;
      }
      $whoSeen=explode(',',$result['conversations'][0]['seen']);
      if(!in_array($author,$whoSeen)) {
        $ins = $result['conversations'][0]['id'];
        $sql1 = "UPDATE `lead_conversations` SET `seen`=CONCAT(`seen`,'$author,') WHERE id='$ins'";
        $con->query($sql1);
      }
        $result['leadid']=$leadId;
	  $result['author']=$author;
      return $result;
    }
    return false;
  }
  public function addConv()
  {
	  $con=$this->db();
	  $leadId=$_POST['leadId'];
	  $userId=$_POST['userId'];
	  $message=$_POST['message'];
	  $message=$this->clean($message);
	  $sql="INSERT INTO lead_conversations (lead_id, author, message, seen) VALUES ('$leadId', '$userId', '$message','$userId,')";
	$res=$con->query($sql);
	$sql1="SELECT id FROM lead_conversations WHERE lead_id='$leadId' LIMIT 1";
    $res1=$con->query($sql1);
    $result=$res1->fetch_assoc();
    $ins=$result['id'];
    if($ins)
    {
      $con->query("UPDATE lead_conversations SET seen='$userId,' WHERE id='$ins'");
    }
    if($res)
	{
		return true;
	}
	return false;
  }

  public function sendLeadsFromCSV()
 {
   $api=new Model_Api();
   $arr=$_POST['array'];
   $arr=urldecode($arr);
   $arr=unserialize($arr);
   $str='';
   foreach($arr as $item)
   {
     $str.=$api->proccess_lead($item).'<br>';
   }
   return $str;
 }
  private function getLeadInfo($id)
  {
    $con = $this->db();
    $sql = "SELECT * FROM leads_lead_fields_rel WHERE id='".$id."'";
    $res = $con->query($sql);
    if($res) {
      if ($r = $res->fetch_assoc()) return $r;
      return false;
    }
  }
  private function getClientById($id)
  {
    $con = $this->db();
    $sql = 'SELECT cc.id, c.email, c.full_name, cc.postcodes';
    $sql.= ' FROM `clients_criteria` as cc';
    $sql.= ' LEFT JOIN `clients` as c ON cc.id = c.id';
    $sql.= " WHERE cc.id=$id";
    $res = $con->query($sql);
    //return $sql;
    if ($res) {
      $client = $res->fetch_assoc();
    } else {
      return 'Db error when trying to get client email';
    }
    return $client;
  }
  public function senManyLeads($client_id, $start, $end, $state, $source)
  {
    $data=$this->getList($start, $end, $state, $source);
    $i=0;
    if($client_id!=0) {
      $c = $this->getClientById($client_id);
      foreach ($data as $lead_id) {
        if ($x = $this->senLeadToCurrent($client_id, $lead_id,$c)) print $x . '<br>';
        else {
          print 'Error!';
        }
        $i++;
        usleep(250000);
      }
    }
    else {
      foreach ($data as $lead_id) {
        if ($x = $this->senLeadToAll($lead_id)
        ) print $x . '<br>';
        else {
          print 'error!';
        }
        $i++;
        usleep(250000);
      }
    }
    print $i.' Leads done';
  }
  public function senOneLead($client_id,$lead_id)
  {
    if($client_id!=0) {
      $c = $this->getClientById($client_id);
      if ($x = $this->senLeadToCurrent($client_id, $lead_id, $c)) print $x;
      else {
        print 'Error!';
      }
    } else {
      if ($x = $this->senLeadToAll($lead_id)) print $x;
      else {
        print 'Error!';
      }
    }
  }
  private function senLeadToCurrent($client_id, $lead_id, $c)
  {
    $receivers=$this->getLeadFromDelivered($lead_id);
    $counter = count($receivers);
    if($counter>=4) return 'This lead already sent 4 times';
    $leadInfo = $this->getLeadInfo($lead_id);
    $postcodes=explode(',',$c['postcodes']);
    if (!in_array($leadInfo['postcode'],$postcodes)) return 'This client is unmatched to receive this lead';
    if(in_array($client_id, $receivers )) return "This client already has this lead";
      $readyLeadInfo = prepareLeadInfo($leadInfo);
      $passedCaps = $this->api->checkClientsLimits($client_id);
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
  private function senLeadToAll($lead_id)
  {
    $receivers=$this->getLeadFromDelivered($lead_id);
    $counter = count($receivers);
    if($counter>=4) return 'This lead already sent 4 times';
    $leadInfo = $this->getLeadInfo($lead_id);
    $state=$leadInfo['state'];
    if(!$clients = $this->api->getClients($leadInfo)) return "No clients matches for this lead, or they are inactive";
    $result = $this->sendToClients($clients, $lead_id, $leadInfo);
    return $result;
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

  private function getList($start, $end, $state=false, $source=false)
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
    $readyLeadInfo = prepareLeadInfo($p);
    $delivery_id = $this->getLastDeliveryID();
    $sentTo = '';
    foreach ($clients as $c) {
      $id = $c["id"];
      if(in_array($id,$receivers))
      {
        continue;
      }
      $passedCaps = $this->checkClientsLimits($id);
      if($passedCaps) {
        $sent = $this->sendToClient($c["email"], $readyLeadInfo, $c["full_name"],$delivery_id);
        if($sent) {
          $counter++;
          $sentTo .= "Lead #$lead_id sent to $c[full_name] : $c[email]<br>\n";
          $this->api->addToDeliveredTable($id, $lead_id, $readyLeadInfo);
          $delivery_id+=1;
          return 'Lead sent and added to database';
        }
        return 'for some reason lead can not be sent';
      }
      return 'Out of clients caps';
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
  public function checkClientsLimits($id)
  {
    $con = $this->db();
    $monday = strtotime("Monday this week");
    $sql="select count(led.id), cc.weekly from `leads_delivery` as led right join clients_criteria cc on cc.id=led.client_id where cc.id = '".$id."' AND led.timedate BETWEEN '".$monday."' AND current_timestamp";
    $res=$con->query($sql);
    $result=$res->fetch_assoc();
    if ($result['weekly']==null) {
      $result["weekly"] = 999999999;
    }
    $con->close();
    if ($result["count(led.id)"] < $result["weekly"]) {
      return $id;
    } else {
      echo "weekly not passed!";
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
      $sql .= ' WHERE cc.states_filter LIKE "%' . $post["state"] . '%" AND cc.postcodes LIKE "%'.$post["postcode"].'%"';
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
  private function clean($item)
  {
	  $item=htmlspecialchars($item);
	  $item=addslashes($item);
	  $item=trim($item);
	  return $item;
  }
}
?>