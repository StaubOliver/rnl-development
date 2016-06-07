<!DOCTYPE html>
<html lang="en" ng-app="app" ng-cloak>
<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reading Nature's Library</title>
	
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
	<script src="/assets/js/share.js"></script>
	<script src="/assets/js/profile.js"></script>
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
</head>
<body>

	<!-- Header -->
	<header class="container">
		<div class="three columns">
			<div id="branding">Reading Nature's Library</div>
		</div>
		
		<div class="three columns">
			<div id="profile-tab" class="header-item">
				<div id="profile-tab-name" ng-click="profile.summary.show = true" off-click="profile.summary.show = false">
					Hello, {{ profile.first_name }} <i class="fa fa-user-plus"></i>
				</div>
				<div id="profile-tab-summary" ng-show="profile.summary.show">
					<div id="profile-tab-badges">
						View and edit your profile:
					</div>
					<p class="show-profile" ng-click="profile.show = true">View / edit profile</p>
				</div>
			</div>
		</div>
		
		<div class="three columns">
			<div id="share-tab" class="header-item share-image" ng-click="showSharePanel(0, 0)">
				Share an image <i class="fa fa-bullhorn"></i>
			</div>
		</div>
		
		<div class="three columns">
			<div id="nav-tab" class="header-item">
				<nav>
					<a href="/app/logout">Sign out</a> <i class="fa fa-sign-out"></i>
				</nav>
			</div>
		</div>
	</header>
	
	<!-- User profile -->
	<div id="profile" class="container" ng-show="profile.show">
		<h3>{{ profile.first_name }} {{ profile.last_name }}'s profile</h3>
		<div id="profile-listing" ng-controller="profileListing" data-profile-id=" {{ profile.id }}">
			<div id="profile-details" class="four columns">
				<h4>Your details</h4>
				<div id="profile-name"><i class="fa fa-user"></i> {{ profile.first_name }} {{ profile.last_name }}</div>
				<div id="profile-email"><i class="fa fa-envelope-o"></i> {{ profile.email }}</div>
				<div id="profile-location"><i class="fa fa-map-marker"></i> {{ profile.location }}</div>
				<!--<div id="profile-username"><i class="fa fa-key"></i> {{ profile.username }}</div>-->
				<div id="profile-last-login">You last logged in <span am-time-ago="profile.last_login" am-preprocess="unix"></span></div>
			</div>
			
			<div id="profile-update" class="four columns">
				<h4>Update your details</h4>
				<p><input type="text" placeholder="{{ profile.first_name }}" ng-model="profile.first_name"></p>
				<p><input type="text" placeholder="{{ profile.last_name }}" ng-model="profile.last_name"></p>
				<p><input type="email" placeholder="{{ profile.email }}" ng-model="profile.email"></p>
				<p>Your current location is set as {{ profile.location }}. We set your location from your IP address.</p>
				<p><input type="submit" value="Save changes" class="pink-button" ng-click="updateProfile()"></p>
			</div>
			
			<div id="profile-achievements" class="four columns">
				<h4>Your RNL achievements</h4>
				<div id="profile-achievements-list">
					<div id="profile-totals">
						<span id="profile-queue-total"><span>{{ profile.queue_total }}</span> completed</span>
						<span id="profile-review-total"><span>{{ profile.review_total }}</span> reviewed</span>
						<span id="profile-share-total"><span>{{ profile.share_total }}</span> shared</span>
					</div>
					
					<div id="profile-badges">
						<span class="profile-badge" id="profile-badge-sign-up" ng-show="profile.badge.signup"><i class="fa fa-flask"></i>
 Citizen Scientist</span>
						<span class="profile-badge" id="profile-badge-image-1" ng-show="profile.badge.image1"><i class="fa fa-certificate"></i>
 1st image</span>
						<span class="profile-badge" id="profile-badge-image-10" ng-show="profile.badge.image10"><i class="fa fa-certificate"></i>
 10 images</span>
						<span class="profile-badge" id="profile-badge-image-25" ng-show="profile.badge.image25"><i class="fa fa-certificate"></i>
 25 images</span>
						<span class="profile-badge" id="profile-badge-review-1" ng-show="profile.badge.review1"><i class="fa fa-asterisk"></i>
 1st review</span>
						<span class="profile-badge" id="profile-badge-review-10" ng-show="profile.badge.review10"><i class="fa fa-asterisk"></i>
 10 reviews</span>
						<span class="profile-badge" id="profile-badge-review-25" ng-show="profile.badge.review25"><i class="fa fa-asterisk"></i>
 25 reviews</span>
 						<span class="profile-badge" id="profile-badge-share-1" ng-show="profile.badge.share1"><i class="fa fa-bullhorn"></i>
1st share</span>
						<span class="profile-badge" id="profile-badge-share-10" ng-show="profile.badge.share10"><i class="fa fa-bullhorn"></i>
10 shares</span>
						<span class="profile-badge" id="profile-badge-share-25" ng-show="profile.badge.share25"><i class="fa fa-bullhorn"></i>
25 shares</span>
					</div>
				</div>
			</div>
		</div>
		
		<div id="profile-close" ng-click="profile.show = false">
			<div><i class="fa fa-arrow-up"></i> Close profile <i class="fa fa-arrow-up"></i></div>
			<div><i class="fa fa-times-circle"></i></div>
		</div>
	</div>

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
			<div id="phone-warning">
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
						We'll keep you posted when we add more projects
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
				<div id="project-last-completed" ng-show="share.lastimage">
					<a href="http://www.twitter.com/share?text=I’ve just recorded this lovely object for @McrMuseum @TheStudyMcr {{ share.lastimageurl }} &hashtags=ReadingNaturesLibrary" target="_blank">
						Great work, you can share the last image you completed on Twitter here.
					</a>
				</div>
				
				<div id="project-data-entry-header">
					<h3>Entry for {{ filename }} <span class="share-image-link share-image" ng-click="share.show=true;share.hash=hash;share.image=filename">Need help? <i class="fa fa-bullhorn"></i></span></h3>
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
				
				<div id="close-project-line">
					<span class="close-project" ng-click="closeCurrentProject()">return to project list</span>
				</div>
			</div>
		</div>

		<!-- Leaderboard -->
		<div id="leaderboard" class="three columns sidebar">
			<h3>Leaderboard <i class="fa fa-trophy"></i></h3>
			<div id="leaderboard-listing" ng-controller="leaderBoardListing">
				<ul>
					<li ng-repeat="place in leaderboard track by place.id | orderBy:'-id' | limitTo:'9'" class="leaderboard-item">
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
		<?php if($is_admin == 1): ?>
		 | <a href="/admin">Admin area</a>
		<?php endif; ?>
	</footer>

	<!-- Share panel -->
	<div id="share-panel"  ng-controller="shareImage" ng-show="share.show">
		<span id="share-close" ng-click="share.show = false">
			<i class="fa fa-times-circle"></i>
		</span>
		
		<h3>Share an image on your networks <i class="fa fa-bullhorn"></i></h3>
		
		<p>You have chosen to share {{ share.image }}.</p>
		
		<div id="share-social-buttons">
			<a ng-click="addPoints(<?php echo $user_id; ?>)" href="http://www.twitter.com/share?text=I can't work out what this label says, can you help? @McrMuseum @TheStudyMcr&hashtags=ReadingNaturesLibrary&url=https://{{ share.url }}{{ share.hash }}" target="_blank" class="share-social-button">
				<i class="fa fa-twitter-square"></i>
			</a>
			
			<a ng-click="addPoints(<?php echo $user_id; ?>)" href="https://www.facebook.com/sharer/sharer.php?u={{ share.url }}{{ share.hash }}" target="_blank" class="share-social-button">
				<i class="fa fa-facebook-square"></i>
			</a>
			
			<a ng-click="addPoints(<?php echo $user_id; ?>)" href="https://www.reddit.com/submit?url={{ share.url }}{{ share.hash }}" target="_blank" class="share-social-button">
				<i class="fa fa-reddit-square"></i>
			</a>
			
			<a ng-click="addPoints(<?php echo $user_id; ?>)" href="https://plus.google.com/share?url={{ share.url }}{{ share.hash }}" target="_blank" class="share-social-button">
				<i class="fa fa-google-plus-square"></i>
			</a>
		</div>
		
		<div id="share-url-address">
			<p>Copy this link to share anywhere:</p>
			<input type="text" value="https://{{ share.url }}{{ share.hash }}" ng-click="onTextClick($event)" readonly>
		</div>
	</div>
	
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
			<p>Once you're happy, click the pink <strong>submit entry</strong> button to save the data, and load the next image.</p>
			<p>If you get stuck try sharing the image with your friends using the <strong>Need help?</strong> button.</p>
			<p>Click the <i class="fa fa-info-circle"></i> icon at the top right of the image to see this information again.</p> 
		</div>
	</div>
</body>
</html>