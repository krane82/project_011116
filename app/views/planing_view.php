<h3>Planning</h3>

<form id="fact">
    <div class="form-group">
        <label for="datepicker">Select Date Range</label>
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm st form-control" name="start" value="<?php print date('m/01/Y')?>"/>
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm en form-control" name="end" value="<?php print date('m/d/Y')?>"/>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Show Leads Count">
    </div>
</form>
<table id="plan" class="display table table-condensed table-striped table-hover table-bordered pull-left">
    <thead>
    <tr>
        <th>Affiliate</th>
        <th colspan="2">NSW<br>fact | plan</th>
        <th colspan="2">QLD<br>fact | plan</th>
        <th colspan="2">SA<br>fact | plan</th>
        <th colspan="2">TAS<br>fact | plan</th>
        <th colspan="2">VIC<br>fact | plan</th>
        <th colspan="2">WA<br>fact | plan</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item=>$key)
{
   print '<tr class="campaign" id="'.$key['id'].'">';
    print '<td>'.$item.'</td>';
    print '<td style="width:6%">'.$key['NSW']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="NSW" style="width:2em" value="'.$key['NSW']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['QLD']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="QLD" style="width:2em" value="'.$key['QLD']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['SA']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="SA" style="width:2em" value="'.$key['SA']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['TAS']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="TAS" style="width:2em" value="'.$key['TAS']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['VIC']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="VIC" style="width:2em" value="'.$key['VIC']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['WA']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="WA" style="width:2em" value="'.$key['WA']['plan'].'"></td>';
    print '</td>';
    print '</tr>';
}
?>
    </tbody>
</table>
<script>
    $(document).ready(function () {
    var campaign=$('.campaign');
    campaign.bind("input", function(e) {
        if (e.target.value.match(/[^0-9]/g)) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        }
    });
    campaign.on('change',function(e){
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/campaigns/plans_update/' ?>',
            data: { 'campaign':this.id, 'state': event.target.className, 'value':event.target.value},
            success: function (data) {
            }
        });
    });
     var form=$('#fact');
     form.submit(function(e) {
     e.preventDefault();
        var start = form.find('input[name=start]').val();
        var end = form.find('input[name=end]').val();
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/campaigns/planing_ajax/' ?>',
            data: { 'start':start, 'end':end},
            success: function (data) {
               var resp=JSON.parse(data);
                for (var i=0;i<campaign.length;i++){
                    campaign[i].innerHTML=resp[i];
                }
            }
        });
    });    
    $('.input-daterange').datepicker({
        multidate: "true"
    });
    });
</script>