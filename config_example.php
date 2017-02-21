<?php
$host = 'http://' . $_SERVER['HTTP_HOST']; // для правильной подгрузки стилей и скриптов
define('__HOST__', $host);
define('_MAIN_DOC_ROOT_', __DIR__);
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );
<<<<<<< HEAD:config_example.php
define( 'DB_NAME', 'leadpoint' );
=======
define( 'DB_NAME', 'project007' );
>>>>>>> 29d32cfdc8603ffe90272d1ab805182dd795e1a8:config.php
define( 'SEND_ERRORS_TO', 'tonkoshkurik@yandex.ua' );
define( 'DISPLAY_DEBUG', true );
//define( 'SITENAME', '' );
define( 'ADMINEMAIL', 'tonkoshkurik@yandex.ua' );
date_default_timezone_set('Australia/Sydney');
//require_once('credentials.php');