
<?php 
include 'https://mywork-andreaem-dev.c9.io/sys/config.php'; 
define('QUADODO_IN_SYSTEM', true);
$W_enabled == 'N'; 
$active= "dash" ;
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
			    <?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/customizer.php'; } ?>
			<!-- BEGIN PAGE HEADER-->
			    <?php include 'tmpl/page_header.php'; ?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN DASHBOARD STATS -->
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				    <?php if ($w_enabled = 'Y') { include $app_url . 'sys/box/box_1.php'; } ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/box/box_2.php'; } ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/box/box_3.php'; } ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/box/box_4.php'; } ?>
				</div>
			</div>
			<!-- END DASHBOARD STATS -->
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/chart_1.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/chart_2.php'; } ?>
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/activity.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/tasks.php'; } ?>
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/stats_1.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/stats_2.php'; } ?>	
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/stats_map.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/feed.php'; } ?>
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/calendar.php'; } ?>
				</div>
				<div class="col-md-6 col-sm-6">
					<?php if ($w_enabled = 'Y') { include $app_url . 'sys/widget/chat.php'; } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTENT -->
    <?php include $app_url . 'tmpl/quick_sidebar.php'; ?>
</div>
<!-- END CONTAINER -->