<div class="container">
  <div class="row panel panel-white ">
    <!-- .panel panel-white -->
      <div class='col-md-4 col-xs-12 '>
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
            <label class="control-label" for="Sources">
              Select Source Provider
            </label>
            <select name="source" id="sources" class="form-control">
              <option value="0">All</option>
              <?php
              foreach ($LeadSources as $key => $value) {
                echo "<option value='" . $value['source'] . "'>" . $value['name'] . "</option>";
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="View leads">
            <!--<button class="btn btn-primary">Download leads</button>-->
          </div>
        </form>
      </div>
      <div class="col-md-4">
        <h4 class="text-center">Send Leads to</h4>
        <label class="control-label" for="date_range1">
          Select Client
        </label>
        <div class="form-group">
          <select name="client" class="form-control" id="client">
            <option value="0">All</option>
            <?php
            foreach ($clients as $key => $value) {
              echo "<option value='" . $value['id'] . "'>" . $value['campaign_name'] . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="controls">
          <a href="javascript:sendtheLeads();" class="btn btn-success">
            Send
          </a>
        </div>
        <hr>

       
      </div>
      <div class="col-md-4"></div>
    </div>
    <div class="row">
      <div class="col-md-10">
         <div id="sendLeadsToClients"></div>
      </div>
    </div>
  <div class="row">
    <div class="col-md-10">
          <table class="table" id="leads">
            <thead>
            <tr>
              <th>ID</th>
              <th>Campaign Name</th>
              <th>State</th>
              <th>Date</th>
              <th>View</th>
              <th>Send</th>
            </tr>
            </thead>
          </table>
    </div>
    <!-- /.panel panel-white -->
  </div>
</div>



<div id="LeadInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="LeadInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Send lead </h4>
      </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
<div id="sendLead" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="sendLead">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Send lead to client</h4>
      </div>
      <div class="modal-body">
        <form action="" id="sendLeadForm">
          <input type="hidden" id="lead_id" >
          <div class="form-group">
            <label class="control-label" for="Clients">
              Select Client
            </label>
            <select name="client" class="form-control" id="clients">
              <option value="0">Send automatically</option>
              <?php
              foreach ($clients as $key => $value) {
                echo "<option value='" . $value['id'] . "'>" . $value['campaign_name'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
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
  $(document).ready(function () {
    var sendLeadForm = $('#sendLeadForm');
    $('#sendLead').on('shown.bs.modal', function (e) {
       $('#sendLead').find('#lead_id').val(e.relatedTarget.getAttribute('attr-id'))
    });
    sendLeadForm.submit(function (e) {
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/leads/sendLead/' ?>',
        data: { id: $('#clients').val(), lead_id: $('#lead_id').val() },
        success: function (data) {
          console.log(data);
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

//        $.ajax({
//          type: "POST",
//          url: '<?php //echo __HOST__ . '/leads/getLeads/' ?>//',
//          data: form.serialize(), // serializes the form's elements.
//          success: function (data) {
//            console.log(data); // show response from the php script.
//          }
//        });
        
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
                "source": $('#sources').val()
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
                "source": $('#sources').val()
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
