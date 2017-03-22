<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.02.2017
 * Time: 16:24
 */
class Model_Settings extends Model
{
public function getSettings()
{
    $con = $this->db();
    $sql="SELECT * FROM lead_settings";
    $res=$con->query($sql);
    return($res->fetch_assoc());
}
public function updateSettings()
{
    $con = $this->db();
    $sql="UPDATE lead_settings SET days='".$_POST['days']."'";
    if($res=$con->query($sql)) return true;
    return false;
}
}