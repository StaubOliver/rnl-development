<!DOCTYPE html>
<html lang="en" ng-app='map_stats'

<?php
	echo 'ng-init = "selectedUniqueId=\''.$selectedUniqueId.'\'"';
?>

>

<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Stats</title>
	
	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
	<script src="/assets/js/map.js"></script>

	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<!-- Icons -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	<!-- CSS -->
	<!--
	<link rel="stylesheet" href="/assets/css/normalize.css">
	<link rel="stylesheet" href="/assets/css/skeleton.css">
	<link rel="stylesheet" href="/assets/css/skeleton-override.css">
	<link rel="stylesheet" href="/assets/css/style.css">
	<link rel="stylesheet" href="/assets/css/responsive.css">
	-->
	<link rel="stylesheet" href="/assets/css/map2.css">
	
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	

</head>

<body class='admin-body' ng-controller='admin_map_stats'>

	<nav class="navbar-default  navbar-fixed-top" >
		<div class="container-fluid">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Admin Stats</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse">
				<ul class="nav navbar-nav">
				</ul>
				<ul class="nav navbar-nav navbar-right">
		 			<li><a href="/map">Back to the map</a></li>
		 			<li><a href="/map/map_admin">Admin</a></li>
		 			<li><a href ="/auth/logout" class="navbar-link">Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class=container>

		<div class='container-fluid'>
			<div class='row'>

				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title'>

					<div class='row'>


						<div class='col-md-2 col-sm-2 col-xs-4'>
							<h5>Visitors - <?php echo $stats['uniqueVisits'] ?> </h5>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-4'>
							<h5>Actions - <?php echo $stats['nbActions'] ?> </h5>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-4'>
							<h5 class="hover-link" ng-click="changeUniqueId()">Refresh</h5>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12'>
							<select class='form-control' ng-model='selectedUniqueId' ng-change='changeUniqueId()'>
								<option value="0" selected>General Statistics</option>
								<optgroup label="Visitors with contribution">	
									<?php
										foreach($stats["ids_contributions"] as $visit){
											echo "<option value='".$visit['unique_id']."'>".$visit['unique_id']."</option>";
										}
									?>
								</optgroup>
								<optgroup label="All visitors">	
									<?php
										foreach($stats["visits"] as $visit){
											echo "<option value='".$visit['unique_id']."'>".$visit['unique_id']."</option>";
										}
									?>
								</optgroup>
							</select>
						</div>

					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>

						<!-- Visitors per visitor -->

						<div class='col-md-12  col-sm-12 col-xs-12'>
							<h5>Visitors</h5>
						</div>

						<!-- Action per visitor -->

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Action per visitor</h6>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Min
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visitor.min}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Max
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visitor.max}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Total
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visitor.total}}
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Std
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visitor.std}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Avg
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visitor.avg}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Med
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visitor.med}}
						</div>

						<!-- Visits per visitor -->

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Visits per visitor</h6>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Min
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_visits_per_visitor.min}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Max
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_visits_per_visitor.max}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Total
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_visits_per_visitor.total}}
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Std
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_visits_per_visitor.std}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Avg
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_visits_per_visitor.avg}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Med
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_visits_per_visitor.med}}
						</div>


						<!-- Visits per visitor -->

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Dwell per visitor</h6>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Min
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visitor.min.d}}d {{general.stat_dwell_per_visitor.min.h}}h {{general.stat_dwell_per_visitor.min.m}}m {{general.stat_dwell_per_visitor.min.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Max
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visitor.max.d}}d {{general.stat_dwell_per_visitor.max.h}}h {{general.stat_dwell_per_visitor.max.m}}m {{general.stat_dwell_per_visitor.max.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Total
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visitor.total.d}}d {{general.stat_dwell_per_visitor.total.h}}h {{general.stat_dwell_per_visitor.total.m}}m {{general.stat_dwell_per_visitor.total.s}}s
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Std
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visitor.std.d}}d {{general.stat_dwell_per_visitor.std.h}}h {{general.stat_dwell_per_visitor.std.m}}m {{general.stat_dwell_per_visitor.std.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Avg
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visitor.avg.d}}d {{general.stat_dwell_per_visitor.avg.h}}h {{general.stat_dwell_per_visitor.avg.m}}m {{general.stat_dwell_per_visitor.avg.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Med
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visitor.med.d}}d {{general.stat_dwell_per_visitor.med.h}}h {{general.stat_dwell_per_visitor.med.m}}m {{general.stat_dwell_per_visitor.med.s}}s
						</div>


					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>


						<!-- Visits -->

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h5>Visits</h5>
						</div>

						<!-- Action per Visits -->

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Action per visit</h6>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Min
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visit.min}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Max
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visit.max}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Total
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visit.total}}
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Std
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visit.std}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Avg
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visit.avg}}
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Med
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							{{general.stat_action_per_visit.med}}
						</div>

						<!-- Dwell per Visits -->

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Dwell per visit</h6>
						</div>

						<div class='col-md-2 col-sm-2 col-xs-2'>
							Min
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visit.min.d}}d {{general.stat_dwell_per_visit.min.h}}h {{general.stat_dwell_per_visit.min.m}}m {{general.stat_dwell_per_visit.min.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Max
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visit.max.d}}d {{general.stat_dwell_per_visit.max.h}}h {{general.stat_dwell_per_visit.max.m}}m {{general.stat_dwell_per_visit.max.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Total
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visit.total.d}}d {{general.stat_dwell_per_visit.total.h}}h {{general.stat_dwell_per_visit.total.m}}m {{general.stat_dwell_per_visit.total.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Std
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visit.std.d}}d {{general.stat_dwell_per_visit.std.h}}h {{general.stat_dwell_per_visit.std.m}}m {{general.stat_dwell_per_visit.std.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Avg
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visit.avg.d}}d {{general.stat_dwell_per_visit.avg.h}}h {{general.stat_dwell_per_visit.avg.m}}m {{general.stat_dwell_per_visit.avg.s}}s
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Med
						</div>
						<div class='col-md-2 col-sm-4 col-xs-4'>
							{{general.stat_dwell_per_visit.med.d}}d {{general.stat_dwell_per_visit.med.h}}h {{general.stat_dwell_per_visit.med.m}}m {{general.stat_dwell_per_visit.med.s}}s
						</div>




					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h5>Action per visitor - {{general.total_participants}} visitors</h5>
						</div>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>All actions</h6>
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Pan
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_pan_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_pan_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Click on Fossil
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.click_on_fossil_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_click_on_fossil_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Change age
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_geological_change_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_geological_change_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Click
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_click_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_click_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Fossil Selected
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.fossil_selected_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_fossil_selected_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Hover collector
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_collector_hover_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_collector_hover_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Zoom In
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_zoom_in_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_zoom_in_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Fossil Deselected
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.fossil_deselected_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_fossil_deselected_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Change collector
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_collector_change_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_collector_change_part}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Zoom Out
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_zoom_out_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_zoom_out_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Clear Selection
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.clear_fossil_selection_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_clear_fossil_selection_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Hover genus
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_genus_hover_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_genus_hover_part}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Feedback Hover
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.feedback_hover_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_feedback_hover_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Write comment
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.write_comment_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_write_comment_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Change genus
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_genus_change_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_genus_change_part}}
						</div>

						


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Feedback Click
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.feedback_click_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_feedback_click_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Submit comment
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.submit_feedback_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_submit_feedback_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Enlarge image
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.enlarge_image_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_enlarge_image_part}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Upvote
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.upvote_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_upvote_part}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Click reply
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.click_reply_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_click_reply_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Sharing
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.sharing_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_sharing_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Share contribution
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.share_contribution_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_share_contribution_part}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Reset Filter
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.reset_filter_part}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_reset_filter_part}}
						</div>


						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Per category</h6>
						</div>


						<div class='col-md-4 col-sm-6 col-xs-6'>
							Data Visualization
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_part_data_visualization}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_part_data_visualization}}
						</div>

						<div class='col-md-4 col-sm-6 col-xs-6'>
							Data Exploration
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_part_data_exploration}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_part_data_exploration}}
						</div>

						<div class='col-md-4 col-sm-6 col-xs-6'>
							Contribution
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_part_contribution}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_part_contribution}}
						</div>

						<div class='col-md-4 col-sm-6 col-xs-6'>
							Social Collaboration
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_part_social_collaboration}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_part_social_collaboration}}
						</div>

					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h5>AB groups</h5>
						</div>

						<div class='col-md-6 col-sm-12 col-xs-12'>
							<div class='row'>

								<div class='col-md-8 col-sm-6 col-xs-6'>	
									<h6>Group A</h6>
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_group_a}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_group_a}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Data Visualization
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_data_visualization_group_a}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_data_visualization_group_a}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Data Exploration
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_data_exploration_group_a}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_data_exploration_group_a}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Contribution
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_contribution_group_a}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_contribution_group_a}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Social Collaboration
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_social_collaboration_group_a}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_social_collaboration_group_a}}
								</div>


							</div>
						</div>

						<div class='col-md-6 col-sm-12 col-xs-12'>
							<div class='row'>

								<div class='col-md-8 col-sm-6 col-xs-6'>	
									<h6>Group B</h6>
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_group_b}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_group_b}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Data Visualization
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_data_visualization_group_b}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_data_visualization_group_b}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Data Exploration
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_data_exploration_group_b}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_data_exploration_group_b}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Contribution
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_contribution_group_b}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_contribution_group_b}}
								</div>

								<div class='col-md-8 col-sm-6 col-xs-6'>
									Social Collaboration
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.p_part_social_collaboration_group_b}}%
								</div>
								<div class='col-md-2 col-sm-3 col-xs-3'>
									{{general.nb_part_social_collaboration_group_b}}
								</div>


							</div>
						</div>



					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h5>Action distribution - {{general.total}} total actions</h5>
						</div>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>All actions</h6>
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Pan
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_pan}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_pan}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Click on Fossil
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.click_on_fossil}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_click_on_fossil}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Change age
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_geological_change}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_geological_change}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Click
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_click}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_click}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Fossil Selected
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.fossil_selected}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_fossil_selected}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Hover collector
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_collector_hover}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_collector_hover}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Zoom In
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_zoom_in}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_zoom_in}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Fossil Deselected
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.fossil_deselected}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_fossil_deselected}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Change collector
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_collector_change}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_collector_change}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Map Zoom Out
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.map_zoom_out}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_map_zoom_out}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Clear Selection
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.clear_fossil_selection}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_clear_fossil_selection}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Hover genus
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_genus_hover}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_genus_hover}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Feedback Hover
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.feedback_hover}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_feedback_hover}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Write comment
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.write_comment}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_write_comment}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Change genus
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.filter_genus_change}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_filter_genus_change}}
						</div>

						


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Feedback Click
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.feedback_click}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_feedback_click}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Submit comment
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.submit_feedback}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_submit_feedback}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Enlarge image
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.enlarge_image}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_enlarge_image}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Upvote
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.upvote}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_upvote}}
						</div>


						<div class='col-md-2 col-sm-6 col-xs-6'>
							Click reply
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.click_reply}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_click_reply}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Sharing
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.sharing}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_sharing}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Share contribution
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.share_contribution}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_share_contribution}}
						</div>

						<div class='col-md-2 col-sm-6 col-xs-6'>
							Reset Filter
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.reset_filter}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_reset_filter}}
						</div>


						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h6>Per category</h6>
						</div>


						<div class='col-md-4 col-sm-6 col-xs-6'>
							Data Visualization
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_data_visualization}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_data_visualization}}
						</div>

						<div class='col-md-4 col-sm-6 col-xs-6'>
							Data Exploration
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_data_exploration}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_data_exploration}}
						</div>

						<div class='col-md-4 col-sm-6 col-xs-6'>
							Contribution
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_contribution}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_contribution}}
						</div>

						<div class='col-md-4 col-sm-6 col-xs-6'>
							Social Collaboration
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.p_social_collaboration}}%
						</div>
						<div class='col-md-1 col-sm-3 col-xs-3'>
							{{general.nb_social_collaboration}}
						</div>
								

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<div id="chart_action_distribution" style="width: 100%; height: 500px; margin-top:15px"></div>
						</div>

						
						<!--<div class='col-md-6'>
							Average visit - {{general.avg_time}}
						</div>-->
	
					</div>
				</div>



				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>


						<div class='col-md-6 col-sm-6 col-xs-6'>
							<h5>Histogram</h5>
						</div>
						<div style="display:block; text-align:right" class='col-md-6 col-sm-6 col-xs-6' ng-hide="show_histogram_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_histogram_data = !show_histogram_data">Show Data</button>
						</div>
						<div style="display:block; text-align:right" class='col-md-6 col-sm-6 col-xs-6' ng-show="show_histogram_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_histogram_data = !show_histogram_data">Hide Data</button>
						</div>

						<div ng-show='show_histogram_data'>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Persons
							</div>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Persons
							</div>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Persons
							</div>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Persons
							</div>

							<div ng-repeat='d in general.data_hist_actions track by $index'>
								<div class='col-md-2 col-sm-4 col-xs-8'>
									{{$index*10}}-{{($index+1)*10}}
								</div>
								<div class='col-md-1  col-sm-2 col-xs-4'>
									{{d}}
								</div>
							</div>


							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Nb actions
							</div>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Nb actions
							</div>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Nb actions
							</div>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Range
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Nb actions
							</div>

							<div ng-repeat='d in general.data_hist_tot_action track by $index'>
								<div class='col-md-2 col-sm-4 col-xs-8'>
									{{$index*10}}-{{($index+1)*10}}
								</div>
								<div class='col-md-1  col-sm-2 col-xs-4'>
									{{d}}
								</div>
							</div>


						</div>

						<div class='col-md-12  col-sm-12 col-xs-12'>
							<div id="chart_div" style="width: 100%; height: 400px;"></div>
						</div>

					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>


						<div class='col-md-6 col-sm-6 col-xs-8'>
							<h5>Dwell = fct(nb action)</h5>
						</div>

						<div style="display:block; text-align:right" class='col-md-6 col-sm-6 col-xs-4' ng-hide="show_dwell_fct_actions_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_dwell_fct_actions_data = !show_dwell_fct_actions_data">Show Data</button>
						</div>
						<div style="display:block; text-align:right" class='col-md-6 col-sm-6 col-xs-4' ng-show="show_dwell_fct_actions_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_dwell_fct_actions_data = !show_dwell_fct_actions_data">Hide Data</button>
						</div>

						<div ng-show='show_dwell_fct_actions_data'>
							<div class='col-md-2 col-sm-4 col-xs-8'>
								Actions
							</div>
							<div class='col-md-1  col-sm-2 col-xs-4'>
								Dwell
							</div>
							<div class='col-md-2  col-sm-4 col-xs-8'>
								Actions
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Dwell
							</div>
							<div class='col-md-2  col-sm-4 col-xs-8'>
								Actions
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Dwell
							</div>
							<div class='col-md-2  col-sm-4 col-xs-8'>
								Actions
							</div>
							<div class='col-md-1 col-sm-2 col-xs-4'>
								Dwell
							</div>
							<div ng-repeat='d in general.nb_action_fct_dwell' ng-if='$index > 0'>
								<div class='col-md-2  col-sm-4 col-xs-8'>
									{{d[0]}}
								</div>
								<div class='col-md-1 col-sm-2 col-xs-4'>
									{{d[1]}}
								</div>
							</div>
						</div>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<div id="chart_action_dwell" style="width: 100%; height: 400px;"></div>
						</div>

					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>


						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h5>Latest Activity</h5>
						</div>
						<div ng-repeat="a in general.latest_activity">
							<div class="col-md-5 col-sm-5 col-xs-12">
								{{a.unique_id}}
							</div>
							<div class="col-md-3 col-sm-3 col-xs-4">
								{{a.time}}
							</div>
							<div class="col-md-4 col-sm-4 col-xs-8">
								{{a.action}} {{a.details}}
							</div>
						</div>
						

						
						

					</div>
				</div>

				<!--
				<div class='col-md-10 col-md-offset-1 admin-title' ng-show='details.length > 0'>
					<div class='row'>
						
						<div class='col-md-12'>
							<h5>{{details[0]['unique_id']}} -  {{details.length - 1}} actions - {{details[details.length-1]}}</h5>
						</div>

						<div ng-repeat='detail in details'>

							<div class='col-md-3'>{{detail.time}} </div> 
							<div class='col-md-9'>{{detail.action}} {{detail.details}}</div>

						</div>

					</div>
				</div>

				-->

				<div class='col-md-10 col-md-offset-1 col-sm-12 col-xs-12 admin-title' ng-show='details.length != 0'>
					<div class='row'>

						<div class='col-md-12 col-sm-12 col-xs-12'>
							<h5>{{details.unique_id}} - Group {{details.ab_group}} - {{details.nb_tot_action}} actions - {{details.nb_visits}} visits - {{details.tot_time.d}}d {{details.tot_time.h}}h {{details.tot_time.m}}m {{details.tot_time.s}}s</h5>
						</div>

						<div ng-repeat='visit in details.visits'>
							<div class='col-md-12 col-sm-12 col-xs-12'>

								<h6>{{visit.nb_action}} actions - {{visit.visit_time.d}}d {{visit.visit_time.h}}h {{visit.visit_time.m}}m {{visit.visit_time.s}}s</h6>
							</div>
							<div ng-repeat='action in visit.actions' ng-class="{'stat-submit-comment':action.action=='Submit feedback'}">
								<div class='col-md-3 col-sm-3 col-xs-12'>{{action.time}} </div> 
								<div class='col-md-9 col-sm-9 col-xs-12'>{{action.action}} {{action.details}}</div>
							</div>


						</div>


					</div>
				</div>



			</div>
		</div>


	</div>

	<div class="" style='height:50px'>
	</div>

<!--
	<div class="" style='height:50px'>
	</div>

	<div class='col-md-12  col-sm-12 col-xs-12'>
		<div id="chart_div" style="width: 1500px; height: 600px;"></div>
	</div>

	<div class='col-md-12 col-sm-12 col-xs-12'>
		<div id="chart_action_distribution" style="width: 1000px; height: 600px; margin-top:15px"></div>
	</div>

	<div class='col-md-12 col-sm-12 col-xs-12'>
		<div id="chart_action_dwell" style="width: 1500px; height: 600px; margin-top:15px"></div>
	</div>
-->


</body>
</html>









































