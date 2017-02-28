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
<<<<<<< HEAD
	$sql="SELECT le.state, le.postcode, COUNT( led.id ) 
FROM  `leads_lead_fields_rel` AS le
LEFT JOIN leads_delivery led ON le.id = led.lead_id
=======
    $beginOfMonth = strtotime(date('Y-m-01'));
	$sql="SELECT le.state, le.postcode, COUNT( led.id ) 
FROM  `leads_lead_fields_rel` AS le
LEFT JOIN leads_delivery led ON le.id = led.lead_id where led.timedate>'".$beginOfMonth."'
>>>>>>> 29d32cfdc8603ffe90272d1ab805182dd795e1a8
GROUP BY le.id
ORDER BY le.postcode";
    $result=array();
    $res=$con->query($sql);
    while($row=$res->fetch_assoc())
    {
	$result[$row['state']][$row['COUNT( led.id )']]['count']++;
    $result[$row['state']][$row['COUNT( led.id )']]['codes'][$row['postcode']]++;
	}
return $result;
}
public function getCodeAjax()
{
    $start = strtotime($_POST["st"]);
    $end = strtotime($_POST["en"]) + 86400;
	//$now = time();
	//if ($start<1485907200) $start=1485907200;
	$con = $this->db();
	$sql="SELECT le.state, le.postcode, COUNT( led.id ) 
FROM  `leads_lead_fields_rel` AS le
LEFT JOIN leads_delivery led ON le.id = led.lead_id WHERE led.timedate between '".$start."' and '".$end."' 
GROUP BY le.id
ORDER BY le.postcode";
    $result=array();
    $res=$con->query($sql);
    while($row=$res->fetch_assoc())
    {
	$result[$row['state']][$row['COUNT( led.id )']]['count']++;
    $result[$row['state']][$row['COUNT( led.id )']]['codes'][$row['postcode']]++;
	}
return $result;
}
}
