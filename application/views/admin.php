<!DOCTYPE html>
<html lang="en" ng-app="app" ng-cloak>
<head>
	<!-- Metadata -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin | Reading Nature's Library</title>
	
	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- Icons -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	
	<!-- CSS -->
	<link rel="stylesheet" href="/assets/css/admin.css">
	
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="/assets/js/admin.js"></script>	
	
	<!-- Analytics -->
	<?php echo $analytics; ?>
</head>
<body>
	<div id="admin-projects-list" ng-controller="projectListing">
		<h1>Projects list</h1>
		<?php $i = 0; ?>
		<?php foreach($projects as $project): ?>
		
			<div id="project-list-<?php echo $project['id']; ?>" class="project-wrapper">
				<div class="project-image-wrapper">
					<img src="/assets/img/<?php echo $project['image']; ?>">
				</div>
				
				<div class="project-info-wrapper">
					<h3><?php echo $project['name']; ?></h3>

					<p class="project-data-totals">
						<span><?php echo $project['total_images']; ?> total images</span> |
						<span><?php echo $project['total_complete']; ?> total complete</span> |
						<span><?php echo $project['total_reviewed']; ?> total reviewed</span>
					</p>
					
					<p>
						<?php echo $project['blurb']; ?>
					</p>
					
					<a class="pink-button" href="/admin/exportdata/<?php echo $project['id']; ?>">Export data</a>
					
					<a class="pink-light-button" href="/admin/completedata/<?php echo $project['id']; ?>">Check and close entries</a>
				</div>
				
				<div class="clear"></div>
			</div>
		<?php $i++; ?>
		<?php endforeach; ?>
	</div>
</body>
</html>