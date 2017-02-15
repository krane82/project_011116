<?php

class Controller_Api extends Controller {

	function __construct() {
    $this->model = new Model_Api();
    $this->view = new View();
  }
	
	public function action_index() {
    echo "access denied!";
	}

  public function action_record()
  {
    $host = 'http://' . $_SERVER['HTTP_HOST']

    //Since the tracking URL is a bit long, I usually put it in a variable of it's own
    $tracker = $host.'/api/record?log=true&deliveryn=';
    if( !empty( $_GET['log'] ) && $_GET['log'] == 'true' && !empty( $_GET['user'] ) && !empty( $_GET['subject'] ) )
    {
    header( 'Content-Type: image/gif' );

    $database = $this->db();
    //Make sure we aren't duplicating the insertion
    $exist_count = $database->num_rows( "SELECT user FROM email_log WHERE user = '$user' AND subject = '$subject'" );

    //No prior record of this message open exists
    if( $exist_count == 0 )
    {
        
        //Make an array of columns => data
        $insert_record = array(
            'user' => $user, 
            'subject' => $subject
        );
        //Insert the information into the email_log table
        $database->insert( 'email_log',  $insert_record );
        
    }
    
    //Get the http URI to the image
    $graphic_http = $host .'/blank.gif';
    
    //Get the filesize of the image for headers
    $filesize = filesize( THIS_ABSOLUTE_PATH . '/blank.gif' );
    
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

    //Add the tracker to the message.
    $message .= '<img alt="" src="'.$tracker.'" width="1" height="1" border="0" />';
  }

	public function action_in()
  {
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

}
