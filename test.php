<?php
session_start();
require_once 'vendor/autoload.php';
$infusionsoft = new \Infusionsoft\Infusionsoft(array(
    'clientId' => '5fdsmkxjb6h9k6g2y7nkx2tu',
    'clientSecret' => 'jDUKwcvSg4',
    'redirectUri' => 'http://project008/test.php',
));
//die();
$t='O:18:"Infusionsoft\Token":4:{s:11:"accessToken";s:24:"zb9w5t25ccs2wa35auj6yx9u";s:12:"refreshToken";s:24:"shwc7nf9w5ncyc947qns9ess";s:9:"endOfLife";i:1495122716;s:9:"extraInfo";a:2:{s:10:"token_type";s:6:"bearer";s:5:"scope";s:27:"full|ki355.infusionsoft.com";}}';
$_SESSION['token']=$t;
if (isset($_SESSION['token'])) {
    $infusionsoft->setToken(unserialize($_SESSION['token']));
}

if (isset($_GET['code']) and !$infusionsoft->getToken()) {
    $infusionsoft->requestAccessToken($_GET['code']);
}
//var_dump($infusionsoft);die();
if ($infusionsoft->getToken()) {
    $_SESSION['token'] = serialize($infusionsoft->getToken());

    $infusionsoft->contacts->add(array('FirstName' => 'John', 'LastName' => 'SmithFromCRM'));
}

else {
    echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
}