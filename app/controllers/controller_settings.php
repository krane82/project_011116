<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23.02.2017
 * Time: 16:20
 */
class Controller_Settings extends Controller
{
    function __construct() {
        $this->model = new Model_Settings();
        $this->view = new View();
    }
    public function action_index()
    {
        $data["body_class"] = "page-header-fixed";
        session_start();
        if ($_SESSION['admin'] == md5('admin')) {
    $data=$this->model->getSettings();
    $this->view->generate('settings.php', 'template_view.php', $data);
    } else {
    session_destroy();
    $this->view->generate('danied_view.php', 'client_template_view.php', $data);
          }
    }

    public function action_update()
    {
        $this->model->updateSettings();
    }
}