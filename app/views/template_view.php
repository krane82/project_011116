<!DOCTYPE html>
<html>
<head>
  <title>Energy Smart</title>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta charset="UTF-8">
  <meta name="description" content="Energy Smart system" />

<!--  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>-->
<link rel="shortcut icon" type="image/x-icon" href="http://www.energysmart.com.au/wp-content/uploads/2015/05/favicon.png">
  <link href="<?php echo __HOST__; ?>/assets/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet" type="text/css"//>
  <!-- <link href="<?php echo __HOST__; ?>/assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet" type="text/css"//> -->
  <link href="<?php echo __HOST__; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php echo __HOST__; ?>/assets/plugins/datatables/css/jquery.datatables.min.css">
  <!-- Theme Styles -->
  <link href="<?php echo __HOST__; ?>/assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/css/themes/green.css" class="theme-color" rel="stylesheet" type="text/css"/>
  <link href="<?php echo __HOST__; ?>/assets/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php echo __HOST__; ?>/assets/plugins/bootstrap-datepicker/css/datepicker.css">
  <link rel="stylesheet" href="<?php echo __HOST__; ?>/css/bootstrap-switch.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet"/>
  <style>
    div.checker {
      display: none;
    }
  </style>
  <link rel="stylesheet" href="<?php echo __HOST__; ?>/css/style.css" type="text/css" />
  <script src="<?php echo __HOST__; ?>/assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
  <script src="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="<?php echo $body_class; ?>">
<!-- Javascripts -->
<script src="<?php echo __HOST__; ?>/assets/plugins/jquery/jquery-2.1.4.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
<div class="overlay"></div>
<form class="search-form" action="#" method="GET">
  <div class="input-group">
    <input type="text" name="search" class="form-control search-input" placeholder="Search...">
    <span class="input-group-btn">
                    <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button"><i class="fa fa-times"></i></button>
                </span>
  </div><!-- Input Group -->
</form><!-- Search Form -->
<main class="page-content content-wrap container">
  <div class="navbar">
    <div class="navbar-inner">
      <div class="sidebar-pusher">
        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
          <i class="fa fa-bars"></i>
        </a>
      </div>
      <div class="logo-box">
        <a href="admin" class="logo-text"><span>Energy Smart</span></a>
      </div><!-- Logo Box -->
<!--      <div class="search-button">-->
<!--        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>-->
<!--      </div>-->
      <div class="topmenu-outer">
        <div class="top-menu">
          <ul class="nav navbar-nav navbar-left">
            <li>
              <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
            </li>
<!--            <li>-->
<!--              <a href="#cd-nav" class="waves-effect waves-button waves-classic cd-nav-trigger"><i class="fa fa-diamond"></i></a>-->
<!--            </li>-->
            <li>
              <a href="javascript:void(0);" class="waves-effect waves-button waves-classic toggle-fullscreen"><i class="fa fa-expand"></i></a>
            </li>
            <li class="dropdown">
<!--              <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">-->
<!--                <i class="fa fa-cogs"></i>-->
<!--              </a>-->
              <ul class="dropdown-menu dropdown-md dropdown-list theme-settings" role="menu">
                <li class="li-group">
                  <ul class="list-unstyled">
                    <li class="no-link" role="presentation">
                      Fixed Header
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right fixed-header-check" checked>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="li-group">
                  <ul class="list-unstyled">
                    <li class="no-link" role="presentation">
                      Fixed Sidebar
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right fixed-sidebar-check">
                      </div>
                    </li>
                    <li class="no-link" role="presentation">
                      Horizontal bar
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right horizontal-bar-check">
                      </div>
                    </li>
                    <li class="no-link" role="presentation">
                      Toggle Sidebar
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right toggle-sidebar-check">
                      </div>
                    </li>
                    <li class="no-link" role="presentation">
                      Compact Menu
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right compact-menu-check">
                      </div>
                    </li>
                    <li class="no-link" role="presentation">
                      Hover Menu
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right hover-menu-check">
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="li-group">
                  <ul class="list-unstyled">
                    <li class="no-link" role="presentation">
                      Boxed Layout
                      <div class="ios-switch pull-right switch-md">
                        <input type="checkbox" class="js-switch pull-right boxed-layout-check" checked>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="li-group">
                  <ul class="list-unstyled">
                    <li class="no-link" role="presentation">
                      Choose Theme Color
                      <div class="color-switcher">
                        <a class="colorbox color-blue" href="?theme=blue" title="Blue Theme" data-css="blue"></a>
                        <a class="colorbox color-green" href="?theme=green" title="Green Theme" data-css="green"></a>
                        <a class="colorbox color-red" href="?theme=red" title="Red Theme" data-css="red"></a>
                        <a class="colorbox color-white" href="?theme=white" title="White Theme" data-css="white"></a>
                        <a class="colorbox color-purple" href="?theme=purple" title="purple Theme" data-css="purple"></a>
                        <a class="colorbox color-dark" href="?theme=dark" title="Dark Theme" data-css="dark"></a>
                      </div>
                    </li>
                  </ul>
                </li>
                <li class="no-link"><button class="btn btn-default reset-options">Reset Options</button></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
<!--            <li>-->
<!--              <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>-->
<!--            </li>-->
            <li>
              <a href="/admin/logout" class="log-out waves-effect waves-button waves-classic">
                <span><i class="fa fa-sign-out m-r-xs"></i>Log out</span>
              </a>
            </li>
          </ul><!-- Nav -->
        </div><!-- Top Menu -->
      </div>
    </div>
  </div><!-- Navbar -->
  <div class="page-sidebar sidebar">
    <div class="page-sidebar-inner slimscroll">
      <?php if(isset($_SESSION["user_name"])) { ?>
      <div class="sidebar-header">
        <div class="sidebar-profile">
          <a href="javascript:void(0);" id="profile-menu-link">
            <div class="sidebar-profile-details">
              <span>Hello,</span>
              <span><?php echo $_SESSION["user_name"]; ?><br><small></small></span>
            </div>
          </a>
        </div>
      </div>
      <?php }  else echo "<br>"; ?>
      <ul class="menu accordion-menu">
        <li><a href="/admin/dashboard" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-home"></span><p>Dashboard</p></a></li>
        <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-user"></span><p>Clients</p><span class="arrow"></span></a>
          <ul class="sub-menu">
          <li><a href="/clients/">Clients</a>
          <li><a href="/clients/managers/">Affiliate Managers</a>

          <li><a href="/clients/covermap">Map of Cover</a>

          <li><a href="<?php echo __HOST__; ?>/leads_limits/">Clients Caps</a></li>
          <li><a href="<?php echo __HOST__; ?>/leads_limits/matches">PostCodes Matches</a></li>
          <li><a href="<?php echo __HOST__; ?>/clients/sms">SMS service</a></li>
          </ul>
          <!--          <ul class="sub-menu">-->
<!--            <li><a href="ui-alerts">Alerts</a></li>-->
<!--            <li><a href="ui-buttons">Buttons</a></li>-->
<!--            <li><a href="ui-icons">Icons</a></li>-->
<!--          </ul>-->
        </li>

        <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-briefcase"></span><p>Campaigns</p><span class="arrow"></span></a>
          <ul class="sub-menu">
          <li><a href="/campaigns/">Campaigns Settings</a></li>
          <li><a href="/campaigns/planing">Affiliates Planning</a></li>
          </ul>
        <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-th"></span><p>Leads</p><span class="arrow"></span></a>
          <ul class="sub-menu">
            <li><a href="<?php echo __HOST__; ?>/leads/">Leads Delivery</a></li>
            <li><a href="<?php echo __HOST__; ?>/approvals/">Leads Approvals</a></li>
            <li><a href="<?php echo __HOST__; ?>/reject/">Leads Rejection</a></li>

            <li><a href="<?php echo __HOST__; ?>/rerouting/">Lead Rerouting</a></li>

            <li><a href="<?php echo __HOST__; ?>/leads/distribution/">Leads distribution</a></li>
          <li><a href="<?php echo __HOST__; ?>/penetration/">Penetration</a></li>

          </ul>
        </li>
        <li><a href="<?php echo __HOST__; ?>/admin_reports/" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-stats"></span><p>Reports</p></a>

        <li><a href="<?php echo __HOST__; ?>/invoice/" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-list-alt"></span><p>Invoices</p></a>

        <li><a href="<?php echo __HOST__; ?>/settings/" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-wrench"></span><p>Settings</p></a>
<!--          <ul class="sub-menu">-->
<!--            <li><a href="charts-sparkline">Sparkline</a></li>-->
<!--            <li><a href="charts-rickshaw">Rickshaw</a></li>-->
<!--            <li><a href="charts-morris">Morris</a></li>-->
<!--            <li><a href="charts-flotchart">Flotchart</a></li>-->
<!--            <li><a href="charts-chartjs">Chart.js</a></li>-->
<!--          </ul>-->
        </li>
      </ul>
    </div><!-- Page Sidebar Inner -->
  </div><!-- Page Sidebar -->
  <div class="page-inner">
    <div class="page-title">
<!--      <h3>--><?php //echo $page_title ?><!--</h3>-->
      <div class="page-breadcrumb">
<!--        <ol class="breadcrumb">-->
<!--          <li><a href="index">Home</a></li>-->
<!--          <li><a href="#">Layouts</a></li>-->
<!--          <li class="active">Boxed Page</li>-->
<!--        </ol>-->
      </div>
    </div>
    <div id="main-wrapper">
      <div class="row">

        <?php include 'app/views/'.$content_view; ?>

      </div><!-- Row -->
    </div><!-- Main Wrapper -->
    <div class="page-footer">
      <p class="no-s">&copy; 2012-2017 Energy Smart</p>
    </div>
  </div><!-- Page Inner -->
</main><!-- Page Content -->
<nav class="cd-nav-container" id="cd-nav">
  <header>
    <h3>Navigation</h3>
    <a href="#0" class="cd-close-nav">Close</a>
  </header>
  <ul class="cd-nav list-unstyled">
    <li class="cd-selected" data-menu="index">
      <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-home"></i>
                        </span>
        <p>Dashboard</p>
      </a>
    </li>
    <li data-menu="profile">
      <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-user"></i>
                        </span>
        <p>Profile</p>
      </a>
    </li>
    <li data-menu="inbox">
      <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-envelope"></i>
                        </span>
        <p>Mailbox</p>
      </a>
    </li>
    <li data-menu="#">
      <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-tasks"></i>
                        </span>
        <p>Tasks</p>
      </a>
    </li>
    <li data-menu="#">
      <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-cog"></i>
                        </span>
        <p>Settings</p>
      </a>
    </li>
    <li data-menu="calendar">
      <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
        <p>Calendar</p>
      </a>
    </li>
  </ul>
</nav>
<div class="cd-overlay"></div>


<script src="<?php echo __HOST__; ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/pace-master/pace.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/jquery-blockui/jquery.blockui.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/switchery/switchery.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/uniform/jquery.uniform.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/js/classie.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/js/main.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/waves/waves.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/3d-bold-navigation/js/main.js"></script>
<script src="<?php echo __HOST__; ?>/assets/js/modern.min.js"></script>
<script src="<?php echo __HOST__; ?>/js/bootstrap-switch.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

  <script>
    // $(document).ready(function(){
    //   $("select, input:radio, input:file").uniform();
    // });
  </script>
</body>
</html>
