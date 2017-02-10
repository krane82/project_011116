<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2017
 * Time: 10:43
 */
class Controller_Leads_Limits
{
    function __construct()
    {
        $this->model = new Model_Leads_Limits();
        $this->view = new View();
    }
    function action_index()
    {
        $data = $this->model->getLimits();
        $this->view->generate('Leads_Limits.php', 'template_view.php', $data);
    }
    
}