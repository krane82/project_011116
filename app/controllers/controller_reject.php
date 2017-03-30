<?php

class Controller_Reject extends Controller
{
    function __construct()
    {
        $this->model = new Model_Reject();
        $this->view = new View();
    }

    function action_index()
    {
        $data["body_class"] = "page-header-fixed";
        $data["lead"] = $this->model->getLead();
        $data["title"] = "Admin Reject";
        session_start();
        if ($_SESSION['admin'] == md5('admin')) {
            $this->view->generate('reject_view.php', 'template_view.php', $data);
        } else {
            session_destroy();
            header('Location:/login');
            $this->view->generate('danied_view.php', 'template_view.php', $data);
        }
    }

    function action_GetApprovals(){

        $table = 'leads_rejection';
        $primaryKey = 'id';
        $columns = array(
            array( 'db' => '`a`.`lead_id`',          'dt' => 0, 'field' => 'lead_id'  ),
            array( 'db' => '`c`.`campaign_name`',          'dt' => 1, 'field' => 'campaign_name'  ),
//      array('db'=>'`a`.`date`', 'dt' => 2, 'formatter' => function( $d, $row ) {
//        return date('m/d/Y', $d);
//      }, 'field'=>'date'),
//      array( 'db' => '`c`.`email`',        'dt' => 2, 'field'=> 'email' ),
            array('db'=>'`ld`.`timedate`',       'dt' => 2, 'formatter' => function( $d, $row ) {
                return date('d/m/Y h:i:s A', $d);
            }, 'field'=>'timedate'),
            array( 'db' => '`ld`.`open_email`', 'dt' => 3, 'field' => 'open_email'  ),

//
            array( 'db' => '`a`.`approval`',  'dt' => 4, 'formatter'=>function($d){
                switch ($d) {
                    case 0:
                        return "<span class=\"bg-primary pdfive\">Approved</span>";
                        break;
                    case 1:
                        return "<span class=\"bg-success pdfive\">Approved</span>";
                        break;
                    case 2:
                        return "<span class=\"bg-warning\">Reject lead</span>";
                        break;
                    case 3:
                        return "<span class=\"bg-danger pdfive\">Reject not Approved</span>";
                        break;
                    case 4:
                        return "<span class=\"bg-info pdfive\">More info required</span>";
                    case 5:
                        return "<span class='hidden'>5</span>";
                    default:
                        return "";
                }
            },
                'field' => 'approval'),
            array('db'=> '`a`.`id`', 'dt'=> 5, 'formatter'=>function($d, $row){
                return "<a href='#' class='viewLeadInfo btn btn-info' attr-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\">View</a>";
            }, 'field'=>'id'),
            array('db'=>'`a`.`client_id`', 'dt'=>6, 'formatter'=>function($d, $row){
                return '<a href="#" role="button" onclick="approveReject('.$row[0]. ', '. $d .');" class="btn btn-small btn-info hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Approved Reject </a><br>
						    <a href="#" role="button" onclick="rejectLead('.$row[0]. ', '. $d .');" class="btn btn-small btn-success hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Reject</a><br>
						    <a href="#" role="button" onclick="delInfo('.$row[0]. ', '. $d .');" class="btn btn-small btn-danger hidden-tablet hidden-phone">
						    Delete</a>';
            }, 'field'=>'client_id')
        );

        $sql_details = array(
            'user' => DB_USER,
            'pass' => DB_PASS,
            'db'   => DB_NAME,
            'host' => DB_HOST
        );

        $joinQuery = "FROM `{$table}` AS `a` INNER JOIN `leads_delivery` as `ld` ON (`a`.`lead_id` = `ld`.`lead_id` AND a.client_id=ld.client_id) INNER JOIN clients as c ON a.client_id=c.id";
//        $where = "`a`.`approval` = 1 ";
        echo json_encode(
            SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where )
        );
    }

    public function action_delete()
    {
       $res = $this->model->delete();
    }

    public function action_rejectLead()
    {
        $res = $this->model->rejectLead();
    }

    public function action_approveReject()
    {
        $res = $this->model->approveReject();
    }
}