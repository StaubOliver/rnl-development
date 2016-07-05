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
					<h4><?php echo count($feedbacks).' feedbacks recorded'; ?></h4>
				</div>
				

				<div ng-repeat='feedback in feedbacks' ng-show='feedbacks.length'>


					<div class='col-md-10 col-md-offset-1 admin-feedback'>
						<div class='row'>

							<div class='col-md-7 admin-feedback-vertical-ligne'>

								<div class='row '>
									
									<div class='col-md-12'>
										<strong>
											{{feedback['message']}}
										</strong>
									</div>
									
									<div class='col-md-4'>
										{{feedback["first_name"]}} {{feedback["last_name"]}}
									</div>
									

									<div class='col-md-4'>
										From: {{feedback['age_min']}}
									</div>

									<div class='col-md-4'>
										To: {{feedback['age_max']}}
									</div>

									<div class='col-md-4'>
										{{feedback['time']}}
									</div>
									
									<div class='col-md-4'>
										<div ng-show="feedback['genus']==-1">
											Genus: All
										</div>
										<div ng-show="feedback['genus']!=-1">
											Genus: {{feedback['genus']}}
										</div>
									</div>

									<div class='col-md-4'>
										<div ng-show="feedback['genus']==-1">
											Collector: All
										</div>
										<div ng-show="feedback['genus']!=-1">
											Collector: {{feedback['collector']}}
										</div>
									</div>


									<div class='col-md-12 admin-feedback-tool'>
										<div class='btn btn-custom-default btn-sm' ng-click='showMap(feedback.feedback_id, 0)'>See comment on the map</div>
										<div class='btn btn-custom-primary btn-sm'>Delete comment</div>	
										
									</div>

								</div>

							</div>

							<div class='col-md-5'>
								<div class='row'>
									<div class='col-md-12'>
										Contribution rating
									</div>
									<!--
									<div class='row'>
										<div class='col-md-4 admin-feedback-rating-left'>
											Incorrect
										</div>
										<div class='col-md-4 admin-feedback-rating-stars'>
											<?php for($i=1; $i<6; $i++): ?>
												<img 
													id=<?php echo "rating-{{feedback['feedback_id']}}-".$i; ?> 
													class="rating rating-star" 
													src="{{init_rating_img[feedback['feedback_id']][<?php echo $i ?>]}}" 
													ng-mouseover="rating_highlight(feedback['feedback_id'], <?php echo $i ?>)"
													ng-mouseleave="rating_unhighlight(feedback['feedback_id'], <?php echo $i ?>)"
													ng-click="rating_click(feedback['feedback_id'], <?php echo $i ?>)"">
											<?php endfor; ?>
										</div>
										<div class='col-md-4 admin-feedback-rating-right'>
											Correct
										</div>
									</div>

									<div class='row'>
										<div class='col-md-4 admin-feedback-rating-left'>
											Known fact
										</div>
										<div class='col-md-4 admin-feedback-rating-stars'>
											<?php for($i=1; $i<6; $i++): ?>
												<img 
													id=<?php echo "rating-{{feedback['feedback_id']}}-".$i; ?> 
													class="rating rating-star" 
													src="{{init_rating_img[feedback['feedback_id']][<?php echo $i ?>]}}" 
													ng-mouseover="rating_highlight(feedback['feedback_id'], <?php echo $i ?>)"
													ng-mouseleave="rating_unhighlight(feedback['feedback_id'], <?php echo $i ?>)"
													ng-click="rating_click(feedback['feedback_id'], <?php echo $i ?>)"">
											<?php endfor; ?>
										</div>
										<div class='col-md-4 admin-feedback-rating-right'>
											New discovery 
										</div>

									</div> -->

									<!-- rating incorrect - correct -->
									<div class="row rating-correctness">
										<div class="col-md-6 rating-left">
											<div class='btn btn-custom-default btn-xs rating-btn-left' ng-click="rating_click(feedback['feedback_id'], 1, 1)">Incorrect<img class='rating-btn-img rating-btn-img-left' src="{{rating_img['feedback_id'][1]"></div>

										</div>

										<div class="col-md-6 rating-right">
											<div class='btn btn-custom-default btn-xs margin-btn-right' ng-click="rating_click(feedback['feedback_id'], 1, 2)"> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img['feedback_id'][2]">Correct</div>
										</div>
									</div>

									<!-- rating known fact - new discovery -->
									<div class="row rating-discovery">
										<div class="col-md-6 rating-left">
											<div class='btn btn-custom-default btn-xs rating-btn-left'>Known Fact<img class='rating-btn-img  rating-btn-img-left' src="{{rating_img['feedback_id'][3]"></div>

										</div>

										<div class="col-md-6 rating-right">
											<div class='btn btn-custom-default btn-xs margin-btn-right'> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img['feedback_id'][4]">New Discovery</div>
										</div>
									</div>

									<!-- rating unrelevant - relevant -->
									<div class="row">
										<div class="col-md-6 rating-left">
											<div class='btn btn-custom-default btn-xs rating-btn-left'>Unrelevant<img class='rating-btn-img rating-btn-img-left' src="{{rating_img['feedback_id'][5]"></div>

										</div>

										<div class="col-md-6 rating-right">
											<div class='btn btn-custom-default btn-xs margin-btn-right'> <img class='rating-btn-img rating-btn-img-right' src="{{rating_img['feedback_id'][6]">Revelant</div>
										</div>
									</div>

								</div>

							</div>
						</div>
					</div>

					<!-- replies -->
					<div ng-repeat='rep in feedback.replies' ng-show='feedback.replies.length'>

						<div class='col-md-7 col-md-offset-1'>
							<div class='admin-feedback-reply'>

								<div class='row'>
										
									<div class='col-md-6'>
										Feedback from {{rep["first_name"]}} {{rep["last_name"]}}
									</div>

									<div class='col-md-6'>
										{{rep["upvote"]}} upvotes
									</div>
									
									<div class='col-md-12'>
										<strong>
											{{rep['message']}}
										</strong>
									</div>
									
									<div class='col-md-6'>
										{{rep['time']}}
									</div>
									
									<div class='col-md-6'>
										{{rep['selection'].length}} fossils selected
									</div>

									<div class='col-md-6'>
										From: {{rep['age_min']}}
									</div>

									<div class='col-md-6'>
										To: {{rep['age_max']}}
									</div>

									<div class='col-md-6'>
										<div ng-show="rep['genus']==-1">
											Genus: All
										</div>
										<div ng-show="rep['genus']!=-1">
											Genus: {{rep['genus']}}
										</div>
									</div>

									<div class='col-md-6'>
										<div ng-show="rep['genus']==-1">
											Collector: All
										</div>
										<div ng-show="rep['genus']!=-1">
											Collector: {{rep['collector']}}
										</div>
									</div>

									<div class='col-md-12 admin-feedback-tool'>
										<div class='btn btn-custom-default btn-sm'>See comment on the map</div>
										<div class='btn btn-custom-primary btn-sm'>Delete comment</div>	
										<?php for($i=1; $i<6; $i++): ?>
											<img 
												id=<?php echo "rating-{{rep['feedback_id']}}-".$i; ?> 
												class="rating rating-star" 
												src="{{init_rating_img[rep['feedback_id']][<?php echo $i ?>]}}" 
												ng-mouseover="rating_highlight(rep['feedback_id'], <?php echo $i ?>)"
												ng-mouseleave="rating_unhighlight(rep['feedback_id'], <?php echo $i ?>)"
												ng-click="rating_click(rep['feedback_id'], <?php echo $i ?>)"">
										<?php endfor; ?>
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


























