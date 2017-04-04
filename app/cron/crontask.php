<?php
include "../../config.php";
include "../classes/cron.php";
include "../classes/cron1.php";
include "../core/functions.php";
//Here we add cron tasks and check for time to run it
//This method will run cron task for resend leads that does not have 4 matches
if (date('w')=='2')
{
    $resent = new cron();
    $resent->action_index();
}
//Here is Arthur's cron task
if (date('i')=='01')
{
    $cron1=new cron1();
    $cron1->getleads();
}