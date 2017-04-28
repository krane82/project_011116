<?php
class Model_Clients extends Model {
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
  public function smsSend()
  {
    $to=$_POST['client'];
    $to="+380500109092";
    $to="+380995591095";
    $message=$_POST['message'];
    $method="http";
    $username="krane";
    $key="37AC0D4A-0732-21C8-DA67-FB3970512CA9";
    $myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'https://api-mapper.clicksend.com/http/v2/send.php',
        //CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array("to"=>$to,"message"=>$message,"method"=>$method,"username"=>$username,"key"=>$key))
    ));
    $response = curl_exec($myCurl);
    curl_close($myCurl);
    return $response;
  }
  public function get_data() {
    $table = "<table id='clients' class=\"display table table-condensed table-striped table-hover table-bordered clients responsive pull-left\">";
      $table .= "<thead><tr><th>ID</th><th>Campaign name</th><th>Email</th><th>Full Name</th><th>Lead Cost</th><th>Delivery</th><th>Status</th><th>Actions</th></tr></thead>";
      $table .= "</table>";
      return $table;
  }
  public function getCoords()
  {
    $con = $this->db();
    $sql="SELECT cri.coords FROM clients cli LEFT JOIN clients_criteria cri on cli.id=cri.id WHERE cli.status='1'";
    $res=$con->query($sql);
    //return $sql;
    $result=array();
    if ($res)
    {
      while($row=$res->fetch_array())
      {
        if($row[0])
        {
          $result[] = explode(':',$row[0]);
        }
      }
      return $result;
    }
    return false;
  }
public function getCover()
{
  $con = $this->db();
  $sql="SELECT cri.postcodes FROM clients cli LEFT JOIN clients_criteria cri on cli.id=cri.id WHERE cli.status='1'";
  $res=$con->query($sql);
  $result=array();
  $result1=array();
if($res)
{
  while($row=$res->fetch_array())
  {
    $arr=explode(',',$row[0]);
    $arr=array_unique($arr);
    foreach($arr as $item)
    {
      $result[trim($item)]++;
    }
  }
  foreach($result as $key=>$item)
  {
    $result1[$item][]=$key;
  }
  return $result1;
}
  return false;
}
public function getManagers()
{
  $con=$this->db();
  $sql="SELECT c.id as 'id', c.name, u.id as 'u.id', u.email, u.active, u.full_name FROM campaigns c left join users u on c.user_id=u.id";
  $res=$con->query($sql);
  $result=array();
  if($res)
  {
    while ($row=$res->fetch_assoc())
    {
      $result[]=$row;
    }
  return $result;
  }
  return false;
}
}
