<div class=""row">
<div class="col-md-4">
    <form id="invoices">
      <h4 class="text-center">Generate Invoice for previous 2 weeks to</h4>
        <div class="form-group">
Select Date Range
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm st form-control" name="start" required/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm en form-control" name="end" required/>
            </div>
Select Client
          <span id="infospan" class="label label-primary" style="float:right"></span>
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
<div class="col-md-7">
    <div id="listOfInvoices">
    </div>
</div>
</div>
<script>
    var infospan=document.getElementById('infospan');
    var listOfInvoices=document.getElementById('listOfInvoices');
    var client=document.getElementById('client');

    function inform(){
        //   $('#listOfInvoices').submit(function(e){
        // var data={'client':this.value};
        $.ajax(
            {
                type:"POST",
                url: "<?php echo __HOST__ . '/invoice/getListOfCurrent' ?>",
                data:{'client':client.value},
                success:function(data) {
                    listOfInvoices.innerHTML=data;
                }
            }
        )
    };

    client.onchange=function(){
        infospan.innerHTML='';
        inform();
    };

    $('#invoices').submit(function(e){
    e.preventDefault();
    if(!client.value) return;
    infospan.innerHTML='Wait please. . . generating invoice';
    var data=$(this).serialize();
    $.ajax(
        {
            type:"POST",
            url: "<?php echo __HOST__ . '/invoice/generate' ?>",
            data:data,
            success:function(data){
                console.log(data)
            infospan.innerHTML="Invoice created!";
            inform();
            }
        }
    )
});

    listOfInvoices.addEventListener('click',delbut);
    function delbut(event) {
        if (event.target.type == 'button') {
            var conf = confirm('Do you really want to delete this file?');
            if (conf) {
                var data = {'client': client.value, 'file': event.target.value};


                $.ajax(
                    {
                        type: "POST",
                        url: "<?php echo __HOST__ . '/invoice/deleteFile' ?>",
                        data: data,
                        success: function (data) {
                            console.log(data);
                            event.target.parentNode.parentNode.removeChild(event.target.parentNode);
                        }
                    }
                )
            }
        }
    }
    $(document).ready(function () {
        $('.input-daterange').datepicker({
           // multidate: "true", format: 'dd-mm-yyyy' }
            multidate: "true"}
        );
    });
</script>