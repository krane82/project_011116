<?php

class Controller_Api extends Controller {

  function __construct() {
    $this->model = new Model_Api();
    $this->view = new View();
  }

  public function action_index() {
    echo "access denied!";
  }


  public function action_in()
  {
    print '<pre>';
    print_r ($_POST);
    if(isset($_POST['source']))
    {
      $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      if(getCampaignID($_POST['source'])) {
        echo $this->model->proccess_lead($_POST);
      }
    }
    else
    {
      echo "access not allowed";
    }
  }


  //  TACK OPEN MAILS
  //  Add the tracker to the message.
  //  $tracker = 'http://' . $_SERVER['HTTP_HOST'] . '/api/record?log=true&deliveryn=' . $track_id;
  //  $message .= '<img alt="" src="'.$tracker.'" width="1" height="1" border="0" />';
  public function action_record()
  {
    if( !empty( $_GET['log'] ) && $_GET['log'] == 'true' && !empty( $_GET['deliveryn'] ) )
    {
      header( 'Content-Type: image/gif' );

      //Make sure we aren't duplicating the insertion
      $id = (int)$_GET['deliveryn'];
      $database = DB::getInstance();
      $exist_count = $database->num_rows( "SELECT open_email FROM `leads_delivery` WHERE `id`=$id" );

      if( $exist_count == 1 )
      {
        $opens = $database->get_results( "SELECT open_email FROM `leads_delivery` WHERE `id`=$id" );

        is_null($opens[0]["open_email"]) ? $nums = 1 : $nums =  $opens[0]["open_email"] + 1;

        
        //Make an array of columns => data
        $now =  time();
        $update = array(
          'open_email' => $nums,
          'open_time'  => $now
        );

        $where = array(
          'id' => $id
        );
        //Insert the information into the email_log table
        $database->update( 'leads_delivery',  $update, $where );
      }

      //Get the http URI to the image
      $graphic_http = __HOST__ .'/blank.gif';

      //Get the filesize of the image for headers
      $filesize = filesize( _MAIN_DOC_ROOT_ . '/blank.gif' );

      //Now actually output the image requested, while disregarding if the database was affected
      header( 'Pragma: public' );
      header( 'Expires: 0' );
      header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
      header( 'Cache-Control: private',false );
      header( 'Content-Disposition: attachment; filename="blank.gif"' );
      header( 'Content-Transfer-Encoding: binary' );
      header( 'Content-Length: '.$filesize );
      readfile( $graphic_http );

      //All done, get out!
      exit;
    }

  }

}
