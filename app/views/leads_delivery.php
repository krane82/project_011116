<div class="container">
  <div class="row">
    <div class="col-md-10">
    <div class="table-responsive">
          <table class="table responsive" id="leads_delivery">
            <thead>
            <tr>
              <th>ID</th>
              <th>lead id</th>
              <th>Postcode match</th>
              <th>Date</th>
              <th>Campaign Name</th>
              <th>Open email</th>
              <th>Last open</th>
              <th>Conversations</th>
            </tr>
            </thead>
          </table>
        </div>
    </div>
    <!-- /.panel panel-white -->
  </div>
</div>
<div class="modal fade" id="modalka"  tabindex="-1" role="dialog" aria-labelledby="editCampaign">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Conversation: </h4>
            </div>
                 <div class="modal-body" id="chat">        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<!--                    <button type="submit" class="btn btn-primary">Update info</button>-->
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
  $(document).ready(function () {
    var table =  $('#leads_delivery').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax": {
            "url": "<?php echo __HOST__ . '/leads/ajax_delivery/' ?>",
            "type": "POST"
          }
    });
    table.order([3, 'desc']).draw();
  });
  $('#leads_delivery').click(function(e)
  {
      if(e.target.type!='button')
      {
          return;
      }
       $.ajax({
          type: "POST",
          url: "<?php echo __HOST__ . "/leads/getConvForLead/"; ?>",
          data: {'lead_id':e.target.value},
          success: function(respond) {
              $("#chat").html(respond);
          }
      });
  });
</script>
