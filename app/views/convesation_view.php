<div class="col-lg-12" id="messages" style="background-color:rgba(214,214,214,0.45)">
<?php
foreach ($data['conversations'] as $item)
{
		print '<p class="label-success align-middle">'.$item['full_name'].'<span class="pull-right badge">'.$item['time'].'</span></p>
        '.$item['message'];
}
?>
</div>
<button type="button" class="btn btn-sm btn-success" data-toggle="collapse" data-target="#map">Add comment</button>
                    <div id="map" class="collapse">
                        <form id="addComment" method="post">
						<input type="hidden" name="leadId" value="<?php print $data['leadid']?>">
						<input type="hidden" name="userId" value="<?php print $data['author']?>">
						<textarea name="message" id="msg" style="width:100%"></textarea>
                        <input type="submit">
                    </div>
<script>	
	$("#addComment").submit(function(e){
	 e.preventDefault();
	var data=$(this).serialize();
	console.log($('#msg').val());
	// console.log(data);
		$.ajax({
          type: "POST",
          url: "<?php echo __HOST__ . "/leads/addConv/"; ?>",
          data: data,
          success: function(respond) {
			  var oldHtml = $("#messages").html();
              $("#messages").html(oldHtml+'<p  class="label-success">You <span class="badge pull-right">(Just added)</span></p>'+$("#msg").val());
          }
      });
  });
</script>