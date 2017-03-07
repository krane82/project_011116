<?php
include "../../config.php";
include "../classes/cron.php";
include "../core/functions.php";
$resent=new cron();
$resent->action_index();