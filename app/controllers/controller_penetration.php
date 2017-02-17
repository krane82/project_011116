<?
class Controller_penetration
{
    function __construct()
    {
        $this->model = new Model_penetration();
        $this->view = new View();
    }

    function action_index()
    {
        $data = json_encode($this->model->getCode());
        $this->view->generate('penetration.php', 'template_view.php',$data);
    }
	function action_getCode()
    {
        print(json_encode($this->model->getCodeAjax()));
    }
}