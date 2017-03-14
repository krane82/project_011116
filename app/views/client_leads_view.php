

<div class="panel panel-white ">
  <div class="col-md-3"></div>
  <div class='col-md-5 col-xs-12  text-center'>
    <h3>Download leads by period</h3>
    <br>
    <form action="downloadleads" id="LeadsForClient">
      <div class="form-group">
        <div class="input-daterange input-group" id="datepicker">
          <input type="text" class="input-sm form-control" name="start" value="<?php print date('m/01/Y')?>"/>
          <span class="input-group-addon">to</span>
          <input type="text" class="input-sm form-control" name="end" value="<?php print date('m/d/Y')?>" />
        </div>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Download leads">
      </div>
    </form>
  </div>
  <div class="col-md-2"></div>
</div>

<hr>

<table class="table" id="client_leads">
  <thead>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
    <th>Lead details</th>
    <th>Rejection details</th>
  </tr>
  </thead>
</table>

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
      "order": [[ 0, "desc" ]],
      "aLengthMenu": [
        [100, 200, -1],
        [100, 200, "All"]
    ],
        "oLanguage": {
        "sInfoFiltered": ""
      }
    });
    var modalBody = '<form id="rejectForm">' +
     '<p>Choose your rejection reason: </p>' +
     '<label><input type="radio" name="reason" value="1" required> Outside of nominated area service (2 days to reject)</label><br>'+
     '<label><input type="radio" name="reason" value="2"> Duplicate (2 days to reject)</label><br>'+
     '<label><input type="radio" name="reason" value="3"> Incorrect Phone Number (2 days to reject)</label><br>'+
     '<label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>'+
     '<label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>'+
     '<label><input type="radio" name="reason" value="6"> Unable to contact withing 7 days (7 days to reject)</label><br>'+
        '<textarea style="width:100%" rows="3" name="notes" required></textarea>' +
        '</form>';
    var modalBody1 = '<form id="rejectForm">' +
        '<p>Choose your rejection reason: </p>' +
        '<label><input type="radio" name="reason" value="1" disabled> Outside of nominated area service (2 days to reject)</label><br>'+
        '<label><input type="radio" name="reason" value="2" disabled> Duplicate (2 days to reject)</label><br>'+
        '<label><input type="radio" name="reason" value="3" disabled> Incorrect Phone Number (2 days to reject)</label><br>'+
        '<label><input type="radio" name="reason" value="4"required> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>'+
        '<label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>'+
        '<label><input type="radio" name="reason" value="6"> Unable to contact withing 7 days (7 days to reject)</label><br>'+
        '<textarea style="width:100%" rows="3" name="notes" required></textarea>' +
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
        var sure = true;
        var id = btn.getAttribute('attr-lead-id');
        var permission = btn.getAttribute('data-permission');
        var leadName = btn.getAttribute('attr-client');
        console.log(e.target.dataset.info);
        //console.log(permission);
        if(!e.target.dataset.info){
          var sure = confirm('Are you sure you want to reject lead "' + leadName + '"?');
        }
        if (!sure) {
          return;
        }
        modalka.find('.modal-header').text('Reject lead "' + leadName + '"');
        if(permission) {
          modalka.find('.modal-body').html(modalBody1);
        } else {
          modalka.find('.modal-body').html(modalBody);
        }
        document.querySelector('#LeadInfo .modal-footer').innerHTML = modalFooter;
        modalka.modal("show");
        $('#rejectForm').submit(function(e){
          e.preventDefault();
          var reason = $(this).find('input[name=reason]:checked').val();
          var notes = $(this).find('textarea[name=notes]').val();
          $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/client_leads/reject_Lead/' ?>',
            data: { lead_id: id,
                    reject_reason: reason, notes: notes },
            success: function (data) {
              console.log(data);
              if(data === "Success") { modalka.modal("hide"); table.ajax.reload(); }
            }
          });
        });
      }
      if (e.target && e.target.matches('a.RejectionDetails')) {
        e.preventDefault();
        var btn = e.target;
        var id = btn.getAttribute('attr-lead-id');
        modalka.find('.modal-header').text('Rejection Details');
        modalka.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
        $.ajax({
          type: "POST",
          url: '<?php echo __HOST__ . '/client_leads/rejectInfo/' ?>',
          data: { id: id },
          success: function (data) {
            modalka.find('.modal-body').html(data);
          }
        });
      }
    });
    $('.input-daterange').datepicker({
      multidate: "true"
    });
  });
</script>
