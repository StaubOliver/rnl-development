<!DOCTYPE html>
<html lang="en" ng-app="map" 

<?php 
	if($logged_in == true)
	{
		$log = "true";
	}
	else
	{
		$log = "false";
	}
	
	if($is_admin==1)
	{
		$admin="true";
	}
	else
	{
		$admin="false";
	}

	echo 'ng-init = "selectedGenus=\''.$genus.'\'; selectedCollector=\''.$collector.'\'; selectedAgeMin='.$agemin.'; selectedAgeMax='.$agemax.'; logged_in='.$log.'; admin='.$admin.'; firstVisit=\''.$firstVisit.'\'"';
?>

>
<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mapping the Fossils' Collection</title>
	
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
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIwlL-wm53SyhrnSAqCRL9SDGwhTVsq7c"></script>
	
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

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-83675122-1', 'auto');
	  ga('send', 'pageview');

	</script>

</head>

<body ng-controller="GoogleMap">
	<!-- Header -->
	<nav class="navbar-default  navbar-fixed-top" >
		<div class="container-fluid">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">{{ profile.first_name }}</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?php if($is_admin == 1): ?>
		 				<li><p class="navbar-text">Fossils loaded: {{nbfossils}}</p></li>
					<?php endif; ?>	
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php if($is_admin == 1): ?>
		 				<li><a href="/map/map_admin">Admin</a></li>
		 				<li><a href="/map/map_stats">Stats</a></li>
					<?php endif; ?>	

					<!--
					<li style='display: inline-block'>
						<p class="navbar-text">Share</p>
						<a class="social-network-link" 
						href="http://www.twitter.com/share?text=Check out Manchester Museum's fossil collection ! @McrMuseum @TheStudyMcr&url=https://{{share_url}}/map" 
						target="#" ng-click="recordActivity('Sharing', 'Twitter')">
						<img class='social-network-icon img-responsive' src="/assets/img/socialnetworks/twitter_white.png" alt="Twitter" height="32" width="32">
						</a>

						<a class="social-network-link"
						href="https://www.reddit.com/submit?url=https://{{share_url}}/map"
						target="#" ng-click="recordActivity('Sharing', 'Reddit')"> 
							<img class='social-network-icon img-responsive' src="/assets/img/socialnetworks/reddit_white.png" alt="Reddit" height="32" width="32">
						</a>

						<a class="social-network-link"
						href="https://www.facebook.com/sharer/sharer.php?u={{ share_url }}/map"
						target="#" ng-click="recordActivity('Sharing', 'Facebook')">
							<img class='social-network-icon img-responsive' src="/assets/img/socialnetworks/facebook_white.png" alt="Facebook" height="32" width="32">
						</a>

						<a class="social-network-link"
						href="https://plus.google.com/share?url={{ share_url }}/map"
						target="#" ng-click="recordActivity('Sharing', 'Google+')"> 
							<img class='social-network-icon img-responsive' src="/assets/img/socialnetworks/google_white.png" alt="Google" height="32" width="32">
						</a>

					</li>
					-->

					
					<li><p class="navbar-text">Share</p></li>

					<li>
						<a class="social-network-link" 
					href="http://www.twitter.com/share?text=Check out Manchester Museum's fossil collection ! @McrMuseum @TheStudyMcr&url=https://{{share_url}}/map" 
					target="#" ng-click="recordActivity('Sharing', 'Twitter')">
						<img class='social-network-icon' src="/assets/img/socialnetworks/twitter_white.png" alt="Twitter" height="32" width="32">
						</a>
					</li>

					<li>
						<a class="social-network-link"
						href="https://www.reddit.com/submit?url=https://{{share_url}}/map"
						target="#" ng-click="recordActivity('Sharing', 'Reddit')"> 
							<img class='social-network-icon' src="/assets/img/socialnetworks/reddit_white.png" alt="Reddit" height="32" width="32">
						</a>
					</li>

					<li>
						<a class="social-network-link"
						href="https://www.facebook.com/sharer/sharer.php?u={{ share_url }}/map"
						target="#" ng-click="recordActivity('Sharing', 'Facebook')">
							<img class='social-network-icon' src="/assets/img/socialnetworks/facebook_white.png" alt="Facebook" height="32" width="32">
						</a>
					</li>
					
					<li>
						<a class="social-network-link"
						href="https://plus.google.com/share?url={{ share_url }}/map"
						target="#" ng-click="recordActivity('Sharing', 'Google+')"> 
							<img class='social-network-icon' src="/assets/img/socialnetworks/google_white.png" alt="Google" height="32" width="32">
						</a>
					</li>

					

					<li>
						<a href="#" data-toggle='modal' data-target='#ModalHelp' id='helpButton' ng-click='recordActivity("Open Help", "")'>
							Help and terms
						</a>
					</li>


					
					

					<?php if($logged_in == true): ?>
		 				<li><a href ="/auth/logout_map" class="navbar-link">Logout</a></li>
					<?php endif; ?>	
					<?php if($logged_in == false): ?>
						<!--<li><a href ="/auth/create_user_map" class="navbar-link">Sign up</a></li>-->
		 				<li><a href ="/auth/login_map" class="navbar-link">Login</a></li>
					<?php endif; ?>	
				</ul>
			</div>
		</div>
	</nav>

	<!-- Modal to show large pictures -->
	<div class="modal fade" id="ModalHelp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<div class='row'>
						<div class='col-md-6 col-sm-6 col-xs-12'>
							<h4 class="modal-title">Mapping Manchester Museum Fossil Collection</h4>
						</div>
						<div class='col-md-6 col-sm-6 col-xs-12 help_terms_close_btn'>
							<button type="button" class="btn btn-xs btn-custom-primary" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> Close</button>
						</div>
					</div>
				</div>

				<div class="modal-body">
					<h4>Welcome</h4>
					<p>
						This website is an experiment built and ran by the Museum of Manchester and the School of Computer Science both from the University of Manchester.
						</br>
						Feel free to explore the collection and maybe discover some cool things about fossils or their locations.
						Note that the collection is huge and has not being digitized completely. More items to come stay tuned !
						</br>
						We'd love to read about what you've discovered.
					</p>

					<h4>How it works</h4>
					<div class='row'>

						<div class='col-md-6 col-sm-6 col-xs-12'>
							</br>
							<p class='help-tutorial-center'>Zoom, pan and filter to isolate something interesting like a particular pattern.
							</p>
							<img class='help-tutorial-img' src='https://natureslibrary.co.uk/assets/img/welcome_map/01.JPG' alt='Zoom, pan and filter to focus on something interesting'>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12'>
							</br>
							<p class='help-tutorial-center'>Click on markers to get more information.
							</p>
							<img class='help-tutorial-img' src='https://natureslibrary.co.uk/assets/img/welcome_map/02.JPG' alt='Click on markers to get more information'>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12'>
							</br>
							<p class='help-tutorial-center'>Click on the select button to add fossils to your contribution.
							</p>
							<img class='help-tutorial-img' src='https://natureslibrary.co.uk/assets/img/welcome_map/03.JPG' alt='Click on the select button to add them to your comment'>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12'>
							</br>
							<p class='help-tutorial-center'>Write a contribution a share it with the community.
							</p>
							<img class='help-tutorial-img' src='https://natureslibrary.co.uk/assets/img/welcome_map/04.JPG' alt='Write and send a comment !'>
						</div>
					</div>

					
					<h4>In more details</h4>
					<p>
						The application is divided into two parts: the map and the tools. 
						On the right-hand side of the screen you will find three distinct sections:
					</p>

					<p>
						<strong>The map</strong>
						The map displays the fossils of the Manchester Museum Collection according to the filter tool's settings. Each marker represents a fossil. You can click on all of them to get a picture and more information. Markers color's is determined by the geological age of the fossil:
					</p>

					<div class='row'>

						<div class='col-md-1 col-md-offset-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/F9F97F.png' alt='Quaternary'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Quaternary
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/FFE619.png' alt='Neogene'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Neogene
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/FD9A52.png' alt='Paleogene'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Paleogene
						</div>
					
					</div>

					<div class='row'>

						<div class='col-md-1 col-md-offset-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/7FC64E.png' alt='Cretaceous'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Cretaceous
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/34B2C9.png' alt='Jurassic'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Jurassic
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/C72B92.png' alt='Triassic'>
						</div>
						<div class='col-md-3 col-sm-2 col-xs-2'>
							Triassic
						</div>

					</div>

					<div class='row'>

						<div class='col-md-1 col-md-offset-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/F04028.png' alt='Permian'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Permian
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/67A599.png' alt='Carboniferous'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Carboniferous
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/CB8C37.png' alt='Devonian'>
						</div>
						<div class='col-md-3 col-sm-2 col-xs-2'>
							Devonian
						</div>

					</div>

					<div class='row'>

						<div class='col-md-1 col-md-offset-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/B3E1B6.png' alt='Silurian'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Silurian
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/009270.png' alt='Ordovician'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Ordovician
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/7F1056.png' alt='Cambrian'>
						</div>
						<div class='col-md-3 col-sm-2 col-xs-2'>
							Cambrian
						</div>

					</div>

					<div class='row'>

						<div class='col-md-1 col-md-offset-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/934370.png' alt='Precambrian'>
						</div>
						<div class='col-md-2 col-sm-2 col-xs-2'>
							Precambrian
						</div>

						<div class='col-md-1 col-sm-1 col-xs-1'>
							<img class='map-legend-pin' src='https://natureslibrary.co.uk/assets/img/markers/909090.png' alt='Data Missing'>
						</div>
						<div class='col-md-3 col-sm-4 col-xs-4'>
							Data Missing
						</div>

					</div>
					
					<p>
						<strong>The filter section</strong>
						Three selectors allow you to select which part of the collection is being displayed on the map. You can select a specific time period, display. 
					</p>

					<p>
						<strong>Your contribution</strong>
						This text box is here for you to share the discoveries you make about the data you explore. You can click on the markers on the map and select them to add them to your message.
					</p>

					<p>
						<strong>The community's discoveries</strong>
						This is the community's contributions, its content adapts according to the state of the filters.
					</p>

					<h4>The experiment</h4>
					
					<p>
					This web application is also an experiment aiming at understanding the way people make discoveries, particularly on the web.




					<h4>Terms and privacy</h4>
					<p>
						<strong>Cookies and privacy</strong>
						This website and web application uses tracking session cookies for user authentication. Details including IP address, user-agent and user activity are logged on the server to improve the service and for research activities. By using this website and application you consent to the use of tracking and data logging.
						</br>
						<strong>Research activities</strong>
						This web application is currently being used for a research study by the University of Manchester School of Computer Science. Further details of this study can be found here: <a href="/doc/pis_map.pdf" target="_blank">natureslibrary.co.uk/doc/pis_map.pdf</a>.
						</br>
						<strong>Further information</strong>
						Please contact support@natureslibrary.co.uk if you require further information or clarification of the terms and conditions of usage.
					</p>	
				</div>

				<div class='modal-footer'>
					<div class='row'>
						<div class='col-md-12 col-sm-12 col-xs-12 help_terms_close_btn_bottom'>
							<button type="button" class="btn btn-xs btn-custom-primary" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span> Close</button>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


	<div class="map-header">
		<img class="map-header-img" src="http://assets.manchester.ac.uk/logos/museum-1.png" alt="There should be an awsome logo" height="84" width="169">
	</div>

	<div class="map-legend">
		<div class='row'>
			<div class='col-md-12 col-sm-12 col-xs-12'>
				<h4 class='map-legend-title'><span class="glyphicon map-legend-title-glyph" aria-hidden="true" ng-click="show_legend=!show_legend" ng-class="{'glyphicon-chevron-down':!show_legend, 'glyphicon-chevron-up':show_legend}"></span>Legend</h4>
			</div>
		</div>
		<div class='row map-legend-content' id='map-legend-content' ng-show="show_legend">
			
		</div>
	</div>

	<div class="row main-layout">

		<div class="col-md-9 col-sm-12 col-xs-12 main-layout-left">
			<!-- Map -->
			<div class='controller-googleMap'>
				
				<!-- Modal to show large pictures -->
				<div class="modal fade" id="ModalImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="modal-image-title"></h4>
							</div>
							<div class="modal-body" id="modal-image-body">
				

							</div>
						</div>
					</div>
				</div>

				<!-- map canvas -->
				<div class="map-section">
					<div id="googleMap"></div>
				</div>

			</div>

		</div>

		<div class="col-md-3 col-sm-12 col-xs-12 main-layout-right">

			<!-- Filter  & Feedback -->
			<div class="row">
				
				<div class='col-sm-12 col-xs-12 mobile_help'>
					<h5><span class="glyphicon glyphicon-chevron-down"></span> Scroll to filter <span class="glyphicon glyphicon-chevron-down"></span></h5>
				</div>

				<!-- Filter tool -->
				<div class="col-md-12 col-sm-12 col-xs-12 section" >
					<div class="filter-section">

						<div class='filter-reset-btn'>
							<span title='Reset filter' class="glyphicon glyphicon-repeat" ng-click="resetFilter(); recordActivity('Reset Filter', '')"></span>
						</div>
				
						<div class="row">
							<div class="col-md-12 filter-title">
								<h4>{{section_filter_title}} </h4>
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
							<div class="col-md-12 col-sm-12 col-xs-12">
								<p class='filter-element-title'>Geological Age</p>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12" ng-mouseenter="recordActivity('Geological Age Selector Hover', '')">
								<rzslider
							    rz-slider-model="selectedAgeMin"
							    rz-slider-high="selectedAgeMax"
							    rz-slider-options="slider.options"></rzslider>
							</div>
						</div>
						
						<!-- Collector -->
						<div class="row ">
							<div class="col-md-6 col-sm-6 col)xs-6 filter-element">
								<p class='filter-element-title'>Collector</p>
								<select class="form-control" id="fossilCollectorSelect" ng-model="selectedCollector" ng-change="clear_selected_markers(); newCollector()"  ng-mouseenter="recordActivity('Collector Selector Hover', '')">
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
						

							<!-- Genus --> 
							<div class="col-md-6 col-sm-6 col)xs-6 filter-element">
								<p class='filter-element-title'>Genus</p>
								<select class="form-control" id="fossilGenusSelect" ng-model="selectedGenus" ng-change="clear_selected_markers(); newGenus()" ng-mouseenter="recordActivity('Genus Selector Hover', '')">
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

				<!-- Write a feedback -->
				<div class="col-md-12 col-sm-12 col-xs-12 section">
					<div name='write-section' class="feedback-section">

						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 feedback-title">
								<h4>{{section_feedback_form_section_title}}</h4>
							</div>
						</div>

						<!--
						<div ng-show="replyto['reply']" class="feedback-message-form">
							<div class='row feedback-form'>
							 	<div class="col-md-12 feedback-form-author">
									<p>{{replyto.first_name}} {{replyto.last_name}} on {{replyto.time.split(" ")[0]}}</p>
								</div>

								<div class="col-md-12 feedback-form-message">
									<p><strong>{{replyto.message}}</strong></p>
								</div>

								<div class="col-md-6 feedback-form-button-cancel">
									<a class='' href='#' ng-click="cancelReplyFeedback(replyto.feedback_id)">Cancel reply</a>
								</div>

								<div class="col-md-6 feedback-form-info">
									<span>{{replyto.upvote}} up </span> 
									<span>{{replyto.selection.length}} fossils</span>
								</div>
								
							</div>
						</div>
						-->


						<div class="feedback-message-form">
							<form>
								<div class="row feedback-form">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<textarea class="form-control feedback-message-form-textarea" ng-model='feedback_form_text' ng-change="feedback_form_error=''; recordActivity('Writing comment', feedback_form_text)" rows="2"></textarea>
									</div>
									<div class="col-md-12 col-sm-12 col-xs-12">
										<span ng-show="selected_markers.length==0">You can select fossils from the map</span>
										<span ng-show="selected_markers.length==1">{{selected_markers.length}} fossil selected</span>
										<span ng-show="selected_markers.length>1">{{selected_markers.length}} fossils selected</span>

										<div ng-repeat='marker in selected_markers'>
											<div class='btn btn-custom-default btn-xs fossil-selection' ng-click="remove_marker(marker, $index)" ng-mouseover="highlight_marker(marker)" ng-mouseleave="remove_highlight(marker)"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>{{ marker['title']}}
											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6 feedback-clear-btn">
										<button type="button" class="btn btn-custom-default btn-sm" ng-show="selected_markers.length > 0" ng-click="clear_selected_markers(); recordActivity('Clear Fossil selection', '')">Clear</button>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6 feedback-submit-btn">
										<button type="button" class="btn btn-custom-primary btn-sm" ng-click="submitfeedback()">Send</button>
									</div>
									<div class="col-md-12 col-sm-12 col-xs-12 feedback-form-error" ng-show="feedback_form_error">
										{{feedback_form_error}}
									</div>
									<!--
									<div class="col-md-12 " ng-show="!logged_in">
										Since you're not logged in your message will be anonymous
									</div>
									-->
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Share on social media -->
				<!--
				<div class="col-md-12">
					<div class="feedback-section">

						<div class="row">
							<div class="col-md-8 feedback-title">
								<h4>{{section_social_sharing_title}}</h4>
							</div>
							<div class="col-md-1">
								<img class='social-network-icon' src="/assets/img/socialnetworks/twitter.png" alt="There should be an awsome logo" height="24" width="24">
							</div>
							<div class='col-md-1'>
								<img class='social-network-icon' src="/assets/img/socialnetworks/facebook.png" alt="There should be an awsome logo" height="24" width="24">
							</div>
							<div class='col-md-1'>
								<img class='social-network-icon' src="/assets/img/socialnetworks/google.png" alt="There should be an awsome logo" height="24" width="24">
							</div>
						</div>
					</div>
				</div>
				-->


				<!-- Contributions from the comunity -->
				<div class='col-md-12 col-sm-12 col-xs-12 section'>
					<div class='feedback-section'>

						<div class="row">
							<div class="col-md-12 feedback-title">
								<h4>{{section_feedbacks_title}}</h4>
							</div>
						</div>

						<div ng-repeat='feedback in feedbacks' ng-show='feedbacks.length'>

							<div class="feedback-message hover-link" ng-mouseenter="recordActivity('Feedback mouse over', feedback['feedback_id']+ ' '+feedback['message']); mouseoverFeedback(feedback)" ng-mouseleave="mouseleaveFeedback()" ng-click="clickFeedback(feedback.feedback_id)">

								<div class="row">

									<div class="col-md-8 col-sm-12 col-xs-12 feedback-message-author">
										<p>{{feedback['first_name']}} {{feedback['last_name']}} on {{feedback['time'].split(" ")[0]}}</p>
									</div>

									<div class='col-md-4 col-sm-12 col-xs-12'>

										<a class="social-network-link" 
										href="https://plus.google.com/share?url={{ share_url }}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}"
										target="#" ng-click="recordActivity('Share contribution', 'Google+ message '.feedback['feedback_id'])">

											<img class='feedback-message-social' src="/assets/img/socialnetworks/google_light.png" alt="Google" height="18" width="18">

										</a>

										<a class="social-network-link" 
										href=="https://www.facebook.com/sharer/sharer.php?u={{ share_url }}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}" 
										target="#" ng-click="recordActivity('Share contribution', 'Facebook message '.feedback['feedback_id'])">
										
											<img class='feedback-message-social' src="/assets/img/socialnetworks/facebook_light.png" alt="Facebook" height="18" width="18">

										</a>

										<a class="social-network-link" 
										href="https://www.reddit.com/submit?url=https://{{share_url}}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}" 
										target="#" ng-click="recordActivity('Share contribution', 'Reddit message '.feedback['feedback_id'])">

											<img class='feedback-message-social' src="/assets/img/socialnetworks/reddit_light.png" alt="Reddit" height="18" width="18">

										</a>

										<a class="social-network-link" 
										href="http://www.twitter.com/share?text=Check out Manchester Museum's fossil collection ! @McrMuseum @TheStudyMcr&url=https://{{share_url}}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}" 
										target="#" ng-click="recordActivity('Share contribution', 'Twitter message '.feedback['feedback_id'])">
										
											<img class='feedback-message-social' src="/assets/img/socialnetworks/twitter_light.png" alt="Twitter" height="18" width="18">

										</a>

									</div>

									<div class="col-md-12 col-sm-12 col-xs-12 feedback-message-text">
										<p>{{feedback['message']}}</p>
									</div>
									
									<!--
									<div class="col-md-6 feedback-message-author">
										<p>{{feedback['first_name']}} {{feedback['last_name']}}</p>
									</div>

									<div class="col-md-6 feedback-message-time">
										<p>{{feedback['time']}}</p>
									</div>-->

									<div class="col-md-6 col-sm-6 col-xs-6 feedback-message-buttons">
										<a class='feedback-message-button-up' href='#' ng-show="!feedback.user_has_upvote" ng-click="upvoteFeedback(feedback.feedback_id)">Upvote</a> 
										<span ng-show="feedback.user_has_upvote">Upvoted</span>
										<a class='feedback-message-button-reply' href='' ng-click="replyFeedback(feedback.feedback_id)" ng-hide="show_feedback_reply[feedback.feedback_id]">Reply</a>
										<a class='feedback-message-button-reply' href='' ng-click="replyFeedback(feedback.feedback_id)" ng-show="show_feedback_reply[feedback.feedback_id]">Cancel reply</a>
									</div>

									<div class="col-md-6 col-sm-6 col-xs-6 feedback-message-info">
										<span>{{feedback.upvote}} up </span> 
										<span>{{feedback.selection.length}} fossils</span>
									</div>

										
										<!--
										<div class='col-md-2' ng-hide="{{feedback.user_has_upvote}}" ng-click="upvoteFeedback(feedback.feedback_id)">
											<button class='btn btn-custom-default btn-xs'>Upvote</button>
										</div>

										<div class="col-md-4 feedback-message-upvote" ng-show="feedback.upvote > 0">
											<p >{{feedback.upvote}} up</p>
										</div>

										<div class="col-md-6 feedback-message-upvote" ng-class="{'col-md-offset-2':feedback.user_has_upvote, 'col-md-offset-4':feedback.upvote==0}">
											<p ng-show="feedback.selection.length">{{feedback.selection.length}} fossils</p>
										</div>
										-->
								</div>

							</div>

							<!-- replies to that feedback -->
							<div ng-repeat='rep in feedback.replies' ng-show='feedbacks.length'>

								<div class="feedback-message-reply " ng-mouseenter="recordActivity('Feedback mouse over', rep['feedback_id']+ ' '+rep['message']); mouseoverFeedback(rep)" ng-mouseleave="mouseleaveFeedback()">

									<!--
									<div class='feedback-message-delete-btn' ng-click="" ng-show="(logged_in && feedback['user_id']==user_id) || admin"> 
										<span class="glyphicon glyphicon-remove"></span>
									</div>
									-->

									<div class='row'>

										<div class="col-md-8 col-sm-12 col-xs-12 feedback-message-author">
											<p>{{feedback['first_name']}} {{feedback['last_name']}} on {{feedback['time'].split(" ")[0]}}</p>
										</div>

										<div class='col-md-4 col-sm-12 col-xs-12'>
										
											<a class="social-network-link" 
											href="https://plus.google.com/share?url={{ share_url }}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}"
											target="#" ng-click="recordActivity('Share contribution', 'Google+ message '.rep['feedback_id'])">

												<img class='feedback-message-social' src="/assets/img/socialnetworks/google_light.png" alt="Google" height="18" width="18">

											</a>

											<a class="social-network-link" 
											href=="https://www.facebook.com/sharer/sharer.php?u={{ share_url }}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}" 
											target="#" ng-click="recordActivity('Share contribution', 'Facebook message '.rep['feedback_id'])">
											
												<img class='feedback-message-social' src="/assets/img/socialnetworks/facebook_light.png" alt="Facebook" height="18" width="18">

											</a>

											<a class="social-network-link" 
											href="https://www.reddit.com/submit?url=https://{{share_url}}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}" 
											target="#" ng-click="recordActivity('Share contribution', 'Reddit message '.rep['feedback_id'])">

												<img class='feedback-message-social' src="/assets/img/socialnetworks/reddit_light.png" alt="Reddit" height="18" width="18">

											</a>

											<a class="social-network-link" 
											href="http://www.twitter.com/share?text=Check out Manchester Museum's fossil collection ! @McrMuseum @TheStudyMcr&url=https://{{share_url}}/map/{{selectedGenus}}/{{selectedCollector}}/{{selectedAgeMin}}/{{selectedAgeMax}}" 
											target="#" ng-click="recordActivity('Share contribution', 'Twitter message '.rep['feedback_id'])">
											
												<img class='feedback-message-social' src="/assets/img/socialnetworks/twitter_light.png" alt="Twitter" height="18" width="18">

											</a>

										</div>

									

										<div class="col-md-12 col-sm-12 col-xs-12 feedback-message-text">
											<p>{{rep['message']}}</p>
										</div>

											<div class="col-md-6 col-sm-6 col-xs-6 feedback-message-buttons">
												<a ng-show="!rep.user_has_upvote" class='feedback-message-button-up' href='#' ng-click="upvoteFeedback(rep.feedback_id)">Upvote</a>
												<span ng-show="rep.user_has_upvote">Upvoted</span>
											</div>

											<div class="col-md-6 col-sm-6 col-xs-6 feedback-message-info">
												<span>{{rep.upvote}} up </span> 
												<span>{{rep.selection.length}} fossils</span>
											</div>

										</div>

									</div>
								</div>

								<!-- reply area -->

								<div class="feedback-message-reply" ng-show='show_feedback_reply[feedback.feedback_id]'>
									<div class='row'>

										<div class="col-md-10">
											<textarea class="form-control feedback-message-form-textarea" ng-model='feedback_form_text_reply[feedback.feedback_id]' ng-change=" recordActivity('Writing reply', feedback.id+feedback_form_text)" rows="1"></textarea>
										</div>

										<div class="col-md-2 feedback-submit-reply-btn">
											<button type="button" class="btn btn-custom-primary btn-xs" ng-click="submitfeedback(feedback.feedback_id)">Send</button>
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

