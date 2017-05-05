<?php
class Model_Approvals extends Model {

  public function getLeadSources() {
    $con = $this->db();
    $LeadSources = array();
    $sql = "SELECT `source`, `name` FROM `campaigns`";
    $res = $con->query($sql);
    while ($row = $res->fetch_assoc()){
      $LeadSources[] = $row;
    }
    return $LeadSources;
  }

public function deleteFile()
{
  $con = $this->db();
  $filepath=$_POST['path'];
  //return var_dump($_POST);
  $sql="UPDATE leads_rejection SET audiofile=NULL WHERE audiofile='$filepath'";
  if(is_file($filepath))
  {
    unlink($filepath);
  }
  $res = $con->query($sql);
  return $sql;
}
}