<h3>Planning</h3>
<table id="plan" class="display table table-condensed table-striped table-hover table-bordered pull-left">
    <thead>
    <tr>
        <th>Affiliate</th>
        <th colspan="2">NSW<br>fact plan</th>
        <th colspan="2">QLD<br>fact plan</th>
        <th colspan="2">SA<br>fact plan</th>
        <th colspan="2">TAS<br>fact plan</th>
        <th colspan="2">VIC<br>fact plan</th>
        <th colspan="2">WA<br>fact plan</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item=>$key)
{
   print '<tr>';
    print '<td>'.$item.'</td>';
    print '<td style="width:5%">'.$key['NSW']['count'].'</td><td style="width:5%"><input type="text" style="width:2em" value="'.$key['NSW']['plan'].'"></td>';
    print '<td style="width:5%">'.$key['QLD']['count'].'</td><td style="width:5%"><input type="text" style="width:2em" value="'.$key['QLD']['plan'].'"></td>';
    print '<td style="width:5%">'.$key['SA']['count'].'</td><td style="width:5%"><input type="text" style="width:2em" value="'.$key['SA']['plan'].'"></td>';
    print '<td style="width:5%">'.$key['TAS']['count'].'</td><td style="width:5%"><input type="text" style="width:2em" value="'.$key['TAS']['plan'].'"></td>';
    print '<td style="width:5%">'.$key['VIC']['count'].'</td><td style="width:5%"><input type="text" style="width:2em" value="'.$key['VIC']['plan'].'"></td>';
    print '<td style="width:5%">'.$key['WA']['count'].'</td><td style="width:5%"><input type="text" style="width:2em" value="'.$key['WA']['plan'].'"></td>';
    print '</td>';
}
?>
    </tbody>
</table>