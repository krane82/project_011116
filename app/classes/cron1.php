<?php
class cron1
{
	public function db()
	{
		$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($con->connect_errno) {
			printf("Connect failed: %s\n", $con->connect_error);
			exit();
		}
		return $con;
	}
	public function getleads()
	{
		// date_default_timezone_set('UTC');
		$date = date('Y-m-d');
  		$date = strtotime($date);

		$sql = "SELECT `id_lead`, `id_client` FROM `queue`";
		$sql .= "WHERE status=1 AND timequeue='$date' ORDER BY timequeue ASC";
		$con = $this->db();
		$res = $con->query($sql);
		if($res->num_rows>0){
			$all = array();
			while($result = $res->fetch_assoc())
			{
				$all[] = $result;
			}

			$this->addToDeliveredTable($all);	
		}

	}

	public function addToDeliveredTable($all){
    $con = $this->db();
    $now = time();
 
	foreach($all as $inf){
		
		$id_leads = $inf['id_lead'];
		$id = $inf['id_client'];
	    $sql = "INSERT INTO `leads_delivery` (lead_id, client_id, timedate) VALUES ($id_leads, $id, $now)";
	    $sql_r = "INSERT INTO `leads_rejection` (lead_id, client_id, date, approval) VALUES ($id_leads, $id, $now, 1)";
	    $sqlupd = "UPDATE `queue` SET `status`=0 WHERE `id_lead`='$id_leads' AND `id_client`='$id'";

	     if($con->query($sql) && $con->query($sql_r) && $con->query($sqlupd)) { $delivered=1; }
	}

    if($delivered){
      return TRUE;
    } else {
      return FALSE;
    }

  }
	public function renewToken()
	{
		require_once '../../vendor/autoload.php';
		$con = $this->db();
		$gettokenQuery="SELECT token FROM token";
		$gettokenResult=mysqli_query($con, $gettokenQuery);
		$result=mysqli_fetch_assoc($gettokenResult);
		$oldToken=$result['token'];
// echo $oldToken.'<pre>';
		$infusionsoft = new \Infusionsoft\Infusionsoft(array(
			'clientId' => '5fdsmkxjb6h9k6g2y7nkx2tu',
			'clientSecret' => 'jDUKwcvSg4',
			'redirectUri' => 'http://project008/test.php',
		));
		$infusionsoft->setToken(unserialize($oldToken));
		$newTok=$infusionsoft->refreshAccessToken();
		$newToken=addslashes(serialize($newTok));
		$settokenQuery="UPDATE token set token='$newToken'";
		mysqli_query($con, $settokenQuery);

//This method must renew token every 21 hours.
	}
}