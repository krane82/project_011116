<?php
class Controller_Migrate
{
  function __construct()
  {
    $this->view = new View();
  }

  function action_index()
  {
    $s = file_get_contents(_MAIN_DOC_ROOT_.'/dump.csv');
    $array = $this->csvstring_to_array($s);
    $size = count($array) - 1;
    echo '<pre>';
    for($i=1; $i<$size;$i++) {
      print_r($array["$i"]);
      echo $i;
    }

//    print_r($array);
    echo '</pre>';
  }
  private function csvstring_to_array($string, $separatorChar = ';', $enclosureChar = '"', $newlineChar = "\n") {

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