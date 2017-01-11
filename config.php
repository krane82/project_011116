<?php
$host = 'http://' . $_SERVER['HTTP_HOST']; // для правильной подгрузки стилей и скриптов
define('__HOST__', $host);
define('_MAIN_DOC_ROOT_', __DIR__);
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );
define( 'DB_NAME', 'leadpoint_new' );
define( 'SEND_ERRORS_TO', '' );
//define( 'DISPLAY_DEBUG', false );
//define( 'SITENAME', '' );
define( 'ADMINEMAIL', 'rapyo@ukr.net' );
date_default_timezone_set('Australia/Sydney');