<?php

class Controller_Admin_Reports extends Controller
{
  function __construct()
  {
    $this->model = new Model_Admin_Reports();
    $this->view = new View();
  }
  function action_index() {
    $data["body_class"] = "page-header-fixed";
    $data["LeadSources"] = $this->model->getLeadSources();
    $data["clients"] = $this->model->getClients();
    $data["title"] = "Admin Report";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      $this->view->generate('admin_reports_view.php', 'template_view.php', $data);
    } else if ($_SESSION['user'] == md5('user')) {
      header('Location:/client/dashboards');
      $this->view->generate('client_reports_view.php', 'client_template_view.php', $data);
    }
    else
    {
      session_destroy();
      header('Location:/login');
      $this->view->generate('danied_view.php', 'template_view.php', $data);
    }
  }
  function action_getDistributed(){

    $now = time();
    $filename =  'Leads_Distributed_' . date("Y_m_d", $now);
    $data = $this->model->getDistributed();
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename .'.csv');
    $fp = fopen('php://output', 'w');
    foreach( $data as $line ) {
      fputcsv( $fp, $line );
    }
    fclose($fp);

  }

  function action_getAverage(){
    $this->model->getAverageReports();
  }

  function action_getAverageNew(){
    $this->model->getAverageReportsNew();
  }

  public function action_downloadAcceptedRejected()
  {
    $now = time();
    $filename =  'Leads_Accepted_Rejected_' . date("Y_m_d", $now);
    $data = $this->model->downloadAcceptedRejected();
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename .'.csv');
    $fp = fopen('php://output', 'w');
    foreach( $data as $line ) {
      fputcsv( $fp, $line );
    }
    fclose($fp);
  }

  // function action_getAverageNewSec(){
  //   $this->model->getAverageReportsNewSec();
  // }

  function action_getSourceAverage(){
    $this->model->getSourceAverage();
  }

  function action_getRejected(){
    $now = time();
    $filename =  'Leads_Rejected_' . date("Y_m_d", $now);
    $data = $this->model->getRejected();
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename .'.csv');
    $fp = fopen('php://output', 'w');
    foreach( $data as $line ) {
      fputcsv( $fp, $line );
    }
    fclose($fp);
  }

  function action_getReceived(){
    $now = time();
    $filename =  'Leads_Received_' . date("Y_m_d", $now);
    $data = $this->model->getReceived();
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename .'.csv');
    $fp = fopen('php://output', 'w');
    foreach( $data as $line ) {
      fputcsv( $fp, $line );
    }
    fclose($fp);
  }

  function action_getAccepted(){
    $now = time();
    $filename =  'Leads_accepted_' . date("Y_m_d", $now);
    $data = $this->model->getAccepted();
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename .'.csv');
    $fp = fopen('php://output', 'w');
    foreach( $data as $line ) {
      fputcsv( $fp, $line );
    }
    fclose($fp);
  }

  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

  function action_getPending(){
        $this->model->getPending();
  }

}
