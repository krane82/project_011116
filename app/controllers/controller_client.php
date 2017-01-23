<?php

class Controller_Client extends Controller
{
  function action_index() {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ( $_SESSION['admin'] == md5('admin'))
    {
      $this->view->generate('admin_view.php', 'template_view.php', $data);
    } else if ($_SESSION['user'] == md5('user')) {
      header('Location:/client/dashboards');
      $this->view->generate('admin_view.php', 'client_template_view.php', $data);
    }
    else
    {
      session_destroy();
      header('Location:/login');
      $this->view->generate('danied_view.php', 'template_view.php', $data);
    }
  }

  public function action_getAverageReports()
  {
    $con = $this->db();
    $client = $_COOKIE["user_id"];
    $timestamp = time();
    if(!empty($_POST["start"])){
      $start = strtotime($_POST["start"]);
      $end = strtotime($_POST["end"]) + 86400;
    } else {
      echo "<h2 class='text-center'>Today stats</h2><hr>";
      $start = strtotime("midnight", $timestamp);
      $end = strtotime("tomorrow", $start) - 1;
    }

    // get approved leads and sum
    $sql = 'SELECT COUNT(*) as amount, SUM(c.lead_cost) as total_cost  FROM `leads_delivery` as ld ';
    $sql .= ' LEFT JOIN `clients` as c ON ld.client_id = c.id';
    $sql .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql .= ' WHERE (lr.approval > 0 OR lr.approval IS NULL)';
    $sql .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    $sql .= ' AND ld.client_id =' . $client;

    $res = $con->query($sql);
    $approved = array();

    if ($res) {
      $approved = $res->fetch_assoc();
    } else {
      echo "No data\n";
    }

    $sql2 = 'SELECT COUNT(*) as amount FROM `leads_delivery` as ld ';
    $sql2 .= ' LEFT JOIN `leads_rejection` as lr ON lr.lead_id = ld.lead_id AND lr.client_id = ld.client_id';
    $sql2 .= ' WHERE lr.approval=0';
    $sql2 .= ' AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if ($client != 0) {
      $sql2 .= ' AND ld.client_id =' . $client;
    }
    $res = $con->query($sql2);
    if ($res) {
      $rejected = $res->fetch_assoc();
    } else {
      echo "0";
    }
//  DISTINCT
    $sqlDestributed  = 'SELECT COUNT(ld.lead_id) as amount, SUM(c.cost) as camp_cost FROM `leads_delivery` as ld';
    $sqlDestributed .= ' INNER JOIN `leads` as l ON l.id=ld.lead_id';
    $sqlDestributed .= ' INNER JOIN `campaigns` as c ON c.id = l.campaign_id';
    $sqlDestributed .= ' WHERE 1=1 AND (ld.timedate BETWEEN '.$start.' AND '.$end.')';
    if (!($client == 0)) {
      $sqlDestributed .= ' AND ld.client_id =' . $client;
    }

    $res = $con->query($sqlDestributed);
    if($res){
      $distributed = $res->fetch_assoc();
    }

    $rejectedP = $rejected["amount"] / $distributed["amount"];
    $ds =  $distributed["amount"] . " leads <br>Distributed";
    $acs = $approved['amount']. " leads Accepted";
    $ras = $rejected["amount"] . " leads Rejected";
    $trs = $approved["total_cost"] . " total cost";
    $rejectedPercent =  number_format($rejectedP * 100, 0) . '% Rejected leads<br>';
    $rev = $approved["total_cost"] ? $approved["total_cost"] : 0 . " $<br>total leads cost";
    echo $this->formStatView($ds, 'users', 'getDistributed');
    echo $this->formStatView($acs, 'check', 'getAccepted');
    echo $this->formStatView($ras, 'window-close', 'getRejected');
    echo $this->formStatView($rejectedPercent, 'window-close', 'getRejected');
    echo $this->formStatView($rev, 'shopping-cart');
  }
  function action_dashboard() {
    $data["body_class"] = "page-header-fixed";
    session_start();
    $data["title"] = "Client Dashboard";
    if ($_SESSION['user'] == md5('user')) {
      $this->view->generate('client_dashboard_view.php', 'client_template_view.php', $data);
    } else {
      session_destroy();
      $this->view->generate('danied_view.php', 'template_view.php', $data);
    }
  }
  function action_logout()
  {
    session_start();
    session_destroy();
    header('Location:/login');
  }

  private function formStatView($string, $icon_class, $fn='fncsv', $color='')
  {
    $v = '  
      <div class="col-md-2 '. $color .'">
        <div onclick="'.$fn.'()" class="panel panel-white pdten" style="cursor: pointer;">
      <i class="fa fa-'.$icon_class.' icon" aria-hidden="true"></i>' . $string .'
      </div> </div>';
    return $v;
  }

}
