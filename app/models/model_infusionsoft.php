<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.05.2017
 * Time: 12:22
 */
class Model_Infusionsoft extends Model
{
      private function gettoken()
    {
        $con=$this->db();
        $gettokenQuery="SELECT token FROM token";
        $gettokenResult=mysqli_query($con, $gettokenQuery);
        $result=mysqli_fetch_assoc($gettokenResult);
        $oldToken=$result['token'];
        return $oldToken;
    }
    function sendLead($p)
    {
        session_start();
        $_SESSION['token']=$this->gettoken();
        $infusionsoft = new \Infusionsoft\Infusionsoft(array(
            'clientId' => '5fdsmkxjb6h9k6g2y7nkx2tu',
            'clientSecret' => 'jDUKwcvSg4',
            'redirectUri' => 'http://project008/test.php',
        ));
            $infusionsoft->setToken(unserialize($_SESSION['token']));
        if ($infusionsoft->getToken()) {
            $infusionsoft->sendLead($p);
        }

    }
    function sendClient($p)
    {
        session_start();
        $_SESSION['token']=$this->gettoken();
        $infusionsoft = new \Infusionsoft\Infusionsoft(array(
            'clientId' => '5fdsmkxjb6h9k6g2y7nkx2tu',
            'clientSecret' => 'jDUKwcvSg4',
            'redirectUri' => 'http://project008/test.php',
        ));
        $infusionsoft->setToken(unserialize($_SESSION['token']));
        if ($infusionsoft->getToken()) {
            $infusionsoft->contacts->add(array('FirstName' => 'John', 'LastName' => 'SmithFromCRM'));
        }

    }
}