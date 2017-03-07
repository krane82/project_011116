<?php
include "../../config.php";
include "../classes/cron.php";
include "../core/functions.php";
//Here we add cron tasks and check for time to run it
//This method will run cron task for resend leads that does not have 4 matches
if (date('wGi')=='21201')
{
    $resent = new cron();
    $resent->action_index();
}
