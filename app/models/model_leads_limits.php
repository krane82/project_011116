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
    $sql="select c.id, c.campaign_name, cri.weekly, (cri.weekly*4) as monthly, count(ld.timedate) as thisMonth
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
    while($row1=$res1->fetch_assoc())
    {
        $result[$row1['id']]['thisWeek']=(int)$row1['thisWeek'];
    }
return $result;
}
}
