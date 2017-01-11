<?php echo "<h3>How ya goin, ".$_COOKIE['user_name'] . "?</h3>"; ?>


<div class="dayle"></div><!-- .dayle -->

<script>

  function getAccepted(){
    document.location.href = '/admin_reports/getAccepted?';
  }
  function getDistributed() {
    document.location.href = '/admin_reports/getDistributed?';
  }
  function getRejected(){
    document.location.href = '/admin_reports/getRejected?';
  }

  $.ajax({
    type: "POST",
    url: '<?php echo __HOST__ . '/client/getAverageReports/' ?>',
    data: {}, // serializes the form's elements.
    success: function (data) {
      document.querySelector('.dayle').innerHTML = data; // show response from the php script.
    }
  });
</script>


