<div class="container">
  <div class="row">
    <!-- .panel panel-white -->
    <div class="panel panel-white ">
    <div class="col-md-10">
      <div class="row">
          <table class="table" id="approvals">
            <thead>
            <tr>
              <th>ID</th>
              <th>Client</th>
              <th>Recieving date</th>
              <th>Rejection date</th>
              <th>Reason  </th>
<!--              <th>Note</th>-->
              <th>Status</th>
              <th>View</th>
              <th>Action</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
              <th>ID</th>
              <th>Client</th>
              <th>Recieving date</th>
              <th>Rejection date</th>
              <th>Reason  </th>
<!--              <th>Note</th>-->
              <th>Status</th>
              <th>View</th>
              <th>Action</th>
            </tr>
            </tfoot>
          </table>
      </div>
    </div>
    <!-- /.panel panel-white -->
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


<script type="text/javascript">

$(document).ready(function () {
    var approvals = $("#approvals");

    // $('#approvals tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    // } );
    table = approvals.DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo __HOST__ . '/approvals/GetApprovals/' ?>",
        "type": "POST"
      },
      "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ 4 ] }
      ],
      "order": [[ 0, "asc" ]],
      "aLengthMenu": [
          [100, 200, -1],
          [100, 200, "All"]
      ],
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
      ,"initComplete": function ()
      {
        var r = $('#approvals tfoot tr');
        r.find('th').each(function(){
          $(this).css('padding', 8);
        });
        $('#approvals thead').append(r);
        $('input').css('text-align', 'center');
      }
    });

    $('#approvals tfoot th').each( function () {
        var title = $('#approvals thead tr:eq(0) th').eq( $(this).index() ).text();
        var html_string = '';
        var input_style = ' style="width:100%; padding:1px !important; margin-left:-2px; margin-bottom: 0px;"';
        var select_style = ' style="width:auto; padding:1px; margin-left:-2px; margin-bottom: 0px; height: 24px;"';

        if ($(this).index() == 2 || $(this).index() == 3){
          html_string = '<input type="text" ' + input_style + ' class="datepicker">';
        }
        else if ( $(this).index() == 5 ) {
          html_string = '<select ' + select_style + '>' +
          '<option value="">Select Status...</option>' +
          '<option value="0">Request Approved</option>' +
          '<option value="3">Request Disapproved</option>' +
          '<option value="2">Requesting to Reject</option>' +
          '</select>';
//            console.log(this);
//            var select = $('<select style="width:auto; padding:1px; margin-left:-2px; margin-bottom: 0px; height: 24px;"><option value="">Select Status...</option><option value="0">Request Approved</option><option value="1">Request Disapproved</option><option value="2">Requesting to Reject</option></select>').appendTo($(this)).on('change',
//                function (){
//                var val = $.fn.dataTable.util.escapeRegex(
//                    $(this).val()
//                );
////                $(this)
////                    .search(val ? '^' + val + '$' : '', true, false)
////                    .draw();
//            });
        }
        else if ( $(this).index() < 5 ){
          html_string = '<input type="text" ' + input_style + ' placeholder="Search ' + $.trim(title) + '"/>';
        }

        $(this).html(html_string);
        // $(this).html( '<input class="searchbox" type="text" placeholder="Search '+title+'" />' );
      } );

    $( ".datepicker" ).datepicker({
      dateFormat: 'yy-mm-dd'
    });
 
    // Apply the search
    table.columns().eq( 0 ).each( function ( colIdx ) {
        $( 'input, select', table.column( colIdx ).footer() ).on( 'keyup change', function () {
            table
                .column( colIdx )
                .search( this.value )
                .draw();
        } );
    } );
});
    function acceptLead(id, client_id){
      $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/approvals/accept_lead/' ?>',
        data: { id: id, client_id: client_id },
        success: function (data) {
          table.ajax.reload();
        }
      });
    }
    function rejectLead(id, client_id){
      $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/approvals/rejectLead/' ?>',
        data: { id: id, client_id: client_id },
        success: function (data) {
          table.ajax.reload();
        }
      });
    }
    function moreInfo(id, client_id) {
      $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/approvals/moreInfo/' ?>',
        data: { id: id, client_id: client_id },
        success: function (data) {
          table.ajax.reload();
        }
      });
    }

</script>
