<!DOCTYPE html>
<html lang="en" ng-app='map_stats'>

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
				<a class="navbar-brand" href="#">Admin Stats</a>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
				</ul>
				<ul class="nav navbar-nav navbar-right">
		 			<li><a href="/map">Back to the map</a></li>
		 			<li><a href ="/auth/logout" class="navbar-link">Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class=container>

		<div class='container-fluid'>
			<div class='row'>

				<div class='col-md-10 col-md-offset-1 admin-title'>

					<div class='row'>


						<div class='col-md-2'>
							<h5>Visitors - <?php echo $stats['uniqueVisits'] ?> </h5>
						</div>

						<div class='col-md-2'>
							<h5>Actions - <?php echo $stats['nbActions'] ?> </h5>
						</div>

						<div class='col-md-2'>
							<h5 class="hover-link" ng-click="changeUniqueId()">Refresh</h5>
						</div>

						<div class='col-md-6'>
							<select class='form-control' ng-model='selectedUniqueId' ng-change='changeUniqueId()'>
								<option value="0" selected>General</option>
								<optgroup label="Choose an ID">	
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


				<div class='col-md-10 col-md-offset-1 admin-title' ng-show="selectedUniqueId == 0">
					<div class='row'>

						<!-- Visitors per visitor -->

						<div class='col-md-12'>
							<h5>Visitors</h5>
						</div>

						<!-- Action per visitor -->

						<div class='col-md-12'>
							<h6>Action per visitor</h6>
						</div>

						<div class='col-md-2'>
							Min
						</div>
						<div class='col-md-2'>
							{{general.stat_action_per_visitor.min}}
						</div>
						<div class='col-md-2'>
							Max
						</div>
						<div class='col-md-2'>
							{{general.stat_action_per_visitor.max}}
						</div>
						<div class='col-md-2'>
							Total
						</div>
						<div class='col-md-2'>
							{{general.stat_action_per_visitor.total}}
						</div>

						<div class='col-md-2'>
							Standard Deviation
						</div>
						<div class='col-md-2'>
							{{general.stat_action_per_visitor.std}}
						</div>
						<div class='col-md-2'>
							Avg
						</div>
						<div class='col-md-2'>
							{{general.stat_action_per_visitor.avg}}
						</div>
						<div class='col-md-2'>
							Med
						</div>
						<div class='col-md-2'>
							{{general.stat_action_per_visitor.med}}
						</div>

						<!-- Visits per visitor -->

						<div class='col-md-12'>
							<h6>Visits per visitor</h6>
						</div>

						<div class='col-md-2'>
							Min
						</div>
						<div class='col-md-2'>
							{{general.stat_visits_per_visitor.min}}
						</div>
						<div class='col-md-2'>
							Max
						</div>
						<div class='col-md-2'>
							{{general.stat_visits_per_visitor.max}}
						</div>
						<div class='col-md-2'>
							Total
						</div>
						<div class='col-md-2'>
							{{general.stat_visits_per_visitor.total}}
						</div>

						<div class='col-md-2'>
							Standard Deviation
						</div>
						<div class='col-md-2'>
							{{general.stat_visits_per_visitor.std}}
						</div>
						<div class='col-md-2'>
							Avg
						</div>
						<div class='col-md-2'>
							{{general.stat_visits_per_visitor.avg}}
						</div>
						<div class='col-md-2'>
							Med
						</div>
						<div class='col-md-2'>
							{{general.stat_visits_per_visitor.med}}
						</div>


						<!-- Visits per visitor -->

						<div class='col-md-12'>
							<h6>Dwell per visitor</h6>
						</div>

						<div class='col-md-2'>
							Min
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visitor.min.d}}d {{general.stat_dwell_per_visitor.min.h}}h {{general.stat_dwell_per_visitor.min.m}}m {{general.stat_dwell_per_visitor.min.s}}s
						</div>
						<div class='col-md-2'>
							Max
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visitor.max.d}}d {{general.stat_dwell_per_visitor.max.h}}h {{general.stat_dwell_per_visitor.max.m}}m {{general.stat_dwell_per_visitor.max.s}}s
						</div>
						<div class='col-md-2'>
							Total
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visitor.total.d}}d {{general.stat_dwell_per_visitor.total.h}}h {{general.stat_dwell_per_visitor.total.m}}m {{general.stat_dwell_per_visitor.total.s}}s
						</div>

						<div class='col-md-2'>
							Standard Deviation
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visitor.std.d}}d {{general.stat_dwell_per_visitor.std.h}}h {{general.stat_dwell_per_visitor.std.m}}m {{general.stat_dwell_per_visitor.std.s}}s
						</div>
						<div class='col-md-2'>
							Avg
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visitor.avg.d}}d {{general.stat_dwell_per_visitor.avg.h}}h {{general.stat_dwell_per_visitor.avg.m}}m {{general.stat_dwell_per_visitor.avg.s}}s
						</div>
						<div class='col-md-2'>
							Med
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visitor.med.d}}d {{general.stat_dwell_per_visitor.med.h}}h {{general.stat_dwell_per_visitor.med.m}}m {{general.stat_dwell_per_visitor.med.s}}s
						</div>







						<!-- Visits -->

						<div class='col-md-4'>
							<h5>Visits</h5>
						</div>

						<!-- Action per Visits -->

						<div class='col-md-12'>
							<h6>Action per visit</h6>
						</div>

						<div class='col-md-2'>
							Min
						</div>
						<div class='col-md-2'>
							{{general.stat_action_visit.min}}
						</div>
						<div class='col-md-2'>
							Max
						</div>
						<div class='col-md-2'>
							{{general.stat_action_visit.max}}
						</div>

						<div class='col-md-2'>
							Standard Deviation
						</div>
						<div class='col-md-2'>
							{{general.stat_action_visit.std}}
						</div>
						<div class='col-md-2'>
							Avg
						</div>
						<div class='col-md-2'>
							{{general.stat_action_visit.avg}}
						</div>
						<div class='col-md-2'>
							Med
						</div>
						<div class='col-md-2'>
							{{general.stat_action_visit.med}}
						</div>

						<!-- Dwell per Visits -->

						<div class='col-md-4'>
							<h6>Dwell per visit</h6>
						</div>

						<div class='col-md-2'>
							Min
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visit.min.d}}d {{general.stat_dwell_per_visit.min.h}}h {{general.stat_dwell_per_visit.min.m}}m {{general.stat_dwell_per_visit.min.s}}s
						</div>
						<div class='col-md-2'>
							Max
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visit.max.d}}d {{general.stat_dwell_per_visit.max.h}}h {{general.stat_dwell_per_visit.max.m}}m {{general.stat_dwell_per_visit.max.s}}s
						</div>
						<div class='col-md-2'>
							Standard Deviation
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visit.std.d}}d {{general.stat_dwell_per_visit.std.h}}h {{general.stat_dwell_per_visit.std.m}}m {{general.stat_dwell_per_visit.std.s}}s
						</div>
						<div class='col-md-2'>
							Avg
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visit.avg.d}}d {{general.stat_dwell_per_visit.avg.h}}h {{general.stat_dwell_per_visit.avg.m}}m {{general.stat_dwell_per_visit.avg.s}}s
						</div>
						<div class='col-md-2'>
							Med
						</div>
						<div class='col-md-2'>
							{{general.stat_dwell_per_visit.med.d}}d {{general.stat_dwell_per_visit.med.h}}h {{general.stat_dwell_per_visit.med.m}}m {{general.stat_dwell_per_visit.med.s}}s
						</div>









						<div class='col-md-12'>
							<h5>Action distribution</h5>
						</div>

						<div class='col-md-2'>
							Map Pan
						</div>
						<div class='col-md-1'>
							{{general.map_pan}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_map_pan}}
						</div>

						<div class='col-md-2'>
							Click on Fossil
						</div>
						<div class='col-md-1'>
							{{general.click_on_fossil}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_click_on_fossil}}
						</div>

						<div class='col-md-2'>
							Change age
						</div>
						<div class='col-md-1'>
							{{general.filter_geological_change}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_filter_geological_change}}
						</div>

						<div class='col-md-2'>
							Map Click
						</div>
						<div class='col-md-1'>
							{{general.map_click}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_map_click}}
						</div>

						<div class='col-md-2'>
							Fossil Selected
						</div>
						<div class='col-md-1'>
							{{general.fossil_selected}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_fossil_selected}}
						</div>

						<div class='col-md-2'>
							Hover collector
						</div>
						<div class='col-md-1'>
							{{general.filter_collector_hover}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_filter_collector_hover}}
						</div>

						<div class='col-md-2'>
							Map Zoom In
						</div>
						<div class='col-md-1'>
							{{general.map_zoom_in}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_map_zoom_in}}
						</div>

						<div class='col-md-2'>
							Fossil Deselected
						</div>
						<div class='col-md-1'>
							{{general.fossil_deselected}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_fossil_deselected}}
						</div>

						<div class='col-md-2'>
							Change collector
						</div>
						<div class='col-md-1'>
							{{general.filter_collector_change}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_filter_collector_change}}
						</div>


						<div class='col-md-2'>
							Map Zoom Out
						</div>
						<div class='col-md-1'>
							{{general.map_zoom_out}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_map_zoom_out}}
						</div>

						<div class='col-md-2'>
							Clear Selection
						</div>
						<div class='col-md-1'>
							{{general.clear_fossil_selection}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_clear_fossil_selection}}
						</div>

						<div class='col-md-2'>
							Hover genus
						</div>
						<div class='col-md-1'>
							{{general.filter_genus_hover}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_filter_genus_hover}}
						</div>


						<div class='col-md-2'>
							Feedback Hover
						</div>
						<div class='col-md-1'>
							{{general.feedback_hover}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_feedback_hover}}
						</div>

						<div class='col-md-2'>
							Write comment
						</div>
						<div class='col-md-1'>
							{{general.write_comment}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_write_comment}}
						</div>

						<div class='col-md-2'>
							Change genus
						</div>
						<div class='col-md-1'>
							{{general.filter_genus_change}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_filter_genus_change}}
						</div>

						


						<div class='col-md-2'>
							Feedback Click
						</div>
						<div class='col-md-1'>
							{{general.feedback_click}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_feedback_click}}
						</div>

						<div class='col-md-2'>
							Submit comment
						</div>
						<div class='col-md-1'>
							{{general.submit_feedback}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_submit_feedback}}
						</div>

						<div class='col-md-2'>
							Enlarge image
						</div>
						<div class='col-md-1'>
							{{general.enlarge_image}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_enlarge_image}}
						</div>


						<div class='col-md-2'>
							Upvote
						</div>
						<div class='col-md-1'>
							{{general.upvote}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_upvote}}
						</div>


						<div class='col-md-2'>
							Click reply
						</div>
						<div class='col-md-1'>
							{{general.click_reply}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_click_reply}}
						</div>

						<div class='col-md-2'>
							Sharing
						</div>
						<div class='col-md-1'>
							{{general.sharing}}%
						</div>
						<div class='col-md-1'>
							{{general.nb_sharing}}
						</div>

								

						<div class='col-md-12'>
							<div id="chart_action_distribution" style="width: 100%; height: 400px;"></div>
						</div>

						
						<!--<div class='col-md-6'>
							Average visit - {{general.avg_time}}
						</div>-->

						



						<div class='col-md-6'>
							<h5>Histogram</h5>
						</div>
						<div style="display:block; text-align:right" class='col-md-6' ng-hide="show_histogram_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_histogram_data = !show_histogram_data">Show Data</button>
						</div>
						<div style="display:block; text-align:right" class='col-md-6' ng-show="show_histogram_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_histogram_data = !show_histogram_data">Hide Data</button>
						</div>

						<div ng-show='show_histogram_data'>
							<div class='col-md-2'>
								Range
							</div>
							<div class='col-md-1'>
								Persons
							</div>
							<div class='col-md-2'>
								Range
							</div>
							<div class='col-md-1'>
								Persons
							</div>
							<div class='col-md-2'>
								Range
							</div>
							<div class='col-md-1'>
								Persons
							</div>
							<div class='col-md-2'>
								Range
							</div>
							<div class='col-md-1'>
								Persons
							</div>

							<div ng-repeat='d in general.data_hist_actions track by $index'>
								<div class='col-md-2'>
									{{$index*10}}-{{($index+1)*10}}
								</div>
								<div class='col-md-1'>
									{{d}}
								</div>
							</div>
						</div>



						<div class='col-md-12'>
							<div id="chart_div" style="width: 100%; height: 400px;"></div>
						</div>



						<div class='col-md-6'>
							<h5>Dwell = fct(nb action)</h5>
						</div>

						<div style="display:block; text-align:right" class='col-md-6' ng-hide="show_dwell_fct_actions_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_dwell_fct_actions_data = !show_dwell_fct_actions_data">Show Data</button>
						</div>
						<div style="display:block; text-align:right" class='col-md-6' ng-show="show_dwell_fct_actions_data">
							<button style='margin-top:8px; margin-bottom:8px' type="button" class="btn btn-xs btn-custom-default" ng-click="show_dwell_fct_actions_data = !show_dwell_fct_actions_data">Hide Data</button>
						</div>

						<div ng-show='show_dwell_fct_actions_data'>
							<div class='col-md-2'>
								Actions
							</div>
							<div class='col-md-1'>
								Dwell
							</div>
							<div class='col-md-2'>
								Actions
							</div>
							<div class='col-md-1'>
								Dwell
							</div>
							<div class='col-md-2'>
								Actions
							</div>
							<div class='col-md-1'>
								Dwell
							</div>
							<div class='col-md-2'>
								Actions
							</div>
							<div class='col-md-1'>
								Dwell
							</div>
							<div ng-repeat='d in general.nb_action_fct_dwell' ng-if='$index > 0'>
								<div class='col-md-2'>
									{{d[0]}}
								</div>
								<div class='col-md-1'>
									{{d[1]}}
								</div>
							</div>
						</div>

						<div class='col-md-12'>
							<div id="chart_action_dwell" style="width: 100%; height: 400px;"></div>
						</div>


						<div class='col-md-12'>
							<h5>Latest Activity</h5>
						</div>
						<div ng-repeat="a in general.latest_activity">
							<div class="col-md-5">
								{{a.unique_id}}
							</div>
							<div class="col-md-3">
								{{a.time}}
							</div>
							<div class="col-md-4">
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

				<div class='col-md-10 col-md-offset-1 admin-title' ng-show='details.length != 0'>
					<div class='row'>

						<div class='col-md-12'>
							{{details.length}}<h5>{{details.unique_id}} - {{details.nb_tot_action}} actions - {{details.nb_visits}} visits - {{details.tot_time.d}}d {{details.tot_time.h}}h {{details.tot_time.m}}m {{details.tot_time.s}}s</h5>
						</div>

						<div ng-repeat='visit in details.visits'>
							<div class='col-md-12'>

								<h5>{{visit.nb_action}} actions - {{visit.visit_time.d}}d {{visit.visit_time.h}}h {{visit.visit_time.m}}m {{visit.visit_time.s}}s</h5>
							</div>
							<div ng-repeat='action in visit.actions'>
								<div class='col-md-3'>{{action.time}} </div> 
								<div class='col-md-9'>{{action.action}} {{action.details}}</div>
							</div>


						</div>


					</div>
				</div>



			</div>
		</div>


	</div>

</body>
</html>









































