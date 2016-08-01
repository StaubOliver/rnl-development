<!DOCTYPE html>
<html lang="en" ng-app='map_admin'>

<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin panel</title>
	
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
	
	<!-- Google Map API -->
	<script src="http://maps.googleapis.com/maps/api/js"></script>
	

</head>

<body class='admin-body' ng-controller='admin_map_feedbacks'>

	<nav class="navbar-default  navbar-fixed-top" >
		<div class="container-fluid">

			<div class="navbar-header">
				<a class="navbar-brand" href="#">Map</a>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><p class="navbar-text">Admin panel</p></li>
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
					<h4>Update location - <?php echo $location_update." Fossils to update" ?> </h4>
				</div>

				<div class='col-md-10 col-md-offset-1 admin-feedback'>
					<div class='row'>

						<div class='col-md-3'>
							<div class='btn btn-custom-primary btn-xl btn-update-location' ng-click="update_location()">
								Update Locations
							</div>	
						</div>
						
						<div class='col-md-9'>	
						<p class='p-update-location'>
							Use this button to use the Google Maps api to translate the country and place information for each fossil into coordinates. Limit: 1000 items per 24 hours.
						</p>
						</div>

						<div class='col-md-12 admin-update-notice' ng-show='show_update_notice' ng-click='update_location()'>
							<h5>The Update process is ongoing. It will take a while however this window can be closed.</h5>
						</div>

					</div>	
				</div>

				<div class='col-md-10 col-md-offset-1 admin-title'>
					<h4><?php echo 'Feedbacks - '.count($feedbacks).' feedbacks recorded'; ?></h4>
				</div>


				<div ng-repeat='feedback in feedbacks' ng-show='feedbacks.length'>

					<div class='col-md-10 col-md-offset-1' ng-class="{'admin-feedback':feedback.hidden==0, 'admin-feedback-hidden':feedback.hidden==1}">
				
						<div class='row'>

						<!--
							<div class="col-md-12 " id="map-{{feedback.feedback_id}}" ng-class="{'map-admin':show_map[feedback.feedback_id], 
							'map-admin-hidden':!show_map[feedback.feedback_id]}">
							</div>
						-->

							<div id ='map-{{feedback.feedback_id}}' class='col-md-12 map-admin'>
							</div>

							<div class='col-md-12 admin-message-head'>
								<div class='row'>

									<div class='col-md-8'>
										{{feedback["first_name"]}} {{feedback["last_name"]}}
										on
										{{feedback['time']}}
									</div>

									<div class='col-md-2 no-margin'>
										<div class='col-md-12 no-margin' ng-show="!show_map[feedback.feedback_id]">
											<div class='btn btn-custom-default btn-xs btn-admin btn-admin-map' ng-click='showMap(feedback.feedback_id)'><span class="glyphicon glyphicon-chevron-up"></span> Show the map</div>
										</div>

										<div class='col-md-12 no-margin' ng-show="show_map[feedback.feedback_id]">
											<div class='btn btn-custom-default btn-xs btn-admin btn-admin-map' ng-click='hideMap(feedback.feedback_id)'><span class="glyphicon glyphicon-chevron-down"></span> Hide the map</div>
										</div>
									</div>

									<div class='col-md-2 no-margin'>

										<div class='col-md-12 no-margin' ng-show="feedback.hidden == 0">
											<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide' ng-click="hideComment(feedback.feedback_id)">Hide comment</div>	
										</div>

										<div class='col-md-12 no-margin' ng-show="feedback.hidden == 1">
											<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide' ng-click="hideComment(feedback.feedback_id)">Unhide comment</div>	
										</div>

									</div>

								</div>
							</div>

							<div class='col-md-8 admin-feedback-vertical-ligne'>

								<div class='row'>
									
									<!--<div class='col-md-12'>
										{{feedback["first_name"]}} {{feedback["last_name"]}}
										on
										{{feedback['time']}}
									</div>
									-->

									<div class='col-md-12'>
										<div class='well well-sm well-message'>
											<strong>
												{{feedback['message']}}
											</strong>
										</div>
									</div>

									<div class='col-md-4'>
										{{feedback['upvote']}} upvotes
									</div>

									<div class='col-md-4'>
										Filter
									</div>

									<div class='col-md-4'>
										Geological Age
									</div>

									<div class='col-md-4'>
										{{feedback.selection.length}} fossils selected
									</div>

									<div class='col-md-4'>
										<div ng-show="feedback['genus']==-1">
											Genus: All
										</div>
										<div ng-show="feedback['genus']!=-1">
											Genus: {{feedback['genus']}}
										</div>
									</div>
									
									<div class='col-md-4' >
										From: {{feedback['age_min']}}
									</div>

									<div class='col-md-4' ng-show='feedback.replies.length == 0'>
										0 replies
									</div>

									<div class='col-md-4' ng-show='feedback.replies.length > 0'>
										<div class='btn btn-custom-default btn-xs btn-admin ' data-toggle='collapse' data-target='#{{feedback.feedback_id}}' aria-expanded="false" aria-controls='{{feedback.feedback_id}}' ng-click='show_replies(feedback.feedback_id)'><span class="glyphicon glyphicon-chevron-down"></span> Show {{feedback.replies.length}} replies</div>
									</div>

									<div class='col-md-4'>
										<div ng-show="feedback['collector']==-1">
											Collector: All
										</div>
										<div ng-show="feedback['collector']!=-1">
											Collector: {{feedback['collector']}}
										</div>
									</div>

									<div class='col-md-4'>
										To: {{feedback['age_max']}}
									</div>

									

									

									

									<!--
									<div class='col-md-12 admin-feedback-tool'>
										<div class='btn btn-custom-default btn-sm' ng-click='showMap(feedback.feedback_id, 0)'>See on the map</div>
										<div class='btn btn-custom-primary btn-sm'>Delete comment</div>	
										
									</div>
									-->

								</div>

							</div>

							<!--

							<div class='col-md-2'>
								<div class='row'>

									<div class='col-md-12' ng-show="!show_map[feedback.feedback_id]">
										<div class='btn btn-custom-default btn-xs btn-admin btn-admin-map' ng-click='showMap(feedback.feedback_id)'><span class="glyphicon glyphicon-chevron-up"></span> Show the map</div>
									</div>

									<div class='col-md-12' ng-show="show_map[feedback.feedback_id]">
										<div class='btn btn-custom-default btn-xs btn-admin btn-admin-map' ng-click='hideMap(feedback.feedback_id)'><span class="glyphicon glyphicon-chevron-down"></span> Hide the map</div>
									</div>

									<div class='col-md-12' ng-show="feedback.hidden == 0">
										<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide' ng-click="hideComment(feedback.feedback_id)">Hide comment</div>	
									</div>

									<div class='col-md-12' ng-show="feedback.hidden == 1">
										<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide' ng-click="hideComment(feedback.feedback_id)">Unhide comment</div>	
									</div>

								</div>
							</div>
							-->

							<div class='col-md-4'>
								<div class='row'>

									<div class='col-md-12'>
										Contribution rating
									</div>

									<!-- rating incorrect - correct -->
									<div class="row rating-correctness">
										<div class="col-md-6 rating-left">
											<div class='rating-btn-left' ng-click="rating_click(feedback['feedback_id'], 1, 1)">Incorrect<img class='rating-btn-img rating-btn-img-left' ng-src="{{rating_img[feedback['feedback_id']][1]}}"></div>

										</div>

										<div class="col-md-6 rating-right">
											<div class='rating-btn-right' ng-click="rating_click(feedback['feedback_id'], 1, 2)"> <img class='rating-btn-img rating-btn-img-right' ng-src="{{rating_img[feedback['feedback_id']][2]}}">Correct</div>
										</div>
									</div>

									<!-- rating known fact - new discovery -->
									<div class="row rating-discovery">
										<div class="col-md-6 rating-left">
											<div class='rating-btn-left' ng-click="rating_click(feedback['feedback_id'], 2, 1)">Known Fact<img class='rating-btn-img  rating-btn-img-left' src="{{rating_img[feedback['feedback_id']][3]}}"></div>

										</div>

										<div class="col-md-6 rating-right">
											<div class='rating-btn-right' ng-click="rating_click(feedback['feedback_id'], 2, 2)"> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img[feedback['feedback_id']][4]}}">New Discovery</div>
										</div>
									</div>

									<!-- rating unrelevant - relevant -->
									<div class="row">
										<div class="col-md-6 rating-left">
											<div class='rating-btn-left' ng-click="rating_click(feedback['feedback_id'], 3, 1)">Unrelevant<img class='rating-btn-img rating-btn-img-left' src="{{rating_img[feedback['feedback_id']][5]}}"></div>

										</div>

										<div class="col-md-6 rating-right">
											<div class='rating-btn-right' ng-click="rating_click(feedback['feedback_id'], 3, 2)"> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img[feedback['feedback_id']][6]}}">Revelant</div>
										</div>
									</div>

								</div>

							</div>


						</div>
					</div>

					<!-- replies -->

					<div class='collapse' id='{{feedback.feedback_id}}'>
						
						<div ng-repeat='rep in feedback.replies' ng-show='feedback.replies.length'>

						

							<div class='col-md-10 col-md-offset-1' ng-class="{'admin-feedback-reply':rep.hidden==0, 'admin-feedback-reply-hidden':rep.hidden==1||feedback.hidden==1}">

								<div class='row'>

									<div class='col-md-12 admin-message-head'>
										<div class='row'>

											<div class='col-md-10'>
												{{feedback["first_name"]}} {{feedback["last_name"]}}
												on
												{{feedback['time']}}
											</div>

											<div class='col-md-2 no-margin'>
												
												<div class='col-md-12 no-margin' ng-show="rep.hidden == 0">
													<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide-reply' ng-click="hideComment(rep.feedback_id)">Hide comment</div>	
												</div>

												<div class='col-md-12 no-margin' ng-show="rep.hidden == 1">
													<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide-reply' ng-click="hideComment(rep.feedback_id)">Unhide comment</div>	
												</div>

											</div>

										</div>
									</div>

									<div class='col-md-8 admin-feedback-vertical-ligne'>

										<div class='row'>

											<div class='col-md-12'>
												<div class='well well-sm well-message'>
													<strong>
														{{rep['message']}}
													</strong>
												</div>
											</div>

											<div class='col-md-12'>
												{{rep['upvote']}} upvotes
											</div>

											<!--

											<div class='col-md-4'>
												Filter
											</div>

											<div class='col-md-4'>
												Geological Age
											</div>


											<div class='col-md-4'>
											</div>

											<div class='col-md-4'>
												<div ng-show="rep['genus']==-1">
													Genus: All
												</div>
												<div ng-show="rep['genus']!=-1">
													Genus: {{rep['genus']}}
												</div>
											</div>




											

											<div class='col-md-4'>
											From: {{rep['age_min']}}
											</div>

											<div class='col-md-4'>
											</div>

											<div class='col-md-4'>
												<div ng-show="rep['genus']==-1">
													Collector: All
												</div>
												<div ng-show="rep['genus']!=-1">
													Collector: {{rep['collector']}}
												</div>
											</div>


											<div class='col-md-4'>
												To: {{rep['age_max']}}
											</div>
											
											

											
											<div class='col-md-4'>
												{{rep['upvote']}} upvotes
											</div>
											-->

										</div>
									</div>

									<!--

									<div class='col-md-2'>
										<div class='row'>

										
											<div class='col-md-12'>
												<div class='btn btn-custom-default btn-xs btn-admin btn-admin-map' ng-click='showMap(feedback.feedback_id, rep.feedback_id)'>See on the map</div>
											</div>
											

											<div class='col-md-12' ng-show="rep.hidden == 0">
												<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide-reply' ng-click="hideComment(rep.feedback_id)">Hide comment</div>	
											</div>

											<div class='col-md-12' ng-show="rep.hidden == 1">
												<div class='btn btn-custom-primary btn-xs btn-admin btn-admin-hide-reply' ng-click="hideComment(rep.feedback_id)">Unhide comment</div>	
											</div>

										</div>
									</div>

									-->

	


									<div class='col-md-4'>

										<div class='row'>

											<div class='col-md-12'>
												Contribution rating
											</div>

											<!-- rating incorrect - correct -->
											<div class="row rating-correctness">
												<div class="col-md-6 rating-left">
													<div class='rating-btn-left' ng-click="rating_click(rep['feedback_id'], 1, 1)">Incorrect<img class='rating-btn-img rating-btn-img-left' ng-src="{{rating_img[rep['feedback_id']][1]}}"></div>

												</div>

												<div class="col-md-6 rating-right">
													<div class='rating-btn-right' ng-click="rating_click(rep['feedback_id'], 1, 2)"> <img class='rating-btn-img rating-btn-img-right' ng-src="{{rating_img[rep['feedback_id']][2]}}">Correct</div>
												</div>
											</div>

											<!-- rating known fact - new discovery -->
											<div class="row rating-discovery">
												<div class="col-md-6 rating-left">
													<div class='rating-btn-left' ng-click="rating_click(rep['feedback_id'], 2, 1)">Known Fact<img class='rating-btn-img  rating-btn-img-left' src="{{rating_img[rep['feedback_id']][3]}}"></div>

												</div>

												<div class="col-md-6 rating-right">
													<div class='rating-btn-right' ng-click="rating_click(rep['feedback_id'], 2, 2)"> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img[rep['feedback_id']][4]}}">New Discovery</div>
												</div>
											</div>

											<!-- rating unrelevant - relevant -->
											<div class="row">
												<div class="col-md-6 rating-left">
													<div class='rating-btn-left' ng-click="rating_click(rep['feedback_id'], 3, 1)">Unrelevant<img class='rating-btn-img rating-btn-img-left' src="{{rating_img[rep['feedback_id']][5]}}"></div>

												</div>

												<div class="col-md-6 rating-right">
													<div class='rating-btn-right' ng-click="rating_click(rep['feedback_id'], 3, 2)"> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img[rep['feedback_id']][6]}}">Revelant</div>
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
		</div>
	</div>

</body>


























