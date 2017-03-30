<?php
class Model_Reject extends Model
{
    public function delete()
    {
        $id = $_POST['id'];
        $client_id = $_POST['client_id'];

        $sql ="DELETE FROM `leads` WHERE `id`='$id'";
        $sql1="DELETE FROM `leads_delivery` WHERE `id`='$id'";
        $sql2="DELETE FROM `leads_lead_fields_rel` WHERE `id`='$id'";
        $sql3="DELETE FROM `leads_rejection` WHERE `lead_id`='$id'";

        $con = $this->db();
        $res = $con->query($sql);
        $res1 = $con->query($sql1);
        $res2 = $con->query($sql2);
        $res3 = $con->query($sql3);

    }

    public function getLead()
    {

    }

    public function rejectLead()
    {
        $id = $_POST['id'];
        $sql = "UPDATE `leads_rejection`";
        $sql.= " SET approval='2'";
        $sql.= " WHERE lead_id='$id'";
        $con = $this->db();
        $res = $con->query($sql);
    }

    public function approveReject()
    {
        $id = $_POST['id'];
        $sql = "UPDATE `leads_rejection`";
        $sql.= " SET approval='0'";
        $sql.= " WHERE lead_id='$id'";
        $con = $this->db();
        $res = $con->query($sql);
    }




}