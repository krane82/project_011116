<link rel="stylesheet" href="<?= __HOST__ ?>/assets/plugins/morris/morris.css">
<script src="<?= __HOST__ ?>/assets/plugins/morris/morris.min.js"></script>
<script src="<?= __HOST__ ?>/assets/plugins/morris/raphael.min.js"></script>
<!-- Flot charts -->
<!--<script src="http://leadpoint.energysmart.com.au/template/js/flot/jquery.flot.js"></script>-->
<!--<script src="http://leadpoint.energysmart.com.au/template/js/flot/jquery.flot.resize.min.js"></script>-->
<h1 class="text-center"><?php echo $title; ?></h1>

<div id="area-chart"></div>
<hr>
<?php
$today = time();
$yesturday = date("d", $today);
$yesturday = $yesturday - 1;
$yesturday = "0".$yesturday;
?>
<div class="row">
    <form id = "reportmain" method="post" action="/admin_reports/getAverage/">
        <div class="form-group reportsdate">
            <label for="datepicker">Select Date Range</label>
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control" name="start" value="<?php print date('m/'.$yesturday.'/Y')?>" />
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control" name="end"  value="<?php print date('m/d/Y')?>" />
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary">
        </div>
    </form>
  <div class="daylenew"></div>
  <div class="clearfix"><br></div>
<!--  <h4>Today stats</h4>-->
<!--  <div class="dayle"></div>-->
</div>
<p><b>Percent of pending leads in this week</b></p>
<input type="text" disabled value="<?php print $data['pendingPercent']?>" style="width:4em">

<br>
<hr>
<h2 class="text-center">Client Lead Distribution Order</h2>
<br>
<div class="panel panel-white ">
<div class="table-responsive">
  <table class="table responsive">
    <tr>
      <td>Client </td>
      <td>Cost per lead </td>
      <td>Rejection (%)  </td>
      <td>Total revenue</td>
    </tr>
    <?php
    $c = 0;
    foreach ($order as $k => $v) {
      $c < 4 ? $class = 'green' : $class = 'red';
      echo "<tr class='$class'>";
      echo "<td>". $v["client"] ."</td>" .
      "<td>". $v["lead_cost"] ."</td>" .
      "<td>". number_format($v["percentage"] * 100, 0) . "%" ."</td>" .
      "<td>" . $v["revenue"] ."$". "</td>";
      echo "</tr>";
      $c++;
    }
    ?>
  </table>
</div>
</div>

<script>

  function getAccepted(){
    document.location.href = '/admin_reports/getAccepted';
  }
  function getDistributed() {
    document.location.href = '/admin_reports/getDistributed';
  }
  function getRejected(){
    document.location.href = '/admin_reports/getRejected';
  }

  Morris.Bar({
    element: 'area-chart',
    data: <?php echo json_encode($MonthlyStats);?>,
    xkey: 'm',
    ykeys: ['a','b','c'],
    labels: ['Spent ($)','Sold ($)','Profit ($)'],
    lineColors:['#3660aa','#d3503e']
  });

//  $.ajax({
//    type: "POST",
//    url: '<?php //echo __HOST__ . '/admin_reports/getAverage/' ?>//',
//    data: {}, // serializes the form's elements.
//    success: function (data) {
//      document.querySelector('.dayle').innerHTML = data; // show response from the php script.
//    }
//  });

  $.ajax({
    type: "POST",
    url: '<?php echo __HOST__ . '/admin_reports/getAverageNew/' ?>',
    success: function (data) {
      document.querySelector('.daylenew').innerHTML = data; // show response from the php script.
    }
  });

  //  $.ajax({
  //   type: "POST",
  //   url: '<?php echo __HOST__ . '/admin_reports/getAverageNewSec/' ?>',
  //   success: function (data) {
  //     document.querySelector('.daylenewsec').innerHTML = data; // show response from the php script.
  //   }
  // });
  $(document).ready(function () {
      var form=$('#reportmain');
      form.submit(function (e) {
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



      $('.input-daterange').datepicker({
          multidate: "true"
      });
  });

</script>