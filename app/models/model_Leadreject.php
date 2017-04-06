<?php

class Model_Leadreject extends Model
{
    private $con;
    public function __construct()
    {
        $this->con = $this->db();
    }
    public function getLead()
    {
        $get=base64_decode($_SERVER["QUERY_STRING"]);
        $arr=explode('&',$get);
//        $sql="SELECT led.timedate, ler.approval FROM leads_delivery led left join leads_rejection ler on led.client_id=ler.client_id WHERE led.id='".$arr[1]."'";
//        return $sql;
        $sql="SELECT ler.approval, led.timedate FROM leads_rejection ler right join leads_delivery led on led.lead_id=ler.lead_id WHERE led.id='".$arr[1]."' AND ler.client_id='".$arr[0]."'";
        $res=$this->con->query($sql);
        $result=$res->fetch_assoc();
        if($result['approval']!=1) {return 'This lead is already rejected';}
        $beforeTwoDays='<form method="POST" id="rejectForm" action="http://'.$_SERVER['HTTP_HOST'].'/leadreject/reject">
    <p>Choose your rejection reason: </p>
    <input type="hidden" name="data" value="'.$_SERVER["QUERY_STRING"].'">
    <label><input type="radio" name="reason" value="1" required> Outside of nominated area service (2 days to reject)</label><br>
    <label><input type="radio" name="reason" value="2"> Duplicate (2 days to reject)</label><br>
    <label><input type="radio" name="reason" value="3"> Incorrect Phone Number (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="6"> Unable to contact within 7 days (7 days to reject)</label><br>
    <textarea style="width:100%" rows="3" name="notes" required></textarea>
    <input type="submit">
    </form>';
        $betweenTwoAndSeven='<form method="POST" id="rejectForm" action="http://'.$_SERVER['HTTP_HOST'].'/leadreject/reject">
    <p>Choose your rejection reason: </p>
    <input type="hidden" name="data" value="'.$_SERVER["QUERY_STRING"].'">
    <label><input type="radio" name="reason" value="3"> Incorrect Phone Number (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="6"> Unable to contact within 7 days (7 days to reject)</label><br>
    <textarea style="width:100%" rows="3" name="notes" required></textarea>
    <input type="submit">
    </form>';
        $outOfPeriod='Rejection period for this lead is over';
        $twoDays=time()-86400*2;
        $sevenDays=time()-86400*7;
    if($result['timedate']>$twoDays)
    {
        return $beforeTwoDays;
    }
        else if(($result['timedate']<$twoDays) && ($result['timedate']>$sevenDays))
        {
            return $betweenTwoAndSeven;
        }
        else {return $outOfPeriod;}
    }

    public function rejectLead()
    {
        $get=base64_decode($_POST['data']);
        $arr=explode('&',$get);
        $reason = $_POST["reason"];
        $notes = $_POST["notes"];
        $arr[0]=$this->clean($arr[0]);
        $arr[1]=$this->clean($arr[1]);
        $reason=$this->clean($reason);
        $notes=$this->clean($notes);
        $now = time();
        $sql="SELECT lead_id FROM leads_delivery WHERE id='".$arr[1]."' AND client_id='".$arr[0]."'";
        $res=$this->con->query($sql);
        $updatingLeadId=$res->fetch_array();
        $sql1 = "UPDATE `leads_rejection` SET approval='2', reason='$reason', note='$notes', date='$now' WHERE client_id='$arr[0]' AND lead_id='$updatingLeadId[0]'";
        //return var_dump($sql1);
        if($this->con->query($sql1)){
            echo "Thank you for entering the reason!";
        } else {
            echo "For some reason connection is aborted. If you need to reject current lead log in to the system.";//$sql;
            return;
        }
    }
    private function clean($item)
    {
        $item=trim($item);
        $item=htmlspecialchars($item);
        $item=addslashes($item);
        return $item;
    }
}