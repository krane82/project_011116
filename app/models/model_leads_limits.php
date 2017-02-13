<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2017
 * Time: 10:55
 */
class Model_Leads_Limits extends Model
{
public function getLimits()
{
    $con = $this->db();
    $beginOfMonth = strtotime(date('Y-m-01'));
    $Monday = strtotime("Monday this week");
    $Now = time();
    $sql="select c.id, c.campaign_name, cri.weekly, cri.monthly, count(ld.timedate) as thisMonth
 from clients c
left join clients_criteria cri on c.id=cri.id left join leads_delivery ld on c.id=ld.client_id
 and ld.timedate > '".$beginOfMonth."' group by c.campaign_name";
    $sql1="select c.id, count(lw.timedate) as thisWeek from clients c left join leads_delivery lw on c.id=lw.client_id and lw.timedate > '".$Monday."' group by c.id";
    $result=array();
    $res=$con->query($sql);
    $res1=$con->query($sql1);
    while($row=$res->fetch_assoc())
    {
        $result[$row['id']]=$row;
    }
    //return $sql1;
    while($row1=$res1->fetch_assoc())
    {
        $result[$row1['id']]['thisWeek']=(int)$row1['thisWeek'];
    }
return $result;
}
public function getMaches($begin=false,$end=false)
{
    $con = $this->db();
    $sql="SELECT le.id, le.postcode, cli.campaign_name FROM leads_lead_fields_rel as le LEFT JOIN leads_delivery as led on le.id=led.lead_id LEFT JOIN clients cli on led.client_id=cli.id";
    $res=$con->query($sql);
    $result=array();
    while($row=$res->fetch_assoc())
    {
        $result[$row['id']]['count']++;
        $result[$row['id']]['postcode']=$row['postcode'];
        if($row['campaign_name'])$result[$row['id']]['client'].=' '.$row['campaign_name'].',';
    }
    return $result;
}

}