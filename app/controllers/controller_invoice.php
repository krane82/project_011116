<?php
include_once 'app/models/model_leads.php';
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.04.2017
 * Time: 16:33
 */
class Controller_Invoice extends Controller
{
    private $leads;
    function __construct() {
        $this->leads = new Model_Leads();
        $this->view = new View();
        $this->model= new Model_Invoice();
    }

    function action_index()
    {
        session_start();
        if ($_SESSION['admin'] == md5('admin')) {
            $data = $this->leads->getAllClients();
            $this->view->generate('invoice_view.php', 'template_view.php', $data);
        }
        if ($_SESSION['user'] == md5('user')) {
            $data = $this->model->getMyInvoices($_SESSION['user_id']);
            $this->view->generate('client_invoice_view.php', 'client_template_view.php', $data);
        }
    }

    function action_generate()
    {
        print $this->model->generate();
    }
    function action_getListOfCurrent()
    {
        $client=$_POST['client'];
        print $this->model->getListOfCurrent($client);
    }
    function action_deleteFile()
    {
        $this->model->deleteFile();
    }
}