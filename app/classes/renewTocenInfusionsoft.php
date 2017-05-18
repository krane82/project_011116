<?php
require_once '../../vendor/autoload.php';
require_once '../../config.php';
$con=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$gettokenQuery="SELECT token FROM token";
$gettokenResult=mysqli_query($con, $gettokenQuery);
$result=mysqli_fetch_assoc($gettokenResult);
$oldToken=(string)$result['token'];
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
