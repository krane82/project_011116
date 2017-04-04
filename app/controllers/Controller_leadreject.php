<?php
class Controller_Leadreject extends Controller
{
    function __construct() {
        $this->model = new Model_Leadreject();
        $this->view = new View();
    }

    function action_index() {
//        print '<pre>';
//        var_dump($_SERVER);die();
       $data=$this->model->getLead();
       include_once 'app/views/leadreject.php';
    }

    function action_reject() {
        $this->model->rejectLead();
    }
}