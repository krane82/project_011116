

<table class="table" id="client_leads">
  <thead>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Date</th>
    <th>Status</th>
    <th>Decline Reason</th>
    <th>Action</th>
    <th>Download</th>
  </tr>
  </thead>
</table>

<!--<div class="download-leads">-->
<!--  <input type="text" class="lead-date-range">-->
<!--  <button class="search"></button>-->
<!--</div>-->


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
    var leads = $('#client_leads');
    var table = leads.DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo __HOST__ . "/client_leads/" ?>getLeads",
        "type": "POST"
      },
      "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ 4,5,6 ] }
      ],
      "order": [[ 0, "asc" ]]
    });
    var modalBody = '<form id="rejectForm">' +
    '<!-- <select class="form-control">' + 
     ' <option value="volvo">Volvo</option>' +
     ' <option value="saab">Saab</option>' +
     ' <option value="mercedes">Mercedes</option>' +
     ' <option value="audi">Audi</option>' +
     '</select> -->' +
     '<p>Describe your rejection reason: </p>' +
     '<div class="form-group"><input class="form-control" type="text" placeholder="Reject reason" required name="reason"> </div>' +
     '</form>';
    var modalFooter = '<input form="rejectForm" type="submit" class="btn btn-primary reject" value="Reject this lead"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    var tt = document.querySelector('#client_leads');
    var modalka = $('#LeadInfo');
    tt.addEventListener('click', function(e){
      if (e.target && e.target.matches('a.viewLeadInfo')) {
        e.preventDefault();
        var btn = e.target;
        var id = btn.getAttribute('attr-lead-id');
        modalka.find('.modal-header').text('Lead details');
        modalka.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $.ajax({
          type: "POST",
          url: '<?php echo __HOST__ . '/client_leads/LeadInfo/' ?>',
          data: { id: id },
          success: function (data) {
            modalka.find('.modal-body').html(data);
          }
        });
      }
      if (e.target && e.target.matches('a.leadreject')) {
        e.preventDefault();
        var btn = e.target;
        var id = btn.getAttribute('attr-lead-id');
        var leadName = btn.getAttribute('attr-client');
        var sure = confirm('Are you sure you want to reject lead "' + leadName + '"?');
        if (!sure) {
          return;
        }
        modalka.find('.modal-header').text('Reject lead "' + leadName + '"');
        modalka.find('.modal-body').html(modalBody);
        document.querySelector('#LeadInfo .modal-footer').innerHTML = modalFooter;
        modalka.modal("show");
        $('#rejectForm').submit(function(e){
          e.preventDefault();
          var reason = $(this).find('input[name=reason]').val();
          $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/client_leads/reject_Lead/' ?>',
            data: { lead_id: id,
                    reject_reason: reason },
            success: function (data) {
              if(data === "Success") { modalka.modal("hide"); table.ajax.reload(); }
            }
          });
        });
      }
    });
  });
</script>
