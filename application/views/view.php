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
	<meta property="og:image" content="<?php echo $image; ?>">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="@McrMuseum">
	<meta name="twitter:creator" content="@McrMuseum">
	<meta name="twitter:title" content="Help catalogue Manchester Museum's fossil collection">
	<meta name="twitter:description" content="Reading Nature's Library is a citizen science application that allows the public to assist the Museum in the cataloguing of an extensive fossil collection.">
	<meta name="twitter:image" content="<?php echo $image; ?>">

	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- CSS -->
	<style type="text/css">
		* {
			font-size: 16px;
			color: #ff4d79;
			font-family: 'Roboto', sans-serif;
		}
		
		html {
			background: #212a33;
		}
		
		#view-image {
			max-width: 1000px;
			margin: 20px auto;
			padding: 10px;
			background: #eee;
			border-radius: 5px;
		}
		
		#view-image img {
			width: 100%;
		}
		
		#view-image p {
			margin: 20px;
			text-align: center;
		}
	</style>
	
	<!-- Analytics -->
	<?php echo $analytics; ?>
</head>
<body>
	<div id="view-image">
		<img src="<?php echo $image; ?>" alt="Fossil image for Reading Nature's Library">
		<p><a href="/share/index/0">You can take part in recording fossil images here.</a></p>
	</div>
</body>
</html>