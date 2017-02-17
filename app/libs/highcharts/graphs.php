<?php


require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/FirstDecisionRealty/lib/FirstDecisionRealty.lib.php';
$langs->load("products");
$langs->load("other");


llxHeader('', $title, $helpurl);

$head=product_prepare_head();
dol_fiche_head($head, 'graphs');


//====================================close cases=====================================
    $sql = "select count(*) as num_close from `llx_listing` 
              where  `status` = 'close';
              ";
    $res = $db->query($sql);
    $items = array();
    while ($row = $db->fetch_array($res))
    {
        $items[] = $row;
    }
    $num_close = $items[0]['num_close'];
//---------------------------------------------------------------------------
$sql = "select sum(ratified_price) as sum_close from `llx_listing` 
              where  `status` = 'close';
              ";
$res = $db->query($sql);
$items = array();
while ($row = $db->fetch_array($res))
{
    $items[] = $row;
}
$sum_close = $items[0]['sum_close'];

//====================================open cases=====================================
    $sql = "select count(*) as num_open from `llx_listing` 
              where  `status` = 'open';
              ";
    $res = $db->query($sql);
    $items = array();
    while ($row = $db->fetch_array($res))
    {
        $items[] = $row;
    }

    $num_open = $items[0]['num_open'];
//---------------------------------------------------------------------------
$sql = "select sum(ratified_price) as sum_open from `llx_listing` 
              where  `status` = 'open';
              ";
$res = $db->query($sql);
$items = array();
while ($row = $db->fetch_array($res))
{
    $items[] = $row;
}
$sum_open = $items[0]['sum_open'];
//====================================general cases=====================================
    $sql = "select count(*) as num_general from `llx_listing` 
             where `status` in ('close', 'open');
              ";
    $res = $db->query($sql);
    $items = array();
    while ($row = $db->fetch_array($res))
    {
        $items[] = $row;
    }

    $num_general = $items[0]['num_general'];
  //  echo $num_general;
//===================================%=============================
    $num_close_p = ($num_close*100)/$num_general;
    $num_open_p = ($num_open*100)/$num_general;
   // echo $num_close_p;

 ?>
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <script src="js/bootstrap.min.js"></script>
    <?php print '<script src="'.DOL_MAIN_URL_ROOT.'/OpenCases/ajax/pendQ.js"></script>'?>
</head>
    <body style="background-color: #eaf7f7">

    <script src="scripts/highcharts.js"></script>
    <script src="scripts/highcharts-3d.js"></script>
    <script src="scripts/themes/grid-light.js"></script>

    <div style="background-color: white; height: 600px; width: 95%;">
        <div id="container1" class="tabBar" style="height: 40%; width: 47%; display: block"></div>
        <div id="container2" class="tabBar" style="height: 40%; width: 47%; display: block"></div>

    </div>

    <script>
        $(function () {
            $('#container2').highcharts({
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: ''
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
                    data: [
                        ['<?php echo $sum_close; ?>', <?php echo $num_close_p; ?>],
                        ['<?php echo $sum_open; ?>', <?php echo $num_open_p; ?>],
                       // ['$23 256,00', 17],
                       // ['$1 500,00', 1],
                    ]
                }]
            });
        });
     $(function () {
        $('#container1').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Leads converted'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: ['Converted', 'Appointments Set', 'Contacts Made', 'Leads In'],
                title: {
                    text: null
                }
            },

            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },

            credits: {
                enabled: false
            },
            series: [{
                name: 'Closed cases',
                data: [<?php echo $num_close ?>]
            },
                {
                name: 'Open cases',
                    data: [<?php echo $num_open ?>]
                },
            {
               name: 'General cases',
                data: [<?php echo $num_general ?>]
               },
                {
                   // name: 'Leads In',
                   // data: [0]
                },
            ]
        });
    });
</script>

</body>
<?php
llxFooter();
?>