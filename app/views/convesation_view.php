<div class="col-lg-12" id="messages" style="height:500px; overflow:auto; background-color:rgba(173,228,188,0.12)">
<?php
foreach ($data['conversations'] as $item)
{       if($data['author']==$item['author'])
    {
        print '<div class="alert alert-info btn-rounded pull-right col-lg-7 col-sm-7 p"><span class="text-success pull-right">'.$item['time'].'</span><br><span class="f-s-14">'.$item['message'].'</span></div>';
    }
    else {
        print '<div class="alert alert-success btn-rounded pull-left col-lg-7 p"><span class="text-info">' . $item['full_name'] .'</span><span class="text-danger pull-right">'. $item['time'] . '</span><br><span class="f-s-14">' . $item['message'] . '</span></div>';
    }
}
?>
</div>
<button type="button" class="btn btn-sm btn-info btn-rounded m-b-md" data-toggle="collapse" data-target="#map">Add comment</button>
                    <div id="map" class="collapse">
                        <form id="addComment" method="post">
						<input type="hidden" name="leadId" value="<?php print $data['leadid']?>">
						<input type="hidden" name="userId" value="<?php print $data['author']?>">
						<textarea name="message" id="msg" class="input-rounded form-control" style="max-width:100%"></textarea>
                        <input class="btn btn-sm btn-success btn-rounded m-t-md" type="submit">
                    </div>
<script>
    var div = $("#messages");
    $("#addComment").submit(function(e){
	 e.preventDefault();
	    var data=$(this).serialize();
        $.ajax({
          type: "POST",
          url: "<?php echo __HOST__ . "/leads/addConv/"; ?>",
          data: data,
          success: function() {
			  var oldHtml = div.html();
              div.html(oldHtml+'<div class="alert alert-info btn-rounded pull-right col-lg-7 col-sm-7 p"><span class="text-success pull-right">(Just added)</span><br><span class="f-s-14">'+$("#msg").val()+'</span></div>');
              div.scrollTop(div.prop('scrollHeight'));
          }
      });
  });
</script>