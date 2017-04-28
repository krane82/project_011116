<?php
//print '<pre>';
//print_r ($_POST);
?>
<h3>SMS SENDING SERVICE</h3>
<div class="col-lg-4 col-md-4">
</div>
<div class="col-lg-4 col-md-4">
    <form id="smsForm">
    <p>Select Client
<select name="client" class="form-control" id="client">
          <option selected disabled></option>
          <?php
          foreach ($data as $key => $value) {
              echo "<option value='" . $value['id'] . "'>" . $value['campaign_name'] . "</option>";
          }
          ?>
</select></p>
<p>Enter Text Here
    <textarea name="message" style="width:100%;height:200px; max-width: 100%; max-height:300px"></textarea>
    <p></p><input type="submit" value="Send!"></p>
    </form>
</p>
</div>
<script>
    $('#smsForm').submit(function(e){
        e.preventDefault();
        var data=$(this).serialize();
        console.log(data);
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/clients/smsSend/' ?>',
            data: data,
            success: function(data) {
            console.log(data)}
        });
    })
</script>