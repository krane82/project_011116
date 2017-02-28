<?php
class Controller_cron extends Controller
{
   function __construct()
  {
    $this->model = new Model_Cron();
    $this->view = new View();
  }
	
  public function action_index()
  {

  	$test = $this->model->test();
exit();
   
  	$res = $this->model->getleads();
  	exit($res);
  }

}