<?php

class Controller_Api extends Controller {

	function __construct() {
    $this->model = new Model_Api();
    $this->view = new View();
  }
	
	function action_index() {
    echo "access denied!";
	}

	function action_in() {
    if(isset($_POST['source']))
    {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      if(getCampaignID($_POST['source'])) {
        $this->model->proccess_lead($_POST);
      }
    }
    else
    {
      echo "access not allowed";
    }
  }

}
