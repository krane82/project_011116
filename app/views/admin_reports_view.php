<h2 class="text-center">Reports</h2>
<div class="container">
  <div class="row">

    <!-- .panel panel-white -->
    <hr>
    <h3 style="padding-left: 15px">Leads by clients</h3>
    <hr>
    <div class="panel panel-white ">
      <div class='col-md-5 col-xs-12 '>
        <form action="table-leads" id="LeadsByClients">
          <div class="form-group">
            <label for="datepicker">Select Date Range</label>
            <div class="input-daterange input-group" id="datepicker">
              <input type="text" class="input-sm form-control" name="start"/>
              <span class="input-group-addon">to</span>
              <input type="text" class="input-sm form-control" name="end"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="Sources">
              Select Client
            </label>
            <select name="client" class="form-control">
              <option value="0">All</option>

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
      <div class="col-md-2"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
      <!-- .average-info -->
        <div class="average-info">
        </div>
      <!-- /.average-info -->
      </div>
    </div>
    <hr>
    <h3 style="padding-left: 15px">Leads by Sources</h3>
    <hr>
    <div class="panel panel-white ">
      <div class='col-md-5 col-xs-12 '>
        <form action="table-leads" id="LeadsBySources">
          <div class="form-group">
            <label for="datepicker">Select Date Range</label>
            <div class="input-daterange input-group" id="datepicker">
              <input type="text" class="input-sm form-control" name="start"/>
              <span class="input-group-addon">to</span>
              <input type="text" class="input-sm form-control" name="end"/>
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
            <label class="control-label" for="State" class="form-control">
              Select State
            </label>
            <select name="State" id="state" class="form-control"> 
              <option value="">All</option>
              <option value="NSW">NSW</option>
              <option value="VIC">VIC</option>
              <option value="WA">WA</option>
              <option value="QLD">QLD</option>
              <option value="SA">SA</option>
              <option value="ACT">ACT</option>
              <option value="TAS">TAS</option>
            </select>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary">
          </div>
        </form>
      </div>
      <div class="col-md-2"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <!-- .average-info -->
        <div class=".average-source-info">
        </div>
        <!-- /.average-info -->
      </div>
    </div>

    <div class="col-md-10">
      <div class="row">
        <table class="table hidden" id="leads" >
          <thead>
          <tr>
            <th>ID</th>
            <th>Campaign Name</th>
            <th>State</th>
            <th>Date</th>
            <th>View</th>
          </tr>
          </thead>
        </table>
      </div>
    </div>

    <div class="col-md-12">
      <div class="row">
        <div class="average-source-info"></div>
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
  function getReceived() {
    var data = $('#LeadsBySources').serialize();
    document.location.href = '/admin_reports/getReceived?'+data;
  }
  function getAccepted(){
    var data = $('#LeadsByClients').serialize();
    document.location.href = '/admin_reports/getAccepted?'+data;
  }
  function getDistributed() {
    var data = $('#LeadsByClients').serialize();
    document.location.href = '/admin_reports/getDistributed?' + data;
  }
  function getRejected(){
    var data = $('#LeadsByClients').serialize();
    document.location.href = '/admin_reports/getRejected?'+data;
  }
  $(document).ready(function () {
    var form = $('#LeadsByClients');
    var formS = $('#LeadsBySources');
    var counter = 0;
    var table =  $('#leads');
    formS.submit(function (e) {
      e.preventDefault();
      var start = formS.find('input[name=start]').val();
      var end = formS.find('input[name=end]').val();
      var data = formS.serialize();
      if (!(start && end)) {
        alert('Please select Date range');
        return;
      }
      $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/admin_reports/getSourceAverage/' ?>',
        data: formS.serialize(), // serializes the form's elements.
        success: function (d) {
          console.log(d);
          document.querySelector('.average-source-info').innerHTML = d; // show response from the php script.
        }
      });
    });
    form.submit(function (e) {
      e.preventDefault();
      var start = form.find('input[name=start]').val();
      var end = form.find('input[name=end]').val();
      var post = form.serialize();
      if(!(start && end)) {
        alert('Please select Date range');
        return;
      }
       $.ajax({
         type: "POST",
         url: '<?php echo __HOST__ . '/admin_reports/getAverage/' ?>',
         data: post, // serializes the form's elements.
         success: function (data) {
           document.querySelector('.average-info').innerHTML = data; // show response from the php script.
         }
       });
    });
    $('.input-daterange').datepicker({
      multidate: "true"
    });
  });
</script>
