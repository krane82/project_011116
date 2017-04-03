<?php

class Model_Leadreject extends Model
{
    public function __construct()
    {
        $this->con = $this->db();
    }
    public function getLead()
    {
        $get=base64_decode($_SERVER["QUERY_STRING"]);
        $arr=explode('&',$get);
        $sql="SELECT timedate FROM leads_delivery WHERE id='".$arr[1]."'";
        $res=$this->con->query($sql);
        $result=$res->fetch_assoc();
        $beforeTwoDays='<form id="rejectForm">
    <p>Choose your rejection reason: </p>
    <label><input type="radio" name="reason" value="1" required> Outside of nominated area service (2 days to reject)</label><br>
    <label><input type="radio" name="reason" value="2"> Duplicate (2 days to reject)</label><br>
    <label><input type="radio" name="reason" value="3"> Incorrect Phone Number (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="6"> Unable to contact withing 7 days (7 days to reject)</label><br>
    <textarea style="width:100%" rows="3" name="notes" required></textarea>
    <input type="submit">
    </form>';
        $betweenTwoAndSeven='<form id="rejectForm">
    <p>Choose your rejection reason: </p>
    <label><input type="radio" name="reason" value="3"> Incorrect Phone Number (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>
    <label><input type="radio" name="reason" value="6"> Unable to contact withing 7 days (7 days to reject)</label><br>
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
        $lead_id = $_POST["lead_id"];
        $client_id = $_SESSION["user_id"];
        $reason = $_POST["reject_reason"];
        $notes = $_POST["notes"];
        $notes=trim($notes);
        $notes=htmlspecialchars($notes);
        $notes=addslashes($notes);
        $con = $this->db();
        $now = time();
        $sql = "UPDATE `leads_rejection` SET approval='2', reason='$reason', note='$notes', date='$now' WHERE client_id=$client_id AND lead_id=$lead_id";
        if($con->query($sql)){
            echo "Success";
        } else {
            echo "sql error";//$sql;
            return;
        }
    }
}