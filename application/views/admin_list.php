<!DOCTYPE html>
<html lang="en">
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
	<script src="/assets/js/admin.list.js"></script>	
	
	<!-- Analytics -->
	<?php echo $analytics; ?>
</head>
<body>
	<div id="admin-projects-list">
		<h1>Project entries</h1>
		
		<p><i>Mark an image as complete to remove it from public editing.</i></p>
		
		<?php foreach($entries as $entry): ?>
			<?php //echo print_r($entry); ?>
			<div class="admin-projects-list-item">
				<img class="admin-projects-list-item-img" src="<?php echo $entry->image_url; ?>">
				
				<div class="admin-projects-list-item-data">
					<h3><?php echo $entry->image_id; ?>. <?php echo $entry->filename; ?></h3>
					
					<table cellspacing=0>
						<thead>
							<th>Field</th>
							<th>Entry</th>
						</thead>
						<tbody>						
							<tr>
								<td>Genus</td> 
								<td><?php echo $entry->genus; ?></td>
							</tr>
							<tr>
								<td>Genus custom</td>
								<td><?php echo $entry->genuscustom; ?></td>
							</tr>
							<tr>
								<td>Species</td>
								<td><?php echo $entry->species; ?></td>
							</tr>
							<tr>
								<td>Age</td>
								<td><?php echo $entry->age; ?></td>
							</tr>
							<tr>
								<td>Country</td>
								<td><?php echo $entry->country; ?></td>
							</tr>
							<tr>
								<td>Place</td>
								<td><?php echo $entry->place; ?></td>
							</tr>
							<tr>
								<td>Collector</td>
								<td><?php echo $entry->collector; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="clear"></div>
				
				<?php if(!$entry->complete): ?>
					<div class="pink-button" data-project-id="<?php echo $project_id; ?>" data-image-id="<?php echo $entry->image_id; ?>">
						Mark as complete
					</div>
				<?php else: ?>
					<div class="admin-projects-list-item-complete">Image removed from the project queue.</div>				
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</body>
</html>
