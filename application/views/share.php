<!DOCTYPE html>
<html lang="en" ng-app="app" ng-cloak>
<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reading Nature's Library</title>
	
	<meta property="og:title" content="Help catalogue Manchester Museum's fossil collection" />
	<meta property="og:site_name" content="Reading Nature's Library"/>
	<meta property="og:url" content="https://msc.refractive.co/share/index/0" />
	<meta property="og:description" content="Reading Nature's Library is a citizen science application that allows the public to assist the Museum in the cataloguing of an extensive fossil collection." />
	<meta property="og:image" content="http://res.cloudinary.com/djoh3yncv/image/upload/c_scale,w_1400/v1434451726/LL349a-d.JPG.jpg">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="@McrMuseum">
	<meta name="twitter:creator" content="@McrMuseum">
	<meta name="twitter:title" content="Help catalogue Manchester Museum's fossil collection">
	<meta name="twitter:description" content="Reading Nature's Library is a citizen science application that allows the public to assist the Museum in the cataloguing of an extensive fossil collection.">
	<meta name="twitter:image" content="http://res.cloudinary.com/djoh3yncv/image/upload/c_scale,w_1000/v1434451726/LL349a-d.JPG.jpg">

	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- Icons -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	
	<!-- CSS -->
	<link rel="stylesheet" href="/assets/css/normalize.css">
	<link rel="stylesheet" href="/assets/css/skeleton.css">
	<link rel="stylesheet" href="/assets/css/skeleton-override.css">
	<link rel="stylesheet" href="/assets/css/style.css">
	<link rel="stylesheet" href="/assets/css/responsive.css">
	
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
	<script src="/assets/js/plugin/transit.js"></script>
	<script src="/assets/js/elevatezoom.js"></script>
	<script src="/assets/js/moment.js"></script>
	<script src="/assets/js/angular.moment.js"></script>
	<script src="/assets/js/angular.offclick.js"></script>
	<script src="/assets/js/app.js"></script>
	<script src="/assets/js/share-anon.js"></script>
	<script src="/assets/js/leaderboard.js"></script>
	<script src="/assets/js/activity.js"></script>
	<script src="/assets/js/project.js"></script>
	<script src="/assets/js/info.js"></script>
	
	<!-- Analytics -->
	<?php echo $analytics; ?>
	
	<!-- Old browser warning: https://msdn.microsoft.com/en-us/library/hh801214%28v=vs.85%29.aspx -->
	<!--[if IE]>
    	<script type="text/javascript">
    		$('body').prepend('<div id="browser-warning">This application is made using technology that may not be supported by your browser.<br>Please switch browser or upgrade: <a href="http://outdatedbrowser.com">outdatedbrowser.com</a></div>');
    	</script>
  	<![endif]-->
  	
  	<!-- Cookie and terms consent -->
  	<script>
  		$(function() {
  			if(!localStorage.getItem('rnl-consent')) {
				$('body').prepend('<div id="tracking-warning">By using this application you agree to our terms detailed here: <a href="https://natureslibrary.co.uk/terms">natureslibrary.co.uk/terms</a> <span id="close">[X] Close</span></div>');
		
				$('#tracking-warning #close').click(function() {
					$('#tracking-warning').remove();
				});
				
				localStorage.setItem('rnl-consent', 1);
  			}
  		});
  	</script>
</head>
<body ng-controller="shareImage">
	<!-- Load image hash -->
  	<script>
  		// Get image by hash value
		var loadImageID = "<?php echo $hashid; ?>" || 0;
  	</script>

	<!-- Header -->
	<header class="container">
		<div class="five columns">
			<div id="branding">Reading Nature's Library</div>
		</div>
		
		<div class="seven columns">
			<div id="signup-tab" class="header-item">
				<div id="profile-tab-name">
					Hello there, you can <a href="/auth/create_user">register here to save your points</a>. <i class="fa fa-user-plus"></i>
				</div>
			</div>
		</div>
	</header>
	
	<!-- User signup -->
	<div id="signup" class="container" ng-show="signup.show"></div>

	<main class="container">		
		<!-- Project -->
		<div id="project" class="nine columns" ng-controller="projectSelection">
			<div id="project-header">
				<h2>{{ title }}</h2>
				
				<span id="info-link" ng-click="info.panel = true">
					<i class="fa fa-info-circle"></i>
				</span>
				
				<span id="project-progress">
					You have helped catalogue <span id="project-record-number">{{ progress }}</span> records this session.
				</span>
			</div>
			
			<!-- Phone warning for small screens -->
			<div id="phone-warning" class="three columns">
				<h2>This application isn't designed for small screens such as phones and tablets just yet.</h2>
				<p>We're currently working on providing this feature. Until then please use a desktop or larger screen device.</p>
				<p>Please feedback any suggestions to <a href="mailto:support@natureslibrary.co.uk">support@natureslibrary.co.uk</a></p>
			</div>
			
			<div id="project-error" ng-click="closeErrorMessage()">
				<h3>{{ error }}</h3>
			</div>
			
			<div id="project-selection">
				<div id="project-queue-end" ng-show="queue.end" ng-click="queue.end = false">
					<i class="fa fa-check-circle"></i>
					
					<h3>Wow, great work!</h3>
					
					<h4>It looks like you've reached the end of the new images for this project</h4>
					
					<p>You can review and edit completed entries, to help us get better results.</p>
					<p>Having people check each other's entries means we get more accurate data for this collection.</p>
				</div>
				
				<div id="project-review-end" ng-show="review.end" ng-click="review.end = false">
					<i class="fa fa-check-circle"></i>
					
					<h3>Wow, great work!</h3>
					
					<h4>It looks like you've reached the end of the review images for this project</h4>
					
					<p>Check back later for more images.</p>
				</div>
			
			<!-- Populate start -->
			<?php foreach($projects->result() as $project): ?>
				<div id="manchester-fossils-<?php echo $project->id; ?>" class="project-wrapper">
				
					<div class="project-image-wrapper">
						<img src="/assets/img/<?php echo $project->image; ?>">
					</div>
					
					<div class="project-info-wrapper">
						<h3><?php echo $project->name; ?></h3>

						<p>
							<?php echo $project->blurb; ?>
						</p>
					
						<span class="join-project-button pink-button" ng-click="loadProject(<?php echo $project->id; ?>)">Complete entries for this project</span>
						<span class="review-project-button grey-button" ng-click="loadReviews(<?php echo $project->id; ?>)">Review completed entries</span>
					</div>
				</div>
			<?php endforeach; ?>
			<!-- Populate end -->	
				<div id="project-more-soon">
					<p>	
						We'll be adding more projects soon. <a href="/auth/create_user">Sign up to be notified.</a>
					</p>
				</div>
			</div>
			
			<div id="project-image">
				<img ng-src="{{ imageurl }}" alt="Project image">
				
				<div id="project-image-loader">
					<img src="/assets/img/loader.svg" alt="Loading next image...">
					<p>Loading next project image...</p>
				</div>
			</div>
			
			<div id="project-data-entry">
				<div id="project-data-entry-header">
					<h3>Entry for {{ filename }}</h3>
				</div>
				
				<div id="project-data-entry-forms">
					<!-- Forms for the projects -->
					<div ng-show="showQueueForm">
						<div ng-include="project.queueform"></div>
					</div>
					
					<div ng-show="showReviewForm">
						<div ng-include="project.reviewform"></div>
					</div>		
				</div>
			</div>
		</div>

		<!-- Leaderboard -->
		<div id="leaderboard" class="three columns sidebar">
			<h3>Leaderboard <i class="fa fa-trophy"></i></h3>
			<div id="leaderboard-listing" ng-controller="leaderBoardListing">
				<ul>
					<li ng-repeat="place in leaderboard track by place.id" class="leaderboard-item">
						<div class="leaderboard-position">{{ place.id }}.</div>
						<div class="leaderboard-name">{{ place.name }}</div>
						<div class="leaderboard-points">{{ place.points }}</div>
						<div class="leaderboard-location">
							 <img class="greyscale" ng-src="/assets/img/flags/{{ place.location }}.png" alt="{{ place.location }}">
						</div>
					</li>
				</ul>
			</div>
		</div>

		<!-- Activity -->
		<div id="activity" class="three columns sidebar">
			<h3>Activity <i class="fa fa-cogs"></i></h3>
			<div id="activity-listing" ng-controller="activityListing">
				<ul>
					<li ng-repeat="task in activity track by task.id | orderBy:'task.id':true" class="activity-item" ng-class="{firstItem: $first}">
						<div class="activity-person">{{ task.name }}</div>
						<div class="activity-type">{{ task.activity }}</div>
						<div class="activity-time" am-time-ago="task.time"></div>
						<!--
						<div class="activity-location">
							 <img class="greyscale" ng-src="/assets/img/flags/{{ task.location }}.png" alt="{{ task.location }}">
						</div>
						-->
					</li>
				</ul>
			</div>
		</div>
	</main>
	
	<!-- Footer -->
	<footer>
		<a href="http://www.museum.manchester.ac.uk/" target="_blank">Manchester Museum</a> | 
		<a href="/terms">Terms &amp; conditions</a>
	</footer>
	
	<!-- Information panel -->
	<div ng-controller="informationPanel" ng-show="info.panel" id="info-panel">
		<span id="info-close" ng-click="closeInformationPanel()">
			<i class="fa fa-times-circle"></i>
		</span>
		
		<h2>How to catalogue a fossil image</h2>
		
		<div id="info-image">
			<img src="/assets/img/example.jpg" alt="Example fossil image">
		</div>
		
		<div id="info-text">
			<p>Fill in the form from the information on the label in the image.</p>
			<p>Some of the information may be hard to read or missing. Just complete what you can see.</p>
			<p>Once you're happy click the pink <strong>submit entry</strong> button to save the data, and load the next image.</p>
			<p>If you get stuck try sharing the image with your friends using the <strong>Need help?</strong> button.</p>
			<p>Click the <i class="fa fa-info-circle"></i> icon at the top right of the image to see this information again.</p> 
		</div>
	</div>
</body>
</html>