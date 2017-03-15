<?php
class Controller_Migrate
{
  function __construct()
  {
    $this->view = new View();
  }

  function action_index()
  {
    $result = array();
    $preArray = array();
    $leads = array();
    $db = DB::getInstance();
    $s = file_get_contents(_MAIN_DOC_ROOT_.'/dump.csv');
    $array = $this->CSV_string_to_array($s);
    $size = count($array) - 1;
    echo '<pre>';
    for($i=1; $i<$size;$i++) {
      $lead = $this->prepeare($array[$i]);
      $leads[$lead["id"]] = $lead;
    }


//    var_dump($this->sendToLeadsFieldsRel($leads));
    echo '</pre>';
  }

  private function sendToLeadsFieldsRel($leads)
  {

    $db = DB::getInstance();
    $timedate = strtotime('00:00:01 03/15/17');
    foreach ($leads as $lead){
      $records[] = $db->escape(array(
        (int)$lead["id"],
        $lead['source'],
        $lead['full_name'],
        $lead['email'],
        phone_valid($lead['phone']),
        $lead['address'],
        $lead['city'],
        $lead['state'],
        $lead['country'],
        $lead['postcode'],
        $lead['suburb'],
        $lead['system_size'],
        $lead['roof_type'],
        $lead['house_type'],
        $lead['system_for'],
        $lead['electricity'],
        $lead['house_age'],
        $lead['note']
      ));
    }
    $fields = array(
      'id',
      'source',
      'full_name',
      'email',
      'phone',
      'address',
      'city',
      'state',
      'country',
      'postcode',
      'suburb',
      'system_size',
      'roof_type',
      'house_type',
      'system_for',
      'electricity',
      'house_age',
      'note'
    );
    return $db->insert_multi('leads_lead_fields_rel', $fields, $records);
  }

  private function sendToReject($leads)
  {
    $db = DB::getInstance();
    $timedate = strtotime('00:00:01 03/15/17');
    foreach ($leads as $lead){
      $records[] = array(
        (int)$lead["client_id"], (int)$lead["id"], (int)$lead["reject"], $timedate
      );
    }
    $fields = array(
      'client_id', 'lead_id', 'approval', 'date'
    );
    return $db->insert_multi('leads_rejection', $fields, $records);
  }

  private function sendToDelivery($leads)
  {
    $db = DB::getInstance();
    $timedate = strtotime('00:00:01 03/15/17');
    foreach ($leads as $lead){
      $records[] = array(
        (int)$lead["id"], (int)$lead["client_id"], $timedate
      );
    }
    $fields = array(
      'lead_id', 'client_id', 'timedate'
    );
    return $db->insert_multi('leads_delivery', $fields, $records);
  }



  private function sendToLeads($leads){
    $db = DB::getInstance();
    foreach ($leads as $lead) {
      $records[] = array(
        (int)$lead["id"], (int)$lead["campaign_id"], $lead["timestamp"]
      );
    }
//    var_dump($records);
//    die;
    $fields = array(
      'id', 'campaign_id', 'datetime'
    );
    var_dump( $db->insert_multi('leads', $fields, $records) );
  }

  private function prepeare($l){
    $lead = array();
    $lead["id"] = $l[0];
    $lead["source"] = "3a2c693";
    $lead["campaign_id"] = 16;
    $lead["timestamp"] = strtotime($l[2] . " " . $l[3]);
    $lead["client_id"] = $this->findClient($l[4]);
    $lead["house_type"] = $l[5];
    $lead["system_for"] = $l[6];
    $lead["system_size"] = $l[7];
    $lead["roof_type"] = $l[8];
    $lead["house_age"] = $l[9];
    $lead["electricity"] = $l[10];
    $lead["note"] = $l[11];
    $lead["full_name"] = $l[12] . " " . $l[13];
    $lead["phone"] = $l[14];
    $lead["postcode"] = $l[15];
    $lead["address"] = $l[16];
    $lead["suburb"] = $l[17];
    $lead["email"] = $l[18];
    $lead["state"] = $l[19];
    $lead["reject"] = $this->convertStatus($l[20]);
    return $lead;
  }

  private function convertStatus($v){
    switch($v) {
      case 'Accepted':
        return 1;
        break;
      case 'Rejected':
        return 2;
        break;
      default:
        return "FUCK";
    }
  }
  private function findClient($client){
    $sql = "SELECT `id` FROM `clients` WHERE `campaign_name` LIKE '$client'";
    $r = DB::getInstance()->get_row($sql);
    return (int)$r[0];
  }

  private function CSV_string_to_array($string, $separatorChar = ';', $enclosureChar = '"', $newlineChar = "\n") {
    $array = array();
    $size = strlen($string);
    $columnIndex = 0;
    $rowIndex = 0;
    $fieldValue="";
    $isEnclosured = false;
    for($i=0; $i<$size;$i++) {

      $char = $string{$i};
      $addChar = "";

      if($isEnclosured) {
        if($char==$enclosureChar) {

          if($i+1<$size && $string{$i+1}==$enclosureChar){
            // escaped char
            $addChar=$char;
            $i++; // dont check next char
          }else{
            $isEnclosured = false;
          }
        }else {
          $addChar=$char;
        }
      }else {
        if($char==$enclosureChar) {
          $isEnclosured = true;
        }else {
          if($char==$separatorChar) {
            $array[$rowIndex][$columnIndex] = $fieldValue;
            $fieldValue="";
            $columnIndex++;
          }elseif($char==$newlineChar) {
            echo $char;
            $array[$rowIndex][$columnIndex] = $fieldValue;
            $fieldValue="";
            $columnIndex=0;
            $rowIndex++;
          }else {
            $addChar=$char;
          }
        }
      }
      if($addChar!=""){
        $fieldValue.=$addChar;

      }
    }

    if($fieldValue) { // save last field
      $array[$rowIndex][$columnIndex] = $fieldValue;
    }
    return $array;
  }

}