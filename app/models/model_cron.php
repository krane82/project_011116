<?php
class Model_Cron extends Model {


	public function getleads()
	{
		

		$sql = "SELECT `id_lead`, `id_client` FROM `queue`";
		$sql .= "WHERE status=1 ORDER BY timedata ASC LIMIT 10 ";
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

	    if($con->query($sql) && $con->query($sql_r)) { $delivered=1; }
	}

    if($delivered){
      return TRUE;
    } else {
      return FALSE;
    }

  }


  public function test()
  {

  	$i = 'Hello';
  	$sql = "INSERT INTO `queue` (test) VALUES ('$i')";
  	$con = $this->db();
	$res = $con->query($sql);
	if($res){
		echo "YES";
	}
  }
}