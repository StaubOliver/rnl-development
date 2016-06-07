<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reading Nature's Library</title>
	<meta name="author" content="Rob Dunne" />
	<meta name="description" content="A citizen science application in collaboration with Manchester Museum" />
	<meta name="keywords"  content="citizen science, fossils" />
	<meta name="Resource-type" content="Document" />

	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- Icons -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/plugin/jquery.fullPage.css" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/home.css" />

	<!--[if IE]>
		<script type="text/javascript">
			 var console = { log: function() {} };
		</script>
	<![endif]-->

	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
	<script src="/assets/js/plugin/jquery.slimscroll.min.js"></script>
	<script src="/assets/js/plugin/jquery.fullPage.min.js"></script>
	<!--<script src="/assets/js/angular.moment.js"></script>-->
	<script src="/assets/js/home-activity.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#fullpage').fullpage({
				sectionsColor: ['#f9f9f9', '#f9f9f9', '#f9f9f9'],
				anchors: ['home', 'about', 'terms'],
				menu: '#menu',
				css3: true,
				scrollingSpeed: 1000
			});
		});
	</script>
	
	<!-- Analytics -->
	<?php echo $analytics; ?>
</head>
<body ng-app="app">

<div id="header">
	<div id="uom-logo">
		<a href="http://www.museum.manchester.ac.uk/">
			<img src="/assets/img/uom-logo.png" alt="Manchester Museum logo" />
		</a>
	</div>
	
	<ul id="menu">
		<li data-menuanchor="home" class="active"><a href="#home">Home</a></li>
		<li data-menuanchor="about"><a href="#about">About</a></li>
		<li data-menuanchor="terms"><a href="#terms">Terms</a></li>
	</ul>
</div>

<div id="fullpage">
	<div class="section " id="section0">
		<div class="intro">
			<h1>Reading Nature's Library</h1>
			
			<h2>Citizen science projects at Manchester Museum</h2>
			
			<div id="button-wrapper">
				<a class="pink-button" href="/auth/create_user">
					Sign up to take part, it's free!
				</a>
				
				<div class="or">
					<span>or</span>
				</div>
				
				<a class="grey-button" href="/auth/login">
					Login to your account
				</a>
			</div>
			
			<div id="activity-feed" ng-controller="activityFeed">
				<i class="fa fa-users"></i>
				<strong>Latest activity</strong> {{ update.name }} {{ update.activity }} <span am-time-ago="update.time">just now.</span>
			</div>
		</div>
	</div>
	
	<div class="section" id="section1">
	    <div class="content">
	    	<i class="fa fa-flask"></i>

			<h1>About</h1>
			<p>Reading Nature's Library is a citizen science project where anyone can help the Museum catalogue the collection.</p>
			<p>The collection is broken down into projects, such as fossil corals. New projects will be added over time as we photograph the collection.</p>
			<p>It's free to join and take part, and you'll be helping make the amazing collection available to everyone!</p>
			<p>Once you've <a href="/auth/create_user">signed up</a> you can share your favourite images with friends and family, or see if they can help with any hard to read labels.</p>

		</div>
	</div>
	
	<div class="section" id="section5">
		<div class="content">
			<i class="fa fa-pencil-square-o"></i>

			<h1>Terms</h1>

			<h2>Cookies and privacy</h2>
			<p>This website and web application uses tracking cookies from Google Analytics along with session cookies for user authentication. Details including IP address, user-agent and user activity are logged on the server to improve the service and for research activities. By using this website and application you consent to the use of tracking and data logging.</p>			
			
			<p>Full terms and conditions of use can be found at: <a href="/terms">https://natureslibrary.co.uk/terms</a></p>
		</div>
	</div>
</div>

</body>
</html>