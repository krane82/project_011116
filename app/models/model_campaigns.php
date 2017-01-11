<?php
class Model_Campaigns extends Model {

  public function get_data() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($con->connect_errno) {
      printf("Connect failed: %s\n", $con->connect_error);
      exit();
    }
    $query = "SELECT * FROM `campaigns`";
    if( $result = $con->query($query) ) {
      $table = "<table class=\"display table table-condensed table-striped table-hover table-bordered pull-left\">";
      $table .= "<tr> <th>ID</th><th>Source</th><th>Cost</th><th>Status</th></tr>";
      while ($row = $result->fetch_assoc()) {
        $table .= "<tr>";
        $table .= "<td>" . $row['id'] . "</td><td>" . $row['source'] . "</td><td>" . $row['cost'] . "<td>" . $row['status'] . "</td> ";
        $table .= "<tr>";
      }
      $table .= "</table>";
      $result->free();
    } else die ("Database access failed: " . $con->error);
      $con->close();
      return $table;
  }
  public function generateembed($campaign_id){
    $sql = "SELECT lead_fields.name, lead_fields.key, lead_fields.type, lead_fields.object, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory";
    $sql .= " FROM `campaign_lead_fields_rel`";
    $sql .= " LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id";
    $sql .= " WHERE campaign_lead_fields_rel.campaign_id=$campaign_id";
    $sql_source = "SELECT * FROM `campaigns` WHERE id='$campaign_id'";
    $con = $this->db();
    $res = $con->query($sql_source);
//    var_dump($sql_source);
    $source = $res->fetch_assoc();
    $source = $source["source"];
    $result = $con->query($sql);
    if($result) {
      $textarea = "<pre>\n ";
      $textareaspec = '<form action="' .__HOST__.  '/api/in" method="post" >' . "\n";
      $textareaspec .= '<input type="hidden" name="source" value="'.$source.'" />' ."\n";
      while ($row = $result->fetch_assoc()) {
        if ($row["is_active"]) {
          $textareaspec .= '<div class="form-group">' ."\n";
          $textareaspec .= '  <label for="' . $row["key"] . '">' . $row["name"] . '</label>' . "\n";
//          if ($row["type"] == "text") {
            $textareaspec .= '  <input type="text" class="form-control" name="' . $row["key"] . '" placeholder="' . $row["name"] . '" ' . (($row['mandatory'] == 1) ? 'required' : '') . ">\n";
//          } else {
//            $textareaspec .= $row["object"] ."\n";
//          }
          $textareaspec .= "</div>\n";
        }
      }
      $textareaspec .= '<input type="submit" class="btn btn-primary" value="SUBMIT">'."\n";
      $textareaspec .= "</form> \n";
      $textarea .= htmlspecialchars($textareaspec, ENT_QUOTES);
      $textarea .= " </pre>";
      $result->free();
    } else die ("Database access failed: " . $con->error);
//    $rows = $result->num_rows;
      $con->close();
      return $textarea;
  }

  public function generetefields($campaign_id) {
    $field_ids = array();
    $sql = 'SELECT id FROM `lead_fields`';
    $db = $this->db();
    $res = $db->query($sql);
    if ($res){
        while ($row = $res->fetch_array()){
            $field_ids[] = $row['id'];
        }
    }
    $sql = 'INSERT INTO `campaign_lead_fields_rel`';
    $sql.= ' (campaign_id, field_id)';
    $sql.= ' VALUES ';
    foreach ($field_ids as $field_id){
        $sql.= '('.$campaign_id.','.$field_id.'),';
    }
    $sql = rtrim($sql, ',');

    $db->query($sql);
    $db->close();
  }
  public function fields_model($campaign_id) {
    $sql = 'SELECT lead_fields.name, campaign_lead_fields_rel.is_active, campaign_lead_fields_rel.mandatory';
    $sql.= ' FROM `campaign_lead_fields_rel`';
    $sql.= ' LEFT JOIN `lead_fields` ON lead_fields.id = campaign_lead_fields_rel.field_id';
    $sql.= ' WHERE campaign_lead_fields_rel.campaign_id = '.$campaign_id;
    $table="";
    if( $result = $this->db()->query($sql) ) {
    $field_id = 1;
    while($r = $result->fetch_assoc()){
      $table.="<tr>";
      foreach($r as $k => $v) {
        $table.='<td>';
        if ($k == 'is_active'){
          $table.= '<input type="checkbox" attr-field-id="'.$field_id.'" attr-campaign-id="'.$campaign_id.'" value="'.$v.'" class="is_active" data-size="small" '.(($v == 1)?'checked':'').'>' ;
        } else if($k == 'mandatory') {
          $table.= '<input type="checkbox" attr-field-id="'.$field_id.'" attr-campaign-id="'.$campaign_id.'" value="'.$v.'" class="mandatory" data-size="small" data-on-text="YES" data-off-text="NO" '.(($v == 1)?'checked':'').'>' ;
        } else if($k == 'name') { $table.= $v; }
        $table.='</td>';
      }
      $table.="</tr>";
      $field_id++;
    }
    $this->db()->close();
    return $table;
    }
  }
}
