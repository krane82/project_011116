<?php
class Model_Admin extends Model {

  public function get_data() {
    $loginUser = array(
      "login_status" => 1
    );
    return $loginUser;
  }

  public function getMonthlyStats(){
    $spent = array();
    $sold  = array();
    $con   = $this->db();
    $year = date("Y");
    $jan = strtotime(date('Y-01-01'));
    $dec = strtotime('last day of ' . date( 'F Y')) + 86399;

//    echo "$jan : $dec";

    $sql1  = 'SELECT MONTH(FROM_UNIXTIME(ld.timedate)) as month, SUM(c.lead_cost) as cost, GROUP_CONCAT(ld.id) FROM `leads_delivery` as ld';
    $sql1 .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql1 .= ' WHERE (lr.approval > 0 OR lr.approval IS NULL)';
    $sql1 .= " AND (ld.timedate BETWEEN $jan AND $dec)";
    $sql1 .= " GROUP BY month";
    //    $sql1 .= ' AND YEAR(FROM_UNIXTIME(ld.timedate)) =' .$year ;

    $sql2  = 'SELECT MONTH(FROM_UNIXTIME(l.datetime)) as month, SUM(c.cost) as cost, GROUP_CONCAT(l.id) FROM `leads` as l ';
    $sql2 .= ' INNER JOIN `campaigns` as c ON c.id = l.campaign_id ';
    $sql2 .= " WHERE l.datetime BETWEEN $jan AND $dec";
    $sql2 .= ' GROUP BY month';
//    $sql2 .= ' WHERE YEAR(FROM_UNIXTIME(l.datetime)) = ' . $year;

    $res1 = $con->query($sql1);
    if($res1){
      while($row = $res1->fetch_assoc()){
        $sold[] = $row;
      }
    }


    $res2 = $con->query($sql2);
    if($res2){
      while($row = $res2->fetch_assoc()){
//        print_r($resul1);
        $spent[] = $row;
      }
    }
//    var_dump($spent); exit;

    $months = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

    for($i=1; $i<13; $i++){
      $key = false;
      foreach ($spent as $k=>$v) {
        if((int)$v["month"] === $i){
          $key = $k;
          $month = (int)$v["month"];
        }
      }
      if($month == $i){
        $array[] = array(
          'm' => $months[$i],
          'a'=> $spent[$key]["cost"],
          'b'=> $sold[$key]["cost"],
          'c'=> $sold[$key]["cost"] - $spent[$key]["cost"]
        );
      } else {
        $array[] = array(
          'm' => $months[$i],
          'a'=> 0,
          'b'=> 0,
          'c'=> 0
        );
      }
    }
    return $array;
  }
  public function DistributionOrder(){
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

    $sql =  'SELECT c.campaign_name as client, IFNULL(c.lead_cost,0) as lead_cost, IF(COUNT(*)=0,0,SUM(IF(lr.approval=0,1,0))/COUNT(*)) as percentage, ((COUNT(*) - SUM(IF(lr.approval=0,1,0)))*c.lead_cost) as revenue FROM `leads_delivery` as ld';
    $sql .= ' INNER JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id INNER JOIN clients as c ON ld.client_id=c.id';
    $sql .= ' WHERE `ld`.`timedate` BETWEEN '.$start.' AND '.$end.'';
    $sql .= ' GROUP BY ld.client_id';
    $sql .= ' ORDER BY revenue DESC, percentage ASC, lead_cost DESC';



    $con  = $this->db();
    $data = array();



    if($res = $con->query($sql)){
      while($result = $res->fetch_assoc()){
        $data[] = $result;
      }
      return $data;
    }
    return FALSE;
  }
}
