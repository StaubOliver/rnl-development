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


	echo 'ng-init = "selectedGenus=\''.$genus.'\'; selectedCollector=\''.$collector.'\'; selectedAgeMin='.$agemin.'; selectedAgeMax='.$agemax.'; logged_in='.$log.'; admin='.$admin.'; firstVisit=\''.$firstVisit.'\'"';
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
					<?php if($is_admin == 1): ?>
		 				<li><p class="navbar-text">Fossils loaded: {{nbfossils}}</p></li>
					<?php endif; ?>	
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php if($is_admin == 1): ?>
		 				<li><a href="/map/map_admin">Admin panel</a></li>
					<?php endif; ?>	

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
						href="https://www.facebook.com/sharer/sharer.php?u={{ share.url }}/map"
						target="#" ng-click="recordActivity('Sharing', 'Facebook')">
							<img class='social-network-icon' src="/assets/img/socialnetworks/facebook_white.png" alt="Facebook" height="32" width="32">
						</a>
					</li>
					
					<li>
						<a class="social-network-link"
						href="https://plus.google.com/share?url={{ share.url }}/map"
						target="#" ng-click="recordActivity('Sharing', 'Google+')"> 
							<img class='social-network-icon' src="/assets/img/socialnetworks/google_white.png" alt="Google" height="32" width="32">
						</a>
					</li>

					<li>
						<a href="#" data-toggle='modal' data-target='#ModalHelp' id='helpButton'>
							Help
						</a>
					</li>

					

					<?php if($logged_in == true): ?>
		 				<li><a href ="/auth/logout_map" class="navbar-link">Logout</a></li>
					<?php endif; ?>	
					<?php if($logged_in == false): ?>
						<li><a href ="/auth/create_user_map" class="navbar-link">Sign up</a></li>
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
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Mapping Manchester Museum Fossil Collection</h4>
				</div>

				<div class="modal-body">
					<h4>Welcome</h4>
					<p>
						This web site is an experiment built and ran by the Museum of Manchester and the School of Computer Science both from the Univrsity of Manchester.
					</p>
					
					<h4>How to use it</h4>
					<p>
						The application is divided into two parts: the map and the tools. 
						On the right hand side of the screen you will find three distinct sections:
					</p>

					<p>
						<strong>The map</strong>
						The map displays the fossils of the Manchester Museum Collection according to the filter tool's settings. Each marker represent a fossil. You can click on all of them to get a picture and more information. Markers colors is determined by the geological of the fossil :
					</p>

					<div class='row'>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/F9F97F/' alt='Quaternary'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/FFE619/' alt='Neogene'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/FD9A52/' alt='Paleogene'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/7FC64E/' alt='Cretaceous'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/34B2C9/' alt='Jurassic'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/C72B92/' alt='Triassic'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/F04028/' alt='Permian'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/67A599/' alt='Carboniferous'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/CB8C37/' alt='Silurian'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/009270/' alt='Ordovician'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/7FA056/' alt='Cambrian'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/934370/' alt='Precambrian'>
						</div>

						<div class='col-md-1'>
							<img class='map-legend-pin' src='http://www.googlemapsmarkers.com/v1/909090/' alt='Data Missing'>
						</div>




						<div class='col-md-1'>
							Quaternary
						</div>

					</div>
					
					<p>
						<strong>The filter section</strong>
						Three selectors allow you to select which part of the collection is being displayed on the map. You can select a specific time period, display 
					</p>

					<p>
						<strong>Your contribution</strong>
						This text box is here for you to share the discoveries you make about the data you explore. You can click on the markers on the map and select them to add them to your message.
					</p>

					<p>
						<strong>The community's discoveries</strong>
						This is the community's contributions, its content adapts according to the state of the filters.
					</p>
					
					<h4>Terms and privacy</h4>
					<p>
						To serve the purpose of the experiment this web site uses cookies.
					</p>	
				</div>
			</div>
		</div>
	</div>


	<div class="map-header">
		<img class="map-header-img" src="http://assets.manchester.ac.uk/logos/museum-1.png" alt="There should be an awsome logo" height="84" width="169">
	</div>

	<div class="map-legend">
		<div class='row'>
			<div class='col-md-12'>
				<h4 class='map-legend-title'><span class="glyphicon map-legend-title-glyph" aria-hidden="true" ng-click="show_legend=!show_legend" ng-class="{'glyphicon-chevron-down':!show_legend, 'glyphicon-chevron-up':show_legend}"></span>Legend</h4>
			</div>
		</div>
		<div class='row map-legend-content' id='map-legend-content' ng-show="show_legend">
			
		</div>
	</div>

	<div class="row main-layout">

		<div class="col-md-9 main-layout-left">
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

		<div class="col-md-3 main-layout-right">

			<!-- Filter  & Feedback -->
			<div class="row">
				

				<!-- Filter tool -->
				<div class="col-md-12 section" >
					<div class="filter-section">

						<div class='filter-reset-btn'>
							<span title='Reset filter' class="glyphicon glyphicon-repeat" ng-click="resetFilter()"></span>
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
						<div class="row ">
							<div class="col-md-6 filter-element">
								<p class='filter-element-title'>Collector</p>
								<select class="form-control" id="fossilCollectorSelect" ng-model="selectedCollector" ng-change="clear_selected_markers(); newCollector()"  ng-mouseover="recordActivity('Collector Selector Hover', '')">
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
							<div class="col-md-6 filter-element">
								<p class='filter-element-title'>Genus</p>
								<select class="form-control" id="fossilGenusSelect" ng-model="selectedGenus" ng-change="clear_selected_markers(); newGenus()" ng-mouseover="recordActivity('Genus Selector Hover', '')">
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
				<div class="col-md-12 section">
					<div name='write-section' class="feedback-section">

						<div class="row">
							<div class="col-md-12 feedback-title">
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
									<div class="col-md-12">
										<textarea class="form-control feedback-message-form-textarea" ng-model='feedback_form_text' ng-change="feedback_form_error=''; recordActivity('Writing comment', feedback_form_text)" rows="2"></textarea>
									</div>
									<div class="col-md-12">
										<span ng-show="selected_markers.length==0">You can select fossils from the map</span>
										<span ng-show="selected_markers.length==1">{{selected_markers.length}} fossil selected</span>
										<span ng-show="selected_markers.length>1">{{selected_markers.length}} fossils selected</span>

										<div ng-repeat='marker in selected_markers'>
											<div class='btn btn-custom-default btn-xs fossil-selection' ng-click="remove_marker(marker, $index)" ng-mouseover="highlight_marker(marker)" ng-mouseleave="remove_highlight(marker)"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>{{ marker['title']}}
											</div>
										</div>
									</div>
									<div class="col-md-6 feedback-clear-btn">
										<button type="button" class="btn btn-custom-default btn-sm" ng-show="selected_markers.length > 0" ng-click="clear_selected_markers(); recordActivity('Clear Fossil selection', '')">Clear</button>
									</div>
									<div class="col-md-6 feedback-submit-btn">
										<button type="button" class="btn btn-custom-primary btn-sm" ng-click="submitfeedback()">Send</button>
									</div>
									<div class="col-md-12 feedback-form-error" ng-show="feedback_form_error">
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
				<div class='col-md-12 section'>
					<div class='feedback-section'>

						<div class="row">
							<div class="col-md-12 feedback-title">
								<h4>{{section_feedbacks_title}}</h4>
							</div>
						</div>

							<div ng-repeat='feedback in feedbacks' ng-show='feedbacks.length'>


								<div class="feedback-message " ng-mouseover="recordActivity('Feedback mouse over', feedback['feedback_id']+ ' '+feedback['message']); mouseoverFeedback(feedback)" ng-mouseleave="mouseleaveFeedback()">

									<!--
									<div class='feedback-message-delete-btn' ng-click="" ng-show="(logged_in && feedback['user_id']==user_id) || admin"> 
										<span class="glyphicon glyphicon-remove"></span>
									</div>
									-->

									<div class="row">

										<div class="col-md-8 feedback-message-author">
											<p>{{feedback['first_name']}} {{feedback['last_name']}} on {{feedback['time'].split(" ")[0]}}</p>
										</div>

										<div class='col-md-4 '>

											<a class="social-network-link" 
											href="https://plus.google.com/share?url={{ share.url }}/map"
											target="#" ng-click="recordActivity('Sharing', 'Google+ message '.feedback['feedback_id'])">

												<img class='feedback-message-social' src="/assets/img/socialnetworks/google_light.png" alt="Google" height="18" width="18">

											</a>

											<a class="social-network-link" 
											href=="https://www.facebook.com/sharer/sharer.php?u={{ share.url }}/map" 
											target="#" ng-click="recordActivity('Sharing', 'Facebook message '.feedback['feedback_id'])">
											
												<img class='feedback-message-social' src="/assets/img/socialnetworks/facebook_light.png" alt="Facebook" height="18" width="18">

											</a>

											<a class="social-network-link" 
											href="https://www.reddit.com/submit?url=https://{{share_url}}/map" 
											target="#" ng-click="recordActivity('Sharing', 'Reddit message '.feedback['feedback_id'])">

												<img class='feedback-message-social' src="/assets/img/socialnetworks/reddit_light.png" alt="Reddit" height="18" width="18">

											</a>

											<a class="social-network-link" 
											href="http://www.twitter.com/share?text=Check out Manchester Museum's fossil collection ! @McrMuseum @TheStudyMcr&url=https://{{share_url}}/map" 
											target="#" ng-click="recordActivity('Sharing', 'Twitter message '.feedback['feedback_id'])">
											
												<img class='feedback-message-social' src="/assets/img/socialnetworks/twitter_light.png" alt="Twitter" height="18" width="18">

											</a>

										</div>

										<div class="col-md-12 feedback-message-text">
											<p>{{feedback['message']}}</p>
										</div>
										
										<!--
										<div class="col-md-6 feedback-message-author">
											<p>{{feedback['first_name']}} {{feedback['last_name']}}</p>
										</div>

										<div class="col-md-6 feedback-message-time">
											<p>{{feedback['time']}}</p>
										</div>-->

										<div class="col-md-6 feedback-message-buttons">
											<a class='feedback-message-button-up' href='#' ng-show="!feedback.user_has_upvote" ng-click="upvoteFeedback(feedback.feedback_id)">Upvote</a> 
											<span ng-show="feedback.user_has_upvote">Upvoted</span>
											<a class='feedback-message-button-reply' href='' ng-click="replyFeedback(feedback.feedback_id)" ng-hide="show_feedback_reply[feedback.feedback_id]">Reply</a>
											<a class='feedback-message-button-reply' href='' ng-click="replyFeedback(feedback.feedback_id)" ng-show="show_feedback_reply[feedback.feedback_id]">Cancel reply</a>
										</div>

										<div class="col-md-6 feedback-message-info">
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

									<div class="feedback-message-reply " ng-mouseover="recordActivity('Feedback mouse over', rep['feedback_id']+ ' '+rep['message']); mouseoverFeedback(rep)" ng-mouseleave="mouseleaveFeedback()">

										<!--
										<div class='feedback-message-delete-btn' ng-click="" ng-show="(logged_in && feedback['user_id']==user_id) || admin"> 
											<span class="glyphicon glyphicon-remove"></span>
										</div>
										-->

										<div class='row'>

											<div class="col-md-8 feedback-message-author">
												<p>{{feedback['first_name']}} {{feedback['last_name']}} on {{feedback['time'].split(" ")[0]}}</p>
											</div>

											<div class='col-md-4 '>

											<img class='feedback-message-social' src="/assets/img/socialnetworks/google_light.png" alt="Google" height="18" width="18">
											
											<img class='feedback-message-social' src="/assets/img/socialnetworks/facebook_light.png" alt="Facebook" height="18" width="18">

											<img class='feedback-message-social' src="/assets/img/socialnetworks/reddit_light.png" alt="Reddit" height="18" width="18">
											
											<img class='feedback-message-social' src="/assets/img/socialnetworks/twitter_light.png" alt="Twitter" height="18" width="18">

										</div>

											<div class="col-md-12 feedback-message-text">
												<p>{{rep['message']}}</p>
											</div>

											<div class="col-md-6 feedback-message-buttons">
												<a ng-show="!rep.user_has_upvote" class='feedback-message-button-up' href='#' ng-click="upvoteFeedback(rep.feedback_id)">Upvote</a>
												<span ng-show="rep.user_has_upvote">Upvoted</span>
											</div>

											<div class="col-md-6 feedback-message-info">
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

