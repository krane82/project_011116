<?php
include "app/models/model_api.php";
class Controller_Cron extends Controller    {

    function __construct() {
        $this->model = new Model_Api();
    }
    public function action_index() {
        $leads=$this->model->getSentLeads();
        foreach ($leads as $lead) {
            $this->proccess_lead($lead,$lead['count'],false,$lead['id']);
        }
        }
}