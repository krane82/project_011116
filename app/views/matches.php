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
    <form action="table-leads" id="getLeads">
        <div class="form-group">
            <label for="datepicker">Select Date Range</label>
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm st form-control" name="start"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm en form-control" name="end"/>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="View leads">
        </div>
    </form>
    <?php
    print '<pre>';
//    var_dump($data);?>
    <table class="display table table-condensed table-striped table-hover table-bordered pull-left dataTable no-footer">
<thead>
<tr>
<td>Post Codes</td>
<td>Matches</td>
<td>Client Name</td>
</tr>
</thead>
<tbody>
<?php foreach ($data as $key=>$item)
{
print '<tr>';
//print '<td>'.$key.'</td>';
print '<td>'.$item['postcode'].'</td>';
print '<td>'.$item['count'].'</td>';
print '<td>'.substr($item['client'],0,50).'</td>';
print '</tr>';
}
?>
<!--<td>--><?php //print_r($data)?><!--</td>-->
</tbody>
</table>
</div>


<script type="text/javascript">
    function sendtheLeads() {

        var start = document.querySelector('input[name=start]').value;
        var end = document.querySelector('input[name=end]').value;
        var client = document.querySelector('select[name=client]').value;

        if(!(start && end)) {
            alert('Please select Date range');
            return;
        }
        console.log(start, end, client);
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/leads/sendLead/' ?>',
            data: { start: start, end: end, client: client},
            success: function (data) {
                console.log(data);
                document.querySelector('#sendLeadsToClients').innerHTML = data;
            }
        });
    }
    var loader = $('<div class="loader">Loading...</div>');
    $(document).ready(function () {
        var sendLeadForm = $('#sendLeadForm');
        $('#sendLead').on('shown.bs.modal', function (e) {
            $('#sendLead').find('#lead_id').val(e.relatedTarget.getAttribute('attr-id'))
        });
        sendLeadForm.submit(function (e) {
            e.preventDefault();
            $(".result").html(loader);
            $.ajax({
                type: "POST",
                url: '<?php echo __HOST__ . '/leads/sendLead/' ?>',
                data: { id: $('#clients').val(), lead_id: $('#lead_id').val() },
                success: function (data) {
                    $(".result").html(data);
//          sendLeadForm.find('.modal-body').innerHTML(data);
                }
            });
        });
        var form = $('#getLeads');
        var counter = 0;
        var table =  $('#leads');
        form.submit(function (e) {
            e.preventDefault();
            var start = form.find('input[name=start]').val();
            var end = form.find('input[name=end]').val();
            var data = form.serialize();
            if(!(start && end)) {
                alert('Please select Date range');
                return;
            }
            if(counter == 0){
                table = table.DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo __HOST__ . '/leads/getLeads/' ?>",
                        "type": "POST",
                        "data": function ( d ) {
                            return $.extend( {}, d, {
                                "st": $('input[name=start]').val(),
                                "en": $('input[name=end]').val(),
                                "source": $('#sources').val(),
                                "state": $('#state').val()
                            } );
                        }
                    },
                    "aoColumnDefs": [
                        { 'bSortable': false, 'aTargets': [ 4 ] }
                    ],
                    "order": [[ 0, "asc" ]],
                    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                        $(nRow).find('.viewLeadInfo').on('click', function () {
                            var id = $(this).attr('attr-id');
                            $.ajax({
                                type: "POST",
                                url: '<?php echo __HOST__ . '/leads/LeadInfo/' ?>',
                                data: { id: id },
                                success: function (data) {
                                    $('#LeadInfo').find('.modal-body').html(data);
                                }
                            });
                        });
                    }
                });
                counter++;
            }
            else {
                table.destroy();
                table = $('#leads').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo __HOST__ . '/leads/getLeads/' ?>",
                        "type": "POST",
                        "data": function ( d ) {
                            return $.extend( {}, d, {
                                "st": $('input[name=start]').val(),
                                "en": $('input[name=end]').val(),
                                "source": $('#sources').val(),
                                "state": $('#state').val()
                            } );
                        }
                    },
                    "aoColumnDefs": [
                        { 'bSortable': false, 'aTargets': [ 4 ] }
                    ],
                    "order": [[ 0, "asc" ]],
                    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                        $(nRow).find('.viewLeadInfo').on('click', function () {
                            var id = $(this).attr('attr-id');
                            $.ajax({
                                type: "POST",
                                url: '<?php echo __HOST__ . '/leads/LeadInfo/' ?>',
                                data: { id: id },
                                success: function (data) {
                                    $('#LeadInfo').find('.modal-body').html(data);
                                }
                            });
                        });
                    }
                });
            }
        });
        $('.input-daterange').datepicker({
            multidate: "true"
        });

    });
</script>