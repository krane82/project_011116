<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.03.2017
 * Time: 17:32
 */
class Controller_Temp  extends Controller
{
    public function db() {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_errno) {
            printf("Connect failed: %s\n", $con->connect_error);
            exit();
        }
        return $db;
    }
        public function action_index()
    {
        $con=$this->db();
        $sql1="UPDATE leads_lead_fields_rel SET state='NSW' WHERE postcode between '1000' AND '1999' OR postcode between '2000' AND '2599' OR postcode between '2620' AND '2898' OR postcode between '2921' AND '2999'";
        $sql2="UPDATE leads_lead_fields_rel SET state='VIC' WHERE postcode between '3000' AND '3999' OR postcode between '8000' AND '8999'";
        $sql3="UPDATE leads_lead_fields_rel SET state='QLD' WHERE postcode between '4000' AND '4999' OR postcode between '9000' AND '9999'";
        $sql4="UPDATE leads_lead_fields_rel SET state='SA' WHERE postcode between '5000' AND '5799' OR postcode between '5800' AND '5999'";
        $sql5="UPDATE leads_lead_fields_rel SET state='WA' WHERE postcode between '6000' AND '6797' OR postcode between '6800' AND '6999'";
        $sql6="UPDATE leads_lead_fields_rel SET state='TAS' WHERE postcode between '7000' AND '7799' OR postcode between '7800' AND '7999'";
        if($con->query($sql1)) print 'NSW Done!';
        if($con->query($sql2)) print 'VIC Done!';
        if($con->query($sql3)) print 'QLD Done!';
        if($con->query($sql4)) print 'SA Done!';
        if($con->query($sql5)) print 'WA Done!';
        if($con->query($sql6)) print 'TAS Done!';
    }
}