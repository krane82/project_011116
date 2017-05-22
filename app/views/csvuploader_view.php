<?php
if ($_POST["action"]=='import')
{
$csv=file($_FILES['file']['tmp_name']);
$csv1=array();
foreach ($csv as $item)
{
$csv1[]=explode(';',$item);
}
print    '<div class="table-responsive" style="height:600px;overflow: auto">';
print '<form method="post" id="importCSV">';
    print '<input type="submit" class="btn btn-lg btn-default" value="Save">';
    print '<b> select suitable name of column, columns without name will not be added to database. Name for first column is required</b>';
    print '</form>';
print '<table  class="">';
    print '<tr>';
        $i=1;
        while ($i<15)
        {
        print '<td><select name="val'.$i.'" form="importCSV" style="background-color: inherit; border-color: transparent;width:100px" name="1">';
                print	'<option value=""></option>';
                print	'<option value="full_name">Full Name</option>';
                print	'<option value="email">Email</option>';
                print	'<option value="phone_number">Phone number</option>';
                print	'<option value="roof">Type of Roof</option>';
                print	'<option value="address">Address</option>';
                print	'<option value="system_size">System size</option>';
                print	'<option value="house">Type of house</option>';
                print	'<option value="system_for">System for</option>';
                print	'<option value="electricity">Electricity Provider</option>';
                print	'<option value="age">House age</option>';
                print	'<option value="state">State</option>';
                print	'<option value="zip">Postcode</option>';
                print	'<option value="suburb">Suburb</option>';
                print	'<option value="notes">Notes</option>';
                print	'</select></td>';
                $i++;
                }
                print '</tr>';
    $i=true;
    foreach ($csv1 as $item)
    {
    print '<tr ';
    if ($i) print 'class="pair">';
    else print 'class="impair">';
    foreach ($item as $row)
    {
    print '<td class="tabBar">';
        print $row;
        print '</td>';
    }
    print '</tr>';
    $i=!$i;
    }
    print '</table>';
}
else{
print '<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="action" value="import">
</form>';
}
?>