<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.03.2017
 * Time: 11:33
 */
class Controller_Manager extends Controller
{
    function __construct() {
        $this->model = new Model_Manager();
        $this->view = new View();
    }

    public function action_index()
    {
        session_start();
        if ( $_SESSION['user'] ==  md5('manager')) {
            $data[0]=$this->model->getFact();
//            print '<pre>';
//            print_r ($this->model->getPenetr());
//            die();
            $data[1]=json_encode($this->model->getPenetr());
            $this->view->generate('manager_view.php', 'client_template_view.php', $data);
        }
    }
    public function action_planing_ajax()
    {
        session_start();
        if ( $_SESSION['user'] ==  md5('manager')) {
            $data=$this->model->getFact();
            $arr=array();
            foreach ($data as $item => $key) {
                $val='<td>' . $item . '</td>';
                $val.='<td style="width:6%">' . $key['NSW']['count'] . '</td><td style="width:6%; font-weight:bold">' . $key['NSW']['plan'] . '</td>';
                $val.='<td style="width:6%">' . $key['QLD']['count'] . '</td><td style="width:6%; font-weight:bold">'. $key['QLD']['plan'] . '</td>';
                $val.='<td style="width:6%">' . $key['SA']['count'] . '</td><td style="width:6%; font-weight:bold">' . $key['SA']['plan'] . '</td>';
                $val.='<td style="width:6%">' . $key['TAS']['count'] . '</td><td style="width:6%; font-weight:bold">' . $key['TAS']['plan'] . '</td>';
                $val.='<td style="width:6%">' . $key['VIC']['count'] . '</td><td style="width:6%; font-weight:bold">' . $key['VIC']['plan'] . '</td>';
                $val.='<td style="width:6%">' . $key['WA']['count'] . '</td><td style="width:6%; font-weight:bold">' . $key['WA']['plan'] . '</td>';
                $val.='</td>';
                $arr[]=$val;
            }
            $data[0]=$arr;
            $data[1]=$this->model->getPenetr();
            print json_encode($data);
        }
    }
}