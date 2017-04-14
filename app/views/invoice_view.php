<?php
print '<pre>';
?>
<div class="col-md-4">
    <form id="invoices">
      <h4 class="text-center">Generate Invoice for previous 2 weeks to</h4>
      <label class="control-label" for="date_range1">
Select Client
</label>
      <div class="form-group">
          <span id="infospan" class="label label-primary"></span>
          <select name="client" class="form-control" id="client">
          <option selected disabled></option>
          <?php
          foreach ($data as $key => $value) {
              echo "<option value='" . $value['id'] . "'>" . $value['campaign_name'] . "</option>";
          }
          ?>
</select>
</div>
<div class="controls">
    <input type="submit" class="btn btn-success" value="Generate Invoice">
</div>
<hr>
</form>
</div>
<script>
    var infospan=document.getElementById('infospan');
    var client=document.getElementById('client');
    client.onchange=function(){infospan.innerHTML=''};
    $('#invoices').submit(function(e){
    e.preventDefault();
    infospan.innerHTML='Wait please. . . generating invoice';
    var data=$(this).serialize();
    $.ajax(
        {
            type:"POST",
            url: "<?php echo __HOST__ . '/invoice/generate' ?>",
            data:data,
            success:function(){
            infospan.innerHTML="Invoice created!"}
        }
    )
});
</script>