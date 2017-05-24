<?php
if ($_POST["action"]=='import')
{
$csv=file($_FILES['file']['tmp_name']);
$csv1=array();
$serArr=array();
foreach ($csv as $item)
{
$csv1[]=explode(';',$item);
}
    for ($i=0;$i<count($csv);$i++)
    {
        $serArr[$i]['source']='15dd4cb';
        $serArr[$i]['full_name']=$csv1[$i][0];
        $serArr[$i]['email']=$csv1[$i][1];
        $serArr[$i]['phone']=$csv1[$i][2];
        $serArr[$i]['roof_type']=$csv1[$i][3];
        $serArr[$i]['address']=$csv1[$i][4];
        $serArr[$i]['system_size']=$csv1[$i][5];
        $serArr[$i]['house_type']=$csv1[$i][6];
        $serArr[$i]['system_for']=$csv1[$i][7];
        $serArr[$i]['electricity']=$csv1[$i][8];
        $serArr[$i]['house_age']=$csv1[$i][9];
        $serArr[$i]['state']=$csv1[$i][10];
        $serArr[$i]['postcode']=$csv1[$i][11];
        $serArr[$i]['suburb']=$csv1[$i][12];
        $serArr[$i]['note']=$csv1[$i][13];
    }
$serArr=serialize($serArr);
$serArr=urlencode($serArr);
    print '<div class="table-responsive" style="height:600px;overflow: auto">';
print '<div class="col-lg-6 col-sm-6"><b>Before uploading the file you need to be sure that columns in uploading file is the same as in table header. Please, prepare it before uploading - sort columns as in table header. If your file does not have one of column - just make empty column in it</b></div>';
print '<table  class="display table table-condensed table-striped table-hover table-bordered clients responsive pull-left dataTable no-footer "><thead>';
                print '<tr>';
                print	'<th>Full Name</th>';
                print	'<th>Email</th>';
                print	'<th>Phone number</th>';
                print	'<th>Type of Roof</th>';
                print	'<th>Address</th>';
                print	'<th>System size</th>';
                print	'<th>Type of house</th>';
                print	'<th>System for</th>';
                print	'<th>Electricity Provider</th>';
                print	'<th>House age</th>';
                print	'<th>State</th>';
                print	'<th>Postcode</th>';
                print	'<th>Suburb</th>';
                print	'<th>Notes</th>';

      print '</tr></thead';
    foreach ($csv1 as $item)
    {
    print '<tr>';
    foreach ($item as $row)
    {
        print '<td style="max-width:200px">';
        print $row;
        print '</td>';
    }
    print '</tr>';
       }
    print '</table>';
    print '</div>';
    print '<div class="col-lg-12 col-sm-12 p"><form id="uploadCSV">';
    print '<input type="hidden" name="array" value="'.$serArr.'">';
    print '<input type="submit" class="btn btn-success" value="Save to DataBase">';
    print '</form></div>';
    print '<div class="row"><div class="col-lg-12 col-sm-12 col-xs-12" id="infodiv"></div></div>';
} else {
?>
<div class="row">
<div class="col-lg-6 col-md-6">
<b>Before uploading the file you need to be sure that columns in uploaded CSV are in this order. Please, prepare it before uploading - sort columns. If your file does not have one of column - just make empty column in it
</b></div>
    <div class="col-log-12 col-md-12 p">
<table class="table-bordered"><thead>
<tr>
<th>Full Name</th>
<th>Email</th>
<th>Phone number</th>
 <th>Type of Roof</th>
<th>Address</th>
<th>System size</th>
<th>Type of house</th>
<th>System for</th>
<th>Electricity Provider</th>
<th>House age</th>
<th>State</th>
<th>Postcode</th>
<th>Suburb</th>
<th>Notes</th>
</tr></thead>
</table></div></div>
<div><form method="post" enctype="multipart/form-data">
    <div class="col-lg-12 p"><label class="btn btn-success">Select File<input type="file" style="display:none" onchange="this.parentNode.nextElementSibling.innerHTML=this.value" name="file"></label><span></span></div>
    <div class="col-lg-12 p"><input type="submit" class="btn btn-success" name="action" value="import"></div>
</form></div>
<?php }?>
<script>
    $('#uploadCSV').submit(function(e){
       e.preventDefault();
        $('#infodiv').html('Wait please while system uploads leads. . .');
        $.ajax(
            {
            type: "POST",
            url: '<?php echo __HOST__ . '/leads/sendLeadsFromCSV/' ?>',
            data: {'array':$('input[name=array]').val()},
            success:function(respond){
          //  console.log(respond);
                $('#infodiv').html(respond);
        }
            }
        )
    });
</script>

