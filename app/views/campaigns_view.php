
<h1 class="text-center">Campaigns</h1>
<table id="campaigns" class="display table table-condensed table-striped table-hover table-bordered pull-left" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>id</th>
    <th>Name</th>
    <th>Source</th>
    <th>Cost</th>
    <th>Status</th>
    <th>Action</th>
    <th>Edit fields</th>
  </tr>
  </thead>
</table>

<!-- #addNewCampaign -->
<h3>Add new campaign</h3>

  <form action="addNewCampaign" class="form-inline" id="addNewCampaign" method="POST">
      <div class="form-group">
        <input type="text" class="form-control" required name="name" placeholder="Campaign name">
      </div>
      <div class="form-group">
        <input type="number" class="form-control" required  name="cost" placeholder="Cost">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Add new campaign</button>
      </div>
  </form>
  <div class="addsuccess bg-success"></div>
<!-- /#addNewCampaign -->

<!-- #editFields.modal fade -->
<div id="editFields" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editFields">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <!-- .modal-body -->
        <div class="modal-body">
          <table id="editfieldstable" class="table" cellspacing="0" width="100%">
            <thead>
            <tr>
              <th>Field Name</th>
              <th>Status</th>
              <th>Mandatory</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
          <hr>
          <div class="text-center">
          <button class="btn btn-primary embed">Generate embed</button>
            <br>
            <div id="embed-code"></div>
          </div>
        </div>
        <!-- /.modal-body -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<!-- /#editFields.modal  -->

<!-- /#editCampaign.modal  -->
<div class="modal fade" id="editCampaign"  tabindex="-1" role="dialog" aria-labelledby="editCampaign">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Edit Campaign</h4>
      </div>
      <form id="editCampaignform" action="update_campaign" method="post">
      <div class="modal-body">
        <input type="hidden" name="id" class="campaign-id">
          <div class="form-group">
            <label for="recipient-name" class="control-label">Campaign Name:</label>
            <input type="text" class="form-control" name="name" id="campaign-name">
          </div>
          <div class="form-group">
            <label for="cost">Cost</label>
            <input type="number" class="form-control" name="cost" id="campaign-cost">
          </div>
          <div class="form-group">
            <input type="checkbox" name="status" checked>
          </div>
      <div class="bg-success success"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update info</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    var table =  $('#campaigns').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": "ajax_get",
      aaSorting:[],
      "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ 4, 5, 6 ] }
      ],
      "order": [[ 0, "asc" ]],
      "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        var id = nRow.querySelector('td:nth-child(1)').innerHTML;
        var name = nRow.querySelector('td:nth-child(2)').innerHTML;
        var cost = nRow.querySelector('td:nth-child(4)').innerHTML;
        var source = nRow.querySelector('td:nth-child(3)').innerHTML;
        var status = nRow.querySelector('input').value;
        nRow.querySelector('.delete-campaign').setAttribute('attr-id', id);
        nRow.querySelector('.edit-campaign').setAttribute('attr-id', id);
        nRow.querySelector('.edit-campaign').setAttribute('attr-name', name);
        nRow.querySelector('.edit-campaign').setAttribute('attr-cost', cost);
        nRow.querySelector('.edit-campaign').setAttribute('attr-status', status);
        nRow.querySelector('.edit-campaign-fields').setAttribute('attr-id', id);
        nRow.querySelector('.edit-campaign-fields').setAttribute('attr-source', source);
        nRow.querySelector('.edit-campaign-fields').setAttribute('attr-name', name);
        nRow.querySelector('.edit-campaign-fields').addEventListener('click', function(event) {
          var button = event.currentTarget;
          var id = button.getAttribute('attr-id'); // Extract info from data-* attributes
          var source = button.getAttribute('attr-source'); // Extract info from data-* attributes
          document.querySelector('#embed-code').innerHTML = "";
          document.querySelector('.embed').setAttribute("attr-id", id);
           document.querySelector('.embed').addEventListener('click', function(event){
             var t = event.currentTarget;
             var id = t.getAttribute('attr-id');
             $.ajax({
               type: "POST",
               url: "get_embed",
               data: {
                 campaign_id: id
               },
               success: function(data) {
                 document.querySelector('#embed-code').innerHTML = data;
               }
             });
           });
          $.ajax({
            type: "POST",
            url: "generete_campaign_fields",
            data: {"campaign-id": id},
            success: function(data) {
              document.querySelector('#editfieldstable tbody').innerHTML = data;
              (function(){
                function udpdatedb(field, obj){
                  if(field === 'isactive') {
                    var attr_field_id = obj.attr('attr-field-id');
                    var campaign_id = obj.attr('attr-campaign-id');
                    var value = obj.attr('value');
                    $.ajax({
                      type: "POST",
                      url: "update_campaign_fields_rel",
                      data: {
                        attr_field_id: attr_field_id,
                        campaign_id: campaign_id,
                        value: value,
                        isactive: 1
                      },
                      success: function(data) {
                        console.log(data);
                      }
                    });
                  }
                    else if(field === 'mandatory') {
                      var attr_field_id = obj.attr('attr-field-id');
                      var campaign_id = obj.attr('attr-campaign-id');
                      var value = obj.attr('value');
                      $.ajax({
                        type: "POST",
                        url: "update_campaign_fields_rel",
                        data: {
                          attr_field_id: attr_field_id,
                          campaign_id: campaign_id,
                          value: value,
                          mandatory: 1
                        },
                        success: function(data) {
                          console.log(data);
                        }
                      });
                    }
                  }
                $('.mandatory').each(function(){
                  $(this).bootstrapSwitch();
                  $(this).on('switchChange.bootstrapSwitch', function(event, state) {
                    this.value = Number(state);
                    udpdatedb('mandatory', $(this));
                  });
                });
                $('.is_active').each(function(){
                  $(this).bootstrapSwitch();
                  $(this).on('switchChange.bootstrapSwitch', function(event, state) {
                    this.value = Number(state);
                    udpdatedb('isactive', $(this));
                  });
                });
              })();
            }
          });
          var modal = document.querySelector('#editFields');
          modal.querySelector('.modal-header h4').innerHTML = 'Edit fields for: ' + name;
        });

        nRow.querySelector('.edit-campaign').addEventListener('click', function (event) {
          var button = event.currentTarget;
          var id = button.getAttribute('attr-id');      // Extract info from data-* attributes
          var name = button.getAttribute('attr-name');  // Extract info from data-* attributes
          var cost = button.getAttribute('attr-cost');  // Extract info from data-* attributes
          var status = button.getAttribute('attr-status');
          var modal = $('#editCampaign');
          modal.find('.modal-title').text('Edit campaign: ' + name);
          modal.find('.modal-body .campaign-id').val(id);
          modal.find('.modal-body #campaign-name').val(name);
          modal.find('.modal-body #campaign-cost').val(cost);
          modal.find('.modal-body #campaign-status').val(status);
        });
        $(nRow).find('.delete-campaign').click(function(){
          var id = $(this).attr("attr-id");
          var sure = confirm('Are you sure you want to delete this?');
          if (!sure) {
            return;
          }
          $.ajax({
            type: "POST",
            url: "delete_campaign",
            data: {"id": id},
            success: function(data) {
              console.log(data);
            }
          });
          table.ajax.reload();
          return false;
        });
      }
    });

    $("[name='status']").bootstrapSwitch()
      .on('switchChange.bootstrapSwitch', function(event, state) {
        console.log(state); // true | false
        this.value = Number(state);
      });

    $('#editCampaign').on('hidden.bs.modal', function () {
      table.ajax.reload();
      document.querySelector('.success').innerHTML = '';
    });

    var addfrm = $('#addNewCampaign');
    addfrm.submit(function (ev) {
        $.ajax({
            type: addfrm.attr('method'),
            url: 'addNewCampaign',
            data:  addfrm.serialize(),
            success: function (data) {
              document.querySelector('.addsuccess').innerHTML = '<p>' + data + '</p>';
              table.ajax.reload();
            }
        });
        ev.preventDefault();
    });

    var frm = $('#editCampaignform');
    frm.submit(function (ev) {
        $.ajax({
            type: frm.attr('method'),
            url: 'update_campaign',
            data:  frm.serialize(),
            success: function (data) {
              document.querySelector('.success').innerHTML = '<p>' + data + '</p>';
            }
        });
        ev.preventDefault();
    });
  });
</script>
