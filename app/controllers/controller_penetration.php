<?php
class Controller_Penetration
{
    function __construct()
    {
        $this->model = new Model_penetration();
        $this->view = new View();
    }

    function action_index()
    {
        $data["body_class"] = "page-header-fixed";
        session_start();
        if ($_SESSION['admin'] == md5('admin')) {
        $data = json_encode($this->model->getCode());
        $this->view->generate('penetration.php', 'template_view.php',$data);
        } else {
            session_destroy();
            $this->view->generate('danied_view.php', 'client_template_view.php', $data);
            //Route::ErrorPage404();
        }
    }
	function action_getCode()
    {
        print(json_encode($this->model->getCodeAjax()));
    }
}