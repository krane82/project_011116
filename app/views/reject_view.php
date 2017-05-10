<div class="col-md-12">
  <div class="row">
      <table class="table" id="approvals">
        <thead>
        <tr>
          <th>ID</th>
          <th>Client</th>
          <th>Receiving date</th>
          <th>Open email</th>
          <th>Status</th>
          <th>View</th>
          <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <th>ID</th>
          <th>Client</th>
          <th>Received date</th>
          <th>Open email</th>
          <th>Status</th>
          <th>View</th>
          <th>Action</th>
        </tr>
        </tfoot>
      </table>
  </div>
</div>


<div id="LeadInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="LeadInfo">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Lead details</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var approvals = $("#approvals");
        var table = approvals.DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo __HOST__ . '/reject/GetApprovals/' ?>",
                "type": "POST"
            },
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [4]}
            ],
            "order": [[3, "desc"]],
            "aLengthMenu": [
                [100, 200, -1],
                [100, 200, "All"]
            ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                $(nRow).find('.viewLeadInfo').on('click', function () {
                    var id = $(this).attr('attr-id');
                    console.log(id);
                    $.ajax({
                        type: "POST",
                        url: '<?php echo __HOST__ . '/leads/LeadInfo/' ?>',
                        data: {id: id},
                        success: function (data) {
                            $('#LeadInfo').find('.modal-body').html(data);
                        }
                    });
                });
            },
//            "initComplete": function () {
//                var r = $('#approvals tfoot tr');
//                r.find('th').each(function(){
//                    $(this).css('padding', 8);
//                });
//                $('#approvals thead').append(r);
//                $('input').css('text-align', 'center');
//            },
            "oLanguage": {
                "sInfoFiltered": ""
            }
        });
        //This is the function to add search fields in datatables
            $('table thead th').each(function () {
                var title = $(this).text();
                if (title == 'Client') {
                    $(this).html('<input type="text" style="width:100px" placeholder="' + title + '" />');
                }
            });
            //
            table.columns().every(function () {
                var that = this;
                $('input', this.header()).click(function (e) {
                    e.stopPropagation();
                })
                $('input', this.header()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        //End of function

    });
    function delInfo(id, client_id) {
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/reject/delete/' ?>',
            data: { id: id, client_id: client_id },
            success: function (data) {
                table.ajax.reload();
            }
        });
    }

    function rejectLead(id, client_id){
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/reject/rejectLead/' ?>',
            data: { id: id, client_id: client_id },
            success: function (data) {
                table.ajax.reload();
            }
        });
    }

    function approveReject(id, client_id){
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/reject/approveReject/' ?>',
            data: { id: id, client_id: client_id },
            success: function (data) {
                table.ajax.reload();
            }
        });
    }
</script>