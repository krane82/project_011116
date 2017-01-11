<?php
class Model_Admin_Reports extends Model
{

  public function getLeadSources()
  {
    $con = $this->db();
    $LeadSources = array();
    $sql = "SELECT `source`, `name` FROM `campaigns`";
    $res = $con->query($sql);
    while ($row = $res->fetch_assoc()) {
      $LeadSources[] = $row;
    }
    $con->close();
    return $LeadSources;
  }

  public function getClients()
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

  public function getRejected()
  {
    $con = $this->db();
    $client = $_REQUEST["client"];
    $start = strtotime($_REQUEST["start"]);
    $end = strtotime($_REQUEST["end"]) + 86400;
    $timestamp = time();
    if(empty($_REQUEST["start"])){
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }
    // get approved leads and sum
    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y" ) AS `Date`, lr.reason as `Rejection reason`,';
    $sql .= ' `lf`.`address`, `lf`.`city`,  `lf`.`state`, `lf`.`postcode`, `lf`.`suburb`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';
    $sql .= ' FROM `leads_delivery` as ld ';
    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' INNER JOIN `leads_lead_fields_rel` as lf ON lf.id = lr.lead_id';
    $sql .= ' WHERE lr.approval = 0';
    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if (!($client == 0)) {
      $sql .= ' AND ld.client_id =' . $client;
    }
    $res = $con->query($sql);
    $approved = array();
    if ($res) {
      $col = $res->fetch_assoc();
      $prearr = array();
      foreach($col as $k=>$v){
        $prearr[] = $k;
      }
      $approved[] = $prearr;
      $approved[] = $col;
      while($line = $res->fetch_assoc()){
        $approved[] = $line;
      }
    } else {
      echo "No data\n";
    }
    return $approved;
  }

  public function getReceived()
  {

    $source = $_REQUEST["source"];
    $state = $_REQUEST["State"];
    $start = strtotime($_REQUEST["start"]);
    $end = strtotime($_REQUEST["end"]) + 86400;
    $campaign_id = getCampaignID($source);
    $timestamp = time();
    if(empty($_REQUEST["start"])){
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }
    $data = "(`l`.`datetime` BETWEEN $start AND $end)";
    $con = $this->db();

    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`l`.`datetime`), "%e %b %Y" ) AS `Date`, c.name as `Campaign`,';
    $sql .= ' `lf`.`address`, `lf`.`city`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`suburb`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';
    $sql .= ' FROM `leads` as l ';
    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=l.id';
    $sql .= ' LEFT JOIN  `campaigns` as c ON c.id = l.campaign_id';
    $sql .= ' WHERE 1=1';
    $sql .= ' AND '. $data;
    if ($campaign_id) {
      $sql.= ' AND l.campaign_id = '.$campaign_id;
    }
    if ($state){
      $sql .= " AND lf.state = '$state'";
    }

    $res = $con->query($sql);
    $received = array();
    if ($res) {
      $col = $res->fetch_assoc();
      $prearr = array();
      foreach($col as $k=>$v){
        $prearr[] = $k;
      }
      $received[] = $prearr;
      $received[] = $col;
      while($line = $res->fetch_assoc()){
        $received[] = $line;
      }
    } else {
      echo "No data\n";
    }
    $con->close();
    return $received;
  }

  public function getAccepted()
  {
    $con = $this->db();
    $client = $_REQUEST["client"];
    $start = strtotime($_REQUEST["start"]);
    $end = strtotime($_REQUEST["end"]) + 86400;
    $timestamp = time();
    if(empty($_REQUEST["start"])){
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }
    // get approved leads and sum
    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y" ) AS `Date`, c.campaign_name as `Client Name`, c.email as `Client Email`, ';
    $sql .= ' `lf`.`address`, `lf`.`city`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`suburb`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';
    $sql .= ' FROM `leads_delivery` as ld ';
    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' INNER JOIN `leads_lead_fields_rel` as lf ON lf.id = lr.lead_id';
    $sql .= ' WHERE (lr.approval > 0 OR lr.approval IS NULL)';
    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if (!($client == 0)) {
      $sql .= ' AND ld.client_id =' . $client;
    }
    $res = $con->query($sql);
    $approved = array();
    if ($res) {
      $col = $res->fetch_assoc();
      $prearr = array();
      foreach($col as $k=>$v){
        $prearr[] = $k;
      }
      $approved[] = $prearr;
      $approved[] = $col;
      while($line = $res->fetch_assoc()){
        $approved[] = $line;
      }
    } else {
      echo "No data\n";
    }
    return $approved;
  }

  public function getDistributed()
  {
    $con = $this->db();
    $client = $_REQUEST["client"];
    $start = strtotime($_REQUEST["start"]);
    $end = strtotime($_REQUEST["end"]) + 86400;
    $timestamp = time();
    if(empty($_REQUEST["start"])){
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }

    $sql = 'SELECT lf.id as `id`, lf.full_name as `Full name`, lf.email, lf.phone, DATE_FORMAT(FROM_UNIXTIME(`ld`.`timedate`), "%e %b %Y" ) AS `Date`, c.campaign_name as `Client Name`,';
    $sql .= ' `lf`.`address`, `lf`.`city`,  `lf`.`state`,  `lf`.`postcode`, `lf`.`suburb`, `lf`.`system_size`, `lf`.`roof_type`, `lf`.`electricity`, `lf`.`house_age`, `lf`.`house_type`, `lf`.`system_for`, `lf`.`note`';
    $sql .= 'FROM `leads_delivery` as ld ';
    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' INNER JOIN `leads_lead_fields_rel` as lf ON lf.id = ld.lead_id';
    $sql .= ' WHERE 1=1';
    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if (!($client == 0)) {
      $sql .= ' AND ld.client_id =' . $client;
    }
    $res = $con->query($sql);
    $distributed = array();
    if ($res) {
      $col = $res->fetch_assoc();
      $prearr = array();
      foreach($col as $k=>$v){
        $prearr[] = $k;
      }

      $distributed[] = $prearr;
      $distributed[] = $col;

      while($line = $res->fetch_assoc()){
        $distributed[] = $line;
      }
    } else {
      echo "No data\n";
    }
    return $distributed;
  }



  private function formStatView($string, $icon_class, $fn='fncsv', $color='')
  {
    $v = '  
      <div class="col-md-2 '. $color .'">
        <div onclick="'.$fn.'()" class="panel panel-white pdten" style="cursor: pointer;">
      <i class="fa fa-'.$icon_class.' icon" aria-hidden="true"></i>' . $string .'
      </div> </div>';
    return $v;
  }


  public function getAverageReports()
  {
    $con = $this->db();
    $client = $_POST["client"];
    if(!empty($_POST["start"])){
      $start = strtotime($_POST["start"]);
      $end = strtotime($_POST["end"]) + 86400;
    }
    else {
      $timestamp = time();
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }

    // get approved leads and sum
    $sql = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as total_cost  FROM `leads_delivery` as ld ';
    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' WHERE (lr.approval > 0 OR lr.approval IS NULL)';
    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if (!($client == 0)) {
      $sql .= ' AND ld.client_id =' . $client;
    }

    $res = $con->query($sql);
    $approved = array();

    if ($res) {
      $approved = $res->fetch_assoc();
    } else {
      echo "No data\n";
    }

    $sql2 = 'SELECT COUNT(*) as amount FROM `leads_delivery` as ld ';
    $sql2 .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql2 .= ' WHERE lr.approval=0';
    $sql2 .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if ($client != 0) {
      $sql2 .= ' AND ld.client_id =' . $client;
    }
    $res = $con->query($sql2);
    if ($res) {
      $rejected = $res->fetch_assoc();
    } else {
      echo "0";
    }
//  DISTINCT
    $sqlDestributed  = 'SELECT COUNT(ld.lead_id) as amount, SUM(c.cost) as camp_cost FROM `leads_delivery` as ld';
    $sqlDestributed .= ' INNER JOIN `leads` as l ON l.id=ld.lead_id';
    $sqlDestributed .= ' INNER JOIN `campaigns` as c ON c.id = l.campaign_id';
    $sqlDestributed .= ' WHERE 1=1 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if (!($client == 0)) {
      $sqlDestributed .= ' AND ld.client_id =' . $client;
    }

    $res = $con->query($sqlDestributed);
    if($res){
      $distributed = $res->fetch_assoc();
    }

    $rejectedP = $rejected["amount"] / $distributed["amount"];
    $ds =  $distributed["amount"] . " leads <br>Distributed";
    $acs = $approved['amount']. " leads Accepted by clients";
    $ras = $rejected["amount"] . " leads Rejected <br>by clients";
    $trs = $approved["total_cost"] . " total Revenue";
    $rejectedPercent =  number_format($rejectedP * 100, 0) . '% Rejected<br> by clients';
    $rev = $approved["total_cost"] ? $approved["total_cost"] . " $<br>Lead Revenue" : 0 . " $<br>Lead Revenue";
    echo $this->formStatView($ds, 'users', 'getDistributed');
    echo $this->formStatView($acs, 'check', 'getAccepted');
    echo $this->formStatView($ras, 'window-close', 'getRejected');
    echo $this->formStatView($rejectedPercent, 'window-close', 'getRejected');
    echo $this->formStatView($rev, 'shopping-cart');
  }

  public function getSourceAverage()
  {
    $state = $_REQUEST["State"];
    $source = $_POST["source"];
    $start = strtotime($_POST["start"]);
    $end = strtotime($_POST["end"]) + 86400;
    $campaign_id = getCampaignID($source);
    $data = "(`l`.`datetime` BETWEEN $start AND $end)";

    $con = $this->db();
    $sql = 'SELECT SUM(c.cost) as total_cost, COUNT(c.id) as amount, lf.state as state FROM `leads` as l';
    $sql .= ' LEFT JOIN `campaigns` as c ON l.campaign_id = c.id';
    $sql .= ' LEFT JOIN `leads_lead_fields_rel` as lf ON lf.id=l.id';
    $sql .= ' WHERE 1=1';
    $sql .= ' AND '. $data;
    if ($campaign_id){
      $sql.= ' AND l.campaign_id = '.$campaign_id;
    }
    if ($state){
      $sql .= " AND lf.state = '$state'";
    }
    $res = $con->query($sql);
    if($res){
      $d = $res->fetch_assoc();
    }
    if($d) {
      $amount = $d["amount"] . " <br>Leads Received";
      $cost = $d["total_cost"] . "$ <br>Total Leads Cost";
      echo $this->formStatView($amount, 'users', 'getReceived');
      echo $this->formStatView($cost, 'shopping-cart', 'getReceived');
    } else {
      echo "No data for this ctriteria.";
    }
  }
}

