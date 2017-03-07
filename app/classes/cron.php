<?php
include "../core/model.php";
include "../models/model_api.php";
include "../core/controller.php";
class cron extends Controller    {

    function __construct() {
        $this->model = new Model_Api();
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