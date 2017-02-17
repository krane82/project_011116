<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2017
 * Time: 11:15
 */
?>
<head>
    <style>
        /*td {width:200px; border:solid 1px green}
    </style>
    <!--script src="../libs/highcharts/themes/grid-light.js"></script-->
</head>
<body>
<h1 class="text-center">PostCodes Penetration</h1>
<div class="no-footer">
    <form id="penetr">
        <div class="form-group">
            <label for="datepicker">Select Date Range</label>
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm st form-control" name="start" value="<?php print date('m/01/Y')?>"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm en form-control" name="end" value="<?php print date('m/d/Y')?>"/>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Build penetrations">
        </div>
    </form>
</div>
<div id='infodiv'>
<div class="row">
<div class="col-lg-3" id="NSW"></div>
<div class="col-lg-3" id="QLD"></div>
<div class="col-lg-3" id="SA"></div>
</div>
<div class="row">
<div class="col-lg-3" id="TAS"></div>
<div class="col-lg-3" id="VIC"></div>
<div class="col-lg-3" id="WA"></div>
</div>
</div>
<script>
    $(document).ready(function () {
		var div=document.getElementById('infodiv');
		var form=$('#penetr');
        form.submit(function (e) {
            e.preventDefault();
            var start = form.find('input[name=start]').val();
            var end = form.find('input[name=end]').val();
            if(!(start && end)) {
                alert('Please select Date range');
                return;
            }

			$.ajax ({
                    url: "<?php echo __HOST__ . '/penetration/getCode' ?>",
                    method: "POST",
                    data: {"st": start,
                            "en": end},
					success: function(respond){
					var Data=JSON.parse(respond);
					var NSWData=Data.NSW;
					var QLDData=Data.QLD;
					var SAData=Data.SA;
					var TASData=Data.TAS;
					var VICData=Data.VIC;
					var WAData=Data.WA;
					if(NSWData)fill("NSW",NSWData);
					if(QLDData)fill("QLD",QLDData);
					if(SAData)fill("SA",SAData);
					if(TASData)fill("TAS",TASData);
					if(VICData)fill("VIC",VICData)
					if(WAData)fill("WA",WAData)
				}
				});

});
var Data=JSON.parse('<?php print $data?>');
var NSWData=Data.NSW;
var QLDData=Data.QLD;
var SAData=Data.SA;
var TASData=Data.TAS;
var VICData=Data.VIC;
var WAData=Data.WA;
fill("NSW",NSWData);
fill("QLD",QLDData);
fill("SA",SAData);
fill("TAS",TASData);
fill("VIC",VICData);
fill("WA",WAData);
function fill(name,datArray)
{$(function () {
	var values=[];
(datArray[0])? values.push(['0',datArray[0].count]):values.push(['0',0]);
(datArray[1])? values.push(['1',datArray[1].count]):values.push(['1',0]);
(datArray[2])? values.push(['2',datArray[2].count]):values.push(['2',0]);
(datArray[3])? values.push(['3',datArray[3].count]):values.push(['3',0]);
(datArray[4])? values.push(['4',datArray[4].count]):values.push(['4',0]);
console.log(values);
            $('#'+name).highcharts({
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: name
                },
                tooltip: {
                    pointFormat: '{point.y}</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: false,
                        cursor: 'pointer',
                        depth: 30,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: '',
                    data: values
                }]
            });
});
}
$('.input-daterange').datepicker({
            multidate: "true"
        });
		   });
</script>
<script src="<?php echo $host; ?>/app/libs/highcharts/highcharts.js"></script>
    <script src="<?php echo $host; ?>/app/libs/highcharts/highcharts-3d.js"></script>
</body>