<?php
include_once "model_campaigns.php";
include_once "model_penetration.php";
session_start();

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.03.2017
 * Time: 12:21
 */
class Model_manager extends Model
{
    private $campaign;
    public function __construct()
    {
        $this->campaign=$this->getCamp($_SESSION['user_id']);
    }
    public function getFact()
    {
    $campaigns = new Model_campaigns();
    $result=$campaigns->getPlans($this->campaign);
    return $result;
    //return $this->campaign;
    }
    public function getPenetr()
    {
        $penetration = new Model_penetration();
        $result=$penetration->getCodeAjax($this->campaign);
        return $result;

    }
    private function getCamp($id)
    {
        $con=$this->db();
        $sql="SELECT id from campaigns WHERE user_id='".$id."'";
        $res=$con->query($sql);
        if($res){
        $result=$res->fetch_assoc();
        return $result['id'];
        }
       return false;
//        return $sql;
    }
}