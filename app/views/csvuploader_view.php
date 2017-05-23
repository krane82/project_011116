<?php
if ($_POST["action"]=='import')
{
$csv=file($_FILES['file']['tmp_name']);
$csv1=array();
foreach ($csv as $item)
{
$csv1[]=explode(';',$item);
}
print '<div class="table-responsive" style="height:600px;overflow: auto">';
print '<form method="post" id="importCSV">';
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
    print '</form>';
    print '</div>';
    print '<input type="submit" value="Save to DataBase">';
}
else
{

    print '<div class="col-lg-6 col-md-6">
<b>Before uploading the file you need to be sure that columns in uploaded CSV are in this order. Please, prepare it before uploading - sort columns. If your file does not have one of column - just make empty column in it
</b></div>';
    print '<div class="row"><table class="table-bordered"><thead>';
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
    print '</table></div>';
    print '<div><form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="action" value="import">
</form></div>';
}
?>
<script>
    var
</script>
