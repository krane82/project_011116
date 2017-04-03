<h1 class="text-center">Clients Caps</h1>
<div class="dataTables_wrapper no-footer">
    <table class="display table table-condensed table-striped table-hover table-bordered pull-left dataTable no-footer" id="limits">
        <thead>
        <tr>
            <td>Campaign Name</td>
            <td>This Week</td>
            <td>Week Limit</td>
            <td>This Month</td>
            <td>Month Limit</td>
        </tr>
        </thead>
        <?php
//        print '<pre>';
//        var_dump($data);
        foreach ($data as $item){
            print '<tr>';
            print '<td>'.$item['campaign_name'].'</td>';
            print '<td>'.$item['thisWeek'].'</td>';
            ($item["weekly"]!=null)  ?  print '<td>' .$item["weekly"] . '</td>'  :print '<td>Unlimited</td>';
            print '<td>'.$item['thisMonth'].'</td>';
            ($item["monthly"]!=null) ?  print '<td>' .$item["monthly"] . '</td>' :print '<td>Unlimited</td>';
            print '</tr>';
        }
        ?>
    </table>
</div>
<script>
    var options={
        "iDisplayLength": 100,
        //"aoColumnDefs":[{},{"sType": 'numeric'},{"sType":"numeric"}]
    }
    $(document).ready(function(){
$('#limits').DataTable(options);
    });
</script>