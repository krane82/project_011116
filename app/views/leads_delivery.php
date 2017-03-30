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
            </tr>
            </thead>
          </table>
        </div>
    </div>
    <!-- /.panel panel-white -->
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
</script>
