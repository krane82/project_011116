<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2017
 * Time: 10:55
 */
class Model_penetration extends Model
{
public function getCode()
{
	$con = $this->db();
    $beginOfMonth = strtotime(date('Y-m-01'));
	$sql="select le.id, le.state, le.postcode, 
	count(led.id) from leads_lead_fields_rel le LEFT JOIN leads lea 
	on le.id=lea.id LEFT JOIN leads_delivery led on le.id=led.lead_id 
	where lea.datetime>'".$beginOfMonth."'
GROUP BY le.id";
    $result=array();
    $res=$con->query($sql);
    while($row=$res->fetch_assoc())
    {
	$result[$row['state']][$row['count(led.id)']]['count']++;
    $result[$row['state']][$row['count(led.id)']]['codes'][$row['postcode']]++;
	}
return $result;
}
public function getCodeAjax($user=false)
{
    $start = ($_POST["st"])?strtotime($_POST["st"]):strtotime(date('Y-m-01'));
    $end = ($_POST["en"])?strtotime($_POST["en"]) + 86400:time();
//    $start = strtotime($_POST["st"]);
//    $end = strtotime($_POST["en"]) + 86400;
	$con = $this->db();
	$sql="select le.id, le.state, le.postcode, 
	count(led.id) from leads_lead_fields_rel le LEFT JOIN leads lea 
	on le.id=lea.id LEFT JOIN leads_delivery led on le.id=led.lead_id 
	where lea.datetime between '".$start."' and '".$end."'";
    if($user) $sql.=" and lea.campaign_id='".$user."'";
    $sql.=" GROUP BY le.id";
    $result=array();
    $res=$con->query($sql);
   // if ($res) {
        while ($row = $res->fetch_assoc()) {
            $result[$row['state']][$row['count(led.id)']]['count']++;
            $result[$row['state']][$row['count(led.id)']]['codes'][$row['postcode']]++;
        }
  //  }
return $result;
}
}
