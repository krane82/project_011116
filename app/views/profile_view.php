\<div id="UpdateClientForm" action="UpdateProfile" >
<?php

    echo $profile;

?>

  <button type="submit" id="Update" class="btn btn-primary">Edit profile</button>
</div>

<script>
 var btn = $('#Update');
      btn.click(function (ev) {
        $.ajax({
            method: "POST",
            url: '<?php echo __HOST__ . '/profile/'; ?>UpdateProfile',
            success: function (data) {
              document.querySelector('#UpdateClientForm').innerHTML = data;
            }
        });
        ev.preventDefault();
    });
</script>