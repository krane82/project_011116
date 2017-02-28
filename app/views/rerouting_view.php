<h4>Lead rerouting</h4>

<form id="getallleads">
  	<div class="form-group">
        <label for="datepicker">Select Date Range</label>
        <div class="input-daterange input-group" id="datepicker">
              <input type="text" class="input-sm st form-control exa" name="start"/>
              <span class="input-group-addon">to</span>
              <input type="text" class="input-sm en form-control" name="end"/>
        </div>
  	</div>
	
	<div>
		<label class="control-label" for="date_range1">
		          Select Client
		</label>
		<div class="form-group">
		  <select name="client" class="form-control" id="client">
		    <option disabled selected >Select</option>
		    
		    <?php
		    foreach ($clients as $key => $value) {
		      echo "<option value='" . $value['id'] . "'>" . $value['campaign_name'] . "</option>";
		    }
		    ?>
		  </select>
		</div>
	</div>
  <div>
    <div class="form-group">
      <label class="control-label"> Amount of leads </label>
      <input type="number" class="form-control" id="numberlead" name="number" required>
    </div>
  </div>
	<div class="form-group">
        <input type="submit" class="btn btn-primary" value="View leads">
	</div>
</form>

<div id="resultlead"></div>

<script type="text/javascript">
$(document).ready(function () {
  var tes = '';
  // $('#numberlead').keyup(function(){
   
  // });
   
  
var i = '';
var form = $('#getallleads');
    // var counter = 0;
    var table =  $('#leads');
form.submit(function (e) {
     var tes = $('#numberlead').val();
   
   if(+tes === 0){
        $('#resultlead').html('Specify the number of');
        e.preventDefault();
      }else if(+tes > 10){
        $('#resultlead').html('The maximum number of leads should not exceed 10');
        e.preventDefault();
      }else{
        e.preventDefault();
        var start = form.find('input[name=start]').val();
        var end = form.find('input[name=end]').val();
        var id_cli = $("#client").val();
        // var data = form.serialize();
        if(!(start && end)) {
          alert('Please select Date range');
          return;
        }
      $.ajax({
   		type: "POST",
          url: '<?php echo __HOST__ . '/rerouting/leadall/' ?>',
          data: { start: start, end: end, id_cli:id_cli, tes:tes },
          success: function (data) {
          $('#resultlead').html(data);
          }

      });
        // console.log(data);
  }
});



	$('.input-daterange').datepicker({
      multidate: "true"
    });
});
</script>
