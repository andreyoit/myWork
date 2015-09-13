
<?php 
include 'https://mywork-andreaem-dev.c9.io/sys/config.php'; 
define('QUADODO_IN_SYSTEM', true);
$w_enabled = 'Y'; 
$active = "dash" ;
$page_title= "Dashboard" ;
?>
<link href="<?php echo $app_url; ?>assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $app_url; ?>assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PORTLET CONFIGURATION MODAL FORM-->
			    <?php include 'tmpl/modal.php'; ?>
			<!-- /.modal -->
			<!-- END PORTLET CONFIGURATION MODAL FORM-->
			    <?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/customizer.php'; } ?>
			<!-- BEGIN PAGE HEADER-->
			    <?php include 'tmpl/page_header.php'; ?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				    <?php if ($w_enabled == 'N') { include $app_url . 'sys/box/box_1.php'; } ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<?php if ($w_enabled == 'N') { include $app_url . 'sys/box/box_2.php'; } ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<?php if ($w_enabled == 'N') { include $app_url . 'sys/box/box_3.php'; } ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<?php if ($w_enabled == 'N') { include $app_url . 'sys/box/box_4.php'; } ?>
				</div>
			</div>
			<!-- END DASHBOARD STATS -->
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'N') { include $app_url . 'sys/widget/chart_1.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'N') { include $app_url . 'sys/widget/chart_2.php'; } ?>
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/activity.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/tasks.php'; } ?>
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/stats_1.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/stats_2.php'; } ?>	
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/stats_map.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/feed.php'; } ?>
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/calendar.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled == 'Y') { include $app_url . 'sys/widget/chat.php'; } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTENT -->
    <?php include $app_url . 'tmpl/quick_sidebar.php'; ?>
</div>
<!-- END CONTAINER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo $app_url; ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
<script src="<?php echo $app_url; ?>assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $app_url; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="<?php echo $app_url; ?>assets/admin/pages/scripts/index.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   QuickSidebar.init(); // init quick sidebar
Demo.init(); // init demo features
   Index.init();   
   Index.initDashboardDaterange();
   Index.initJQVMAP(); // init index page's custom scripts
   Index.initCalendar(); // init index page's custom scripts
   Index.initCharts(); // init index page's custom scripts
   Index.initChat();
   Index.initMiniCharts();
   Tasks.initDashboardWidget();
});
</script>