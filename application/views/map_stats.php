<!DOCTYPE html>
<html lang="en" ng-app='map_stats'>

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
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIwlL-wm53SyhrnSAqCRL9SDGwhTVsq7c"></script>
	

</head>

<body class='admin-body' ng-controller='admin_map_stats'>

	<nav class="navbar-default  navbar-fixed-top" >
		<div class="container-fluid">

			<div class="navbar-header">
				<a class="navbar-brand" href="#">Map</a>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><p class="navbar-text">Admin Stats</p></li>
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
					<h5>Unique visits - <?php echo $stats['uniqueVisits'] ?> </h5>
				</div>

				<div class='col-md-10 col-md-offset-1 admin-title'>
					<h5>Saved actions - <?php echo $stats['nbActions'] ?> </h5>
				</div>


				<div class='col-md-10 col-md-offset-1 admin-title'>
					<div class='row'>

						<?php foreach($stats["visits"] as $visits): ?>
							<div class='col-md-6'>
								<p ng-click="loadVisitdetails('<?php echo $visits["unique_id"] ?>')"><?php echo $visits["unique_id"] ?></p>
							</div>
							
						<?php endforeach; ?>

					</div>
				</div>


				<div class='col-md-10 col-md-offset-1 admin-title' ng-show='details.length > 0'>
					<div class='row'>
						
						<div class='col-md-12'>
							<h5>{{details[0][unique_id]}} -  {{details.length}} actions</h5>
						</div>

						<div ng-repeat='detail in details'>

							<div class='col-md-4'>{{detail.time}}</div> 
							<div class='col-md-4'>{{detail.action}}</div>
							<div class='col-md-4'>{{detail.details}}</div>



						</div>





					</div>
				</div>



			</div>
		</div>


	</div>

</body>
</html>









































