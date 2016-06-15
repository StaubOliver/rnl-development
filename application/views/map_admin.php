<!DOCTYPE html>
<html lang="en">

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

</head>

<body class='admin-body'>

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

				<div class='col-md-8 col-md-offset-2'>
					<div class='admin-title'>
						<h4><?php echo count($feedbacks).' feedbacks recorded'; ?></h4>
					</div>
				</div>

				<?php foreach($feedbacks as $feedback): ?>
					<div class='col-md-8 col-md-offset-2'>
						<div class='admin-feedback'>

							<div class='row'>
									
								<div class='col-md-6'>
									<?php echo 'Feedback from '.$feedback['first_name'].' '.$feedback['last_name']; ?>
								</div>

								<div class='col-md-6'>
									<?php echo $feedback['upvote']." upvote"; ?>
								</div>
								
								<div class='col-md-12'>
									<strong>
										<?php echo $feedback['message']; ?>
									</strong>
								</div>
								
								<div class='col-md-6'>
									<?php echo $feedback['time']; ?>
								</div>
								
								<div class='col-md-6'>
									<?php echo count($feedback['selection'])." fossils selected"; ?>
								</div>
								<div class='col-md-6'>
									<?php 
										if ($feedback['age_min']=="0") echo "From: <strong>Quaternary</strong>"; 
										if ($feedback['age_min']=="1") echo "From: <strong>Neogene</strong>"; 
										if ($feedback['age_min']=="2") echo "From: <strong>Paleogene</strong>"; 
										if ($feedback['age_min']=="3") echo "From: <strong>Cretaceous</strong>"; 
										if ($feedback['age_min']=="4") echo "From: <strong>Jurassic</strong>"; 
										if ($feedback['age_min']=="5") echo "From: <strong>Triassic</strong>"; 
										if ($feedback['age_min']=="6") echo "From: <strong>Permian</strong>"; 
										if ($feedback['age_min']=="7") echo "From: <strong>Carboniferous</strong>"; 
										if ($feedback['age_min']=="8") echo "From: <strong>Devonian</strong>"; 
										if ($feedback['age_min']=="9") echo "From: <strong>Silurian</strong>"; 
										if ($feedback['age_min']=="10") echo "From: <strong>Ordovician</strong>"; 
										if ($feedback['age_min']=="11") echo "From: <strong>Cambrian</strong>"; 
										if ($feedback['age_min']=="12") echo "From: <strong>Precambrian</strong>"; 
									?>
								</div>
								<div class='col-md-6'>
									<?php 
										if ($feedback['age_max']=="0") echo "To: <strong>Quaternary</strong>"; 
										if ($feedback['age_max']=="1") echo "To: <strong>Neogene</strong>"; 
										if ($feedback['age_max']=="2") echo "To: <strong>Paleogene</strong>"; 
										if ($feedback['age_max']=="3") echo "To: <strong>Cretaceous</strong>"; 
										if ($feedback['age_max']=="4") echo "To: <strong>Jurassic</strong>"; 
										if ($feedback['age_max']=="5") echo "To: <strong>Triassic</strong>"; 
										if ($feedback['age_max']=="6") echo "To: <strong>Permian</strong>"; 
										if ($feedback['age_max']=="7") echo "To: <strong>Carboniferous</strong>"; 
										if ($feedback['age_max']=="8") echo "To: <strong>Devonian</strong>"; 
										if ($feedback['age_max']=="9") echo "To: <strong>Silurian</strong>"; 
										if ($feedback['age_max']=="10") echo "To: <strong>Ordovician</strong>"; 
										if ($feedback['age_max']=="11") echo "To: <strong>Cambrian</strong>"; 
										if ($feedback['age_max']=="12") echo "To: <strong>Precambrian</strong>"; 
									?>
								</div>
								
								<div class='col-md-6'>
									<?php if($feedback['genus']=="-1"){
										echo("Genus: ALL");
									} else {
										echo "Genus: ".$feedback['genus']; 
									}?>
								</div>
								<div class='col-md-6'>
									<?php if($feedback['collector']=="-1"){
										echo("Collector: ALL");
									} else {
										echo "Collector: ".$feedback['collector']; 
									}?>
								</div>
								<div class='col-md-6'>
									<div class='btn btn-custom-default btn-sm'>See comment on the map</div><div class='btn btn-custom-primary btn-sm'>Delete comment</div>	
								</div>
								<div class='col-md-6'>
									<img class="rating rating-star rating-empty"><img class="rating rating-star rating-empty"><img class="rating rating-star rating-empty"><img class="rating rating-star rating-empty"><img class="rating rating-star rating-empty">
									<div>/assets/img/star/star_empty.png</div>
								</div>

							</div>

						</div>
					</div>
				<?php endforeach; ?>


			</div>
		</div>
	</div>

</body>


























