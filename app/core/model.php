<?php 
class Model
{
  public $last_id;

  public function get_data()
  {

  }
  public function db() {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_errno) {
      printf("Connect failed: %s\n", $con->connect_error);
      exit();
    }
    return $db;
  }

  public function sql($sql) {
    $con = $this->db();
    if( $result = $con->query($sql) ) {
      $this->last_id = $con->insert_id;
      $con-close();
      return $result;
    }
    return FALSE;
  }
}
