<?php

class Controller_Registration extends Controller {

    public function __construct()
    {
        $this->model = new Model_registration();
        $this->view = new View();
    }

    public function action_index()
    {
        $data = ['somedata'];
        $this->view->generate('registration/registration_view.php', 'registration/registration_template.php', $data);
    }

    public function action_submit()
    {
        if (isset($_POST)){
            $store = $this->model->store_client($_POST);
        }
    }
}
