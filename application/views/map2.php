<!DOCTYPE html>
<html lang="en" ng-app="map" 
<?php if($logged_in == true)
	{
		$log = "true";
	}
	else
	{
		$log = "false";
	}
	if($is_admin==1){
		$admin="true";
	}
	else
	{
		$admin="false";
	}

	echo 'ng-init = "selectedGenus='.$genus.'; selectedCollector=-1; logged_in='.$log.'; admin='.$admin.'"';
?>
>
<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mapping the Fossil' Collection</title>
	
	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
	<script src="/assets/js/map.js"></script>
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
	
	<!-- Google Map API -->
	<script src="http://maps.googleapis.com/maps/api/js"></script>
	
	<!--<script>
		function initialize() {
		  var mapProp = {
		    center:new google.maps.LatLng(51.508742,-0.120850),
		    zoom:5,
		    mapTypeId:google.maps.MapTypeId.ROADMAP,
		    mapTypeControl:false,
		    streetViewControl:false
		  };
		  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
		}
		google.maps.event.addDomListener(window, 'load', initialize);

	</script>-->

	<!-- RZ Slider -->
	<script src="/assets/js/rzslider.js"></script>
	<link rel="stylesheet" href="/assets/css/rzslider.css">

</head>

<body ng-controller="GoogleMap">
	<!-- Header -->
	<nav class="navbar-default  navbar-fixed-top" >
		<div class="container-fluid">

			<div class="navbar-header">
				<a class="navbar-brand" href="#">Map</a>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><p class="navbar-text">Hello {{ profile.first_name }}</p></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php if($is_admin == 1): ?>
		 			<li><a href="/map/map_admin">Admin panel</a></li>
					<?php endif; ?>	
					<li><p class="navbar-text">Profil</p></li>
					<li><p class="navbar-text">Share</p></li>
					<?php if($logged_in == true): ?>
		 			<li><a href ="/auth/logout" class="navbar-link">Logout</a></li>
					<?php endif; ?>	
					<?php if($logged_in == false): ?>
		 			<li><a href ="/auth/login" class="navbar-link">Login</a></li>
					<?php endif; ?>	
				</ul>
			</div>
		</div>
	</nav>

	<div class="map-header">
		<img class="map-header-img" src="http://assets.manchester.ac.uk/logos/museum-1.png" alt="There should be an awsome logo" height="84" width="169">
	</div>

	<div class="map-legend">
		<div class='row'>
			<div class='col-md-12'>
				<h4 class='map-legend-title'><span class="glyphicon map-legend-title-glyph" aria-hidden="true" ng-click="show_legend=!show_legend" ng-class="{'glyphicon-chevron-down':!show_legend, 'glyphicon-chevron-up':show_legend}"></span>Legend</h4>
			</div>
		</div>
		<div class='row map-legend-content' ng-show="show_legend">
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/F9F97F/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Quaternary</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/FFE619/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Neogene</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/FD9A52/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Paleogene</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/7FC64E/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Cretaceous</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/34B2C9/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Jurassic</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/C71B92/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Triassic</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/F04028/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Permian</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/67A599/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Carboniferous</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/CB8C37/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Devonian</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/B3E1B6/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Silurian</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/009270/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Ordovician</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/7F1056/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Cambrian</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/934370/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Precambrian</p>
			</div>
			<div class='col-md-3'>
				<img class="map-legend-pin" src="http://www.googlemapsmarkers.com/v1/909090/" alt="Hello there">
			</div>
			<div class='col-md-9'>
				<p class='map-legend-content-title'>Data Missing</p>
			</div>
		</div>
	</div>

	<div class="row main-layout">

		<div class="col-md-9 main-layout-left">
			<!-- Map -->
			<div class='controller-googleMap'>
				

				<!-- Modal to show large pictures -->
				<div class="modal fade" id="Modal-lg-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="modal-image-title"></h4>
							</div>
							<div class="modal-body" id="modal-image-body">
				

							</div>
							<!--
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
							-->
						</div>
					</div>
				</div>
				<!--
				<div class="map-loading" ng-show="loading">
					<h2 >Loading...</h2>
				</div>
				-->
				<div class="map-section">
					<div id="googleMap"></div>
				</div>
			</div>

		</div>

		<div class="col-md-3 main-layout-right">

			<!-- Filter  & Feedback -->
			<div class="row">
				<!-- Filter -->
				<div class="col-md-12" >
					<div class="filter-section">
				
						<div class="row">
							<div class="col-md-12 filter-title">
								<h4>Filter what's being plotted on the map</h4>
							</div>
						</div>

						<!-- Project -->
						<!--
						<div class="row filter-element">
							<div class="col-md-3">
								<h6>Projects</h6>
							</div>
							<div class="col-md-8">
								<select class="form-control" id="fossilProjectSelect" ng-model="selectedProject"  ng-mouseover="recordActivity('Project Selector Hover')">
									<option value="-1" selected>All Projects</option>
									<optgroup label="Available projects">	
									<?php
										foreach($projects as $p){
											//if ($genus != "" and $genus != "Not listed" and $genus != "Missing") {
												echo "<option value='".$p."'>".$p."</option>";
											//}
										}
									?>
									</optgroup>
								</select>
							</div>
						</div>
						-->

						<!-- Place -->
						<!--
						<div class="row filter-element">
							<div class="col-md-3">
								<h6>Place</h6>
							</div>
							<div class="col-md-9">
								<h6>Go to a place</h6>
							</div>
						</div>
						-->

						<!-- Geological Age --> 
						<div class="row filter-element">
							<div class="col-md-12">
								<p class='filter-element-title'>Geological Age</p>
							</div>
							<div class="col-md-12">
								<rzslider
							    rz-slider-model="selectedAgeMin"
							    rz-slider-high="selectedAgeMax"
							    rz-slider-options="slider.options"></rzslider>
							</div>
						</div>
						
						<!-- Collector -->
						<div class="row filter-element">
							<div class="col-md-12">
								<p class='filter-element-title'>Collector</p>
							</div>
							<div class="col-md-12">
								<select class="form-control" id="fossilCollectorSelect" ng-model="selectedCollector" ng-change="clear_selected_markers(); newCollector()"  ng-mouseover="recordActivity('Collector Selector Hover')">
									<option value="-1" selected>All Collectors</option>
									<optgroup label="Collectors">	
									<?php
										foreach($collectors as $collector){
											//if ($genus != "" and $genus != "Not listed" and $genus != "Missing") {
												echo "<option value='".$collector."'>".$collector."</option>";
											//}
										}

									?>
									</optgroup>
								</select>
							</div>
						</div>

						<!-- Genus --> 
						<div class="row filter-element">
							<div class="col-md-12">
								<p class='filter-element-title'>Genus</p>
							</div>
							<div class="col-md-12">
								<select class="form-control" id="fossilGenusSelect" ng-model="selectedGenus" ng-change="clear_selected_markers(); newGenus()" ng-mouseover="recordActivity('Genus Selector Hover')">
									<option value="-1" selected>All Genera</option>
									<optgroup label="Coral genera">	
									<?php
										foreach($genuses as $genus){
											//if ($genus != "" and $genus != "Not listed" and $genus != "Missing") {
												echo "<option value='".$genus['genus']."'>".$genus['genus']." (".$genus['count'].")"."</option>";
											//}
										}

									?>
									</optgroup>
								</select>
							</div>
						</div>

					</div>	
				</div>

				<!-- Feedback -->
				<div class="col-md-12">
					<div class="feedback-section">

						<div class="row">
							<div class="col-md-12 feedback-title">
								<h4>Share your discoveries</h4>
							</div>
						</div>


						<div class="feedback-message-form">
							<form>
								<div class="row feedback-form">
									<div class="col-md-12">
										<textarea class="form-control feedback-message-form-textarea" ng-model='feedback_form_text' ng-change="feedback_form_error=''; recordActivity('Writing comment : '+feedback_form_text)" rows="2"></textarea>
									</div>
									<div class="col-md-12">
										<p ng-show="selected_markers.length==0">You can select fossils from the map</p>
										<p ng-show="selected_markers.length==1">{{selected_markers.length}} fossil selected</p>
										<p ng-show="selected_markers.length>1">{{selected_markers.length}} fossils selected</p>

										<div ng-repeat='marker in selected_markers'>
											<div class='btn btn-custom-default btn-xs fossil-selection' ng-click="remove_marker(marker, $index)" ng-mouseover="highlight_marker(marker)" ng-mouseleave="remove_highlight(marker)"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>{{ marker['title'].split("-")[1]}}
											</div>
										</div>
									</div>
									<div class="col-md-6 feedback-clear-btn">
										<button type="button" class="btn btn-custom-default btn-sm" ng-show="selected_markers.length > 0" ng-click="clear_selected_markers(); recordActivity('Clear Fossil selection')">Clear</button>
									</div>
									<div class="col-md-6 feedback-submit-btn">
										<button type="button" class="btn btn-custom-primary btn-sm" ng-click="submitfeedback()">Save</button>
									</div>
									<div class="col-md-12 feedback-form-error" ng-show="feedback_form_error">
										{{feedback_form_error}}
									</div>
									<div class="col-md-12 " ng-show="!logged_in">
										Since you're not logged in your message will be anonymous
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Share on social media -->
				<div class="col-md-12">
					<div class="feedback-section">

						<div class="row">
							<div class="col-md-7 feedback-title">
								<h4>Share on your networks</h4>
							</div>
							<div class="col-md-4">
								<img class='social-network-icon' src="/assets/img/socialnetworks/twitter.png" alt="There should be an awsome logo" height="24" width="24"> 
								<img class='social-network-icon' src="/assets/img/socialnetworks/facebook.png" alt="There should be an awsome logo" height="24" width="24"> 
								<img class='social-network-icon' src="/assets/img/socialnetworks/google.png" alt="There should be an awsome logo" height="24" width="24">
							</div>
						</div>

						<!--
						<div class="feedback-message-form">
							<form>
								<div class="row feedback-form">
									<div class='col-md-4'>
										
									</div>
									<div class='col-md-4'>
										
									</div>
									<div class='col-md-4'>
										
									</div>
								</div>
							</form>
						</div>
						-->
					</div>
				</div>

				<div class='col-md-12'>
					<div class='feedback-section'>

						<div class="row">
							<div class="col-md-12 feedback-title">
								<h4>See what others have found</h4>
							</div>
						</div>

							<div ng-repeat='feedback in feedbacks' ng-show='feedbacks.length'>


								<div class="feedback-message " ng-mouseover="recordActivity('Feedback mouse over '+feedback['feedback_id']+ ' '+feedback['message']); mouseoverFeedback(feedback)" ng-mouseleave="mouseleaveFeedback()">
									<div class='feedback-message-delete-btn' ng-click="" ng-show="(logged_in && feedback['user_id']==user_id) || admin"> 
										<span class="glyphicon glyphicon-remove"></span>
									</div>
									<div class="row">
										<div class="col-md-12 feedback-message-text">
											<p>{{feedback['message']}}</p>
										</div>

										<div class="col-md-6 feedback-message-author">
											<p>{{feedback['first_name']}} {{feedback['last_name']}}</p>
										</div>

										<div class="col-md-6 feedback-message-time">
											<p>{{feedback['time']}}</p>
										</div>
										
										<div class='col-md-2' ng-hide="{{feedback.user_has_upvote}}" ng-click="upvoteFeedback(feedback.feedback_id)">
											<button class='btn btn-custom-default btn-xs'>Upvote</button>
										</div>

										<div class="col-md-4 feedback-message-upvote" ng-show="feedback.upvote > 0">
											<p >{{feedback.upvote}} upvotes</p>
										</div>

										<div class="col-md-6 feedback-message-upvote" ng-class="{'col-md-offset-2':feedback.user_has_upvote, 'col-md-offset-4':feedback.upvote==0}">
											<p ng-show="feedback.selection.length">{{feedback.selection.length}} fossils selected</p>
										</div>
									</div>
								</div>
							</div>

							<div ng-hide='feedbacks.length'>
								<div class="feedback-message">
									<div class="row">
										<div class="col-md-12 feedback-message-text">
											<p>No messages here yet</p>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				

			</div>

		</div>

	</div>

</body>
</html>

