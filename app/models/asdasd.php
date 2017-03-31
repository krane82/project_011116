array( 'db' => '`a`.`lead_id`', 'dt' => 0, 'field' => 'lead_id'  ),
array( 'db' => '`llf`.`full_name`', 'dt' => 1, 'field' => 'full_name'  ),
array('db'=>'`a`.`date`',       'dt' => 2, 'formatter' => function( $d ) {
return date('d/m/Y h:i:s A', $d);
}, 'field'=>'date'),
array( 'db' => '`a`.`approval`',  'dt' => 3, 'formatter'=>function($d){
switch ($d) {
case 0:
return "<span class=\"bg-primary pdfive\">Reject Approved</span>";
break;
case 1:
return "<span class=\"bg-success pdfive\">Approved</span>";
break;
case 2:
return "<span class=\"bg-warning pdfive\">Reject requested</span>";
break;
case 3:
return "<span class=\"bg-danger pdfive\">Reject not Approved</span>";
break;
case 4:
return "<span class=\"bg-info pdfive\">More info required</span>";
break;
default:
return "";
}
},'field'=> 'approval' ),
array('db'=> '`a`.`id`', 'dt'=>4, 'formatter'=>function($d, $row){
if( strtotime('+7 days', $row[2]) < time()){
return 'Out of 7 days';
} else
if( $row[3] == 1 ) {
$button="<a href='#' class='btn leadreject btn-warning' attr-lead-id='$row[0]' attr-client='$row[1]' data-gb='$row[3]'";
if( strtotime('+2 days', $row[2]) < time())
{
$button.=" data-permission='1'";
}
$button.="> Reject</a>";
return $button;
} if($row[3] == 4) {
return "<a href='#' class='btn leadreject btn-danger' data-info='true' attr-lead-id='$row[0]' attr-client='$row[1]' data-gb='$row[3]'> Provide more info</a>";
} else {
return ""; // silence is golden: <button type=\"button\" class=\"btn btn-warning\" disabled=\"disabled\">Reject </button>";
}
}, 'field'=>'id' ),
array('db'=>'`a`.`id`', 'dt'=>5, 'formatter'=>function($d, $row){
if(!($row[3] == 0 OR $row[3] == 3)){
return "<a href='#' class='viewLeadInfo btn btn-primary' attr-lead-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\"  >View</a>";
}
}),
array( 'db' => '`a`.`decline_reason`',  'dt' => 6, 'formatter'=>function($d, $row) {
if (!$d and $row[3] > 1) {
return "<a href='#' class='RejectionDetails btn btn-primary' attr-lead-id='$row[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\"  >View</a>";
}
},
'field' => 'decline_reason')