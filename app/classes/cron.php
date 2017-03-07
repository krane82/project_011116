<?php
include "class.db.php";
include "../libs/PHPMailer/class.phpmailer.php";
include "../core/model.php";
include "../models/model_api.php";
include "../core/controller.php";
class cron   {

    function __construct() {
        $this->model = new Model_Api();
    }
    public function db()
    {
        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($con->connect_errno) {
            printf("Connect failed: %s\n", $con->connect_error);
            exit();
        }
        return $con;
    }
    public function action_index() {
        $leads=$this->model->getSentLeads();
        print '<pre>';
        //print_r($leads);
        foreach ($leads as $lead) {
        $this->model->proccess_lead($lead, $lead['count'],false,$lead['id']);
        print 'Done, '.$lead['id'].'<br>';
        }
        }
}