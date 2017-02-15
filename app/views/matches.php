<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2017
 * Time: 11:15
 */
?>
<head>
    <style>
        /*td {width:200px; border:solid 1px green}
    </style>
</head>
<h1 class="text-center">PostCodes Matches</h1>
<div class="dataTables_wrapper no-footer">
    <form id="getLeads">
        <div class="form-group">
            <label for="datepicker">Select Date Range</label>
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm st form-control" name="start" value="<?php print date('m/01/Y')?>"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm en form-control" name="end" value="<?php print date('m/d/Y')?>"/>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="View leads">
        </div>
    </form>
    <?php
//    print '<pre>';
//    var_dump($data);?>
    <table id ="matches" class="table dataTable no-footer">
<thead>
<tr>
<td>Post Codes</td>
<td>Matches</td>
<td>Client Name</td>
</tr>
</thead>
<tbody>
<?php foreach ($data as $item)
{
print '<tr>';
//print '<td>'.$key.'</td>';
print '<td>'.$item['postcode'].'</td>';
print '<td>'.$item['COUNT( le.id )'].'</td>';
print '<td>'.substr($item['GROUP_CONCAT( cli.campaign_name )'],0,50).'</td>';
print '</tr>';
}
?>
<!--<td>--><?php //print_r($data)?><!--</td>-->
</tbody>
</table>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var form=$('#getLeads');
        var table=$('#matches');
        table=table.DataTable();
        form.submit(function (e) {
            e.preventDefault();
            var start = form.find('input[name=start]').val();
            var end = form.find('input[name=end]').val();
            if(!(start && end)) {
                alert('Please select Date range');
                return;
            }
            table.destroy();
            table=$('#matches').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo __HOST__ . '/Leads_Limits/matchesAjax' ?>",
                    "type": "POST",
                    "data": function ( d ) {
                        return $.extend( {}, d, {
                            "st": $('input[name=start]').val(),
                            "en": $('input[name=end]').val(),
                        } );
                    }
                }
                });
        });
        //daterangepicker
                $('.input-daterange').datepicker({
            multidate: "true"
        });
//        $(function () {
//            $("#matches").dataTable(
//            );
//        })
    });
</script>