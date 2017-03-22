<?php
class Controller_Leads_Limits
{
    function __construct()
    {
        $this->model = new Model_Leads_Limits();
        $this->view = new View();
    }

    function action_index()
    {
    $data["body_class"] = "page-header-fixed";
    session_start();
    if ($_SESSION['admin'] == md5('admin')) {
        $data = $this->model->getLimits();
        $this->view->generate('leads_limits.php', 'template_view.php', $data);
    } else {
        session_destroy();
        $this->view->generate('danied_view.php', 'client_template_view.php', $data);
        //Route::ErrorPage404();
    }
    }

    function action_matches()
    {
        $data["body_class"] = "page-header-fixed";
        session_start();
        if ($_SESSION['admin'] == md5('admin')) {
            $data = $this->model->getMaches();
            $this->view->generate('matches.php', 'template_view.php', $data);
        }   else {
            session_destroy();
            $this->view->generate('danied_view.php', 'client_template_view.php', $data);
            //Route::ErrorPage404();
        }
    }

    function action_matchesAjax()
    {
        $start = strtotime($_POST["st"]);
        $end = strtotime($_POST["en"]) + 86400;
        $table = 'leads_lead_fields_rel';
        // print_r($_POST); exit();
//le.id, le.postcode, cli.campaign_name FROM leads_lead_fields_rel as le LEFT JOIN leads_delivery as led on le.id=led.lead_id LEFT JOIN clients cli on led.client_id=cli.id";
//SELECT le.postcode, count(le.id), cli.campaign_name FROM leads_lead_fields_rel as le LEFT JOIN leads_delivery as led on le.id=led.lead_id LEFT JOIN clients cli on led.client_id=cli.id group by le.postcode

        $primaryKey = 'id';

        $columns = array(
          array('db' => 'le.postcode', 'dt' => 0, 'field' => 'postcode'),
          array('db' => 'count(le.id)', 'dt' => 1, 'field' => 'count(le.id)'),
          array('db' => 'group_concat(cli.campaign_name)', 'dt' => 2, 'field' => 'group_concat(cli.campaign_name)'),
          array('db' => 'led.lead_id', 'dt' => 3, 'field' => 'lead_id')
        );

        $sql_details = array(
          'user' => DB_USER,
          'pass' => DB_PASS,
          'db' => DB_NAME,
          'host' => DB_HOST
        );

        //$joinQuery = "FROM `{$table}` AS `l` LEFT JOIN `campaigns` AS `c` ON (`l`.`campaign_id` = `c`.`id`) LEFT JOIN `leads_lead_fields_rel` AS `lf` ON `lf`.`id`=`l`.`id`";
        //$joinQuery = "FROM `{$table}` AS `le` LEFT JOIN `leads_delivery` AS `led` ON (`le`.`id` = `led`.`lead_id`) LEFT JOIN `clients` `cli` ON `led`.`client_id`=`cli`.`id`";
        $joinQuery = "FROM `{$table}` AS `le` LEFT JOIN `leads_delivery` AS `led` ON (`le`.`id` = `led`.`lead_id`) LEFT JOIN `clients` `cli` ON `led`.`client_id`=`cli`.`id`";
        $where = ' (`led`.`timedate` BETWEEN ' . $start . ' AND ' . $end . ')';
        $groupBy='led.lead_id';

        echo json_encode(
          SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupBy)
        );

    }
}