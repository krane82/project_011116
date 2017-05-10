<h1 class="text-center">Clients Caps</h1>
<div class="dataTables_wrapper no-footer">
<div class="table-responsive">
    <table class="display table table-condensed table-striped table-hover table-bordered pull-left dataTable no-footer" id="limits">
        <thead>
        <tr>
            <th><input type="text" id="search" placeholder="Campaign Name"></th>
            <th>This Week</th>
            <th>Week Limit</th>
            <th>This Month</th>
            <th>Month Limit</th>
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
</div>
<script>
    var options={
        "iDisplayLength": 100,
        //"aoColumnDefs":[{},{"sType": 'numeric'},{"sType":"numeric"}]
    }
    $(document).ready(function(){
var table=$('#limits').DataTable(options);
        $('#search').click(function(e){
            e.stopPropagation();
        })
        $('#search').on( 'keyup change', function () {
            table
                .columns( 0 )
                .search( this.value )
                .draw();
        } );
    });
</script>