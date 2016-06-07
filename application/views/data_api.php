<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reading Nature's Library</title>
	<meta name="author" content="Rob Dunne" />
	<meta name="description" content="A citizen science application in collaboration with Manchester Museum" />
	<meta name="keywords"  content="citizen science, fossils" />
	<meta name="Resource-type" content="Document" />

	<!-- Typography -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
	
	<!-- Icons -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/data_api.css" />

	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	
	<!-- Analytics -->
	<?php echo $analytics; ?>
</head>
<body>

<?php echo $project_id_error; ?>

<div id="header">
	<div id="uom-logo">
		<a href="http://www.museum.manchester.ac.uk/">
			<img src="/assets/img/uom-logo.png" alt="Manchester Museum logo" />
		</a>
	</div>
	
	<h1>Reading Nature's Library Open Data API</h1>
	
	<div class="clear"></div>
</div>

<div id="main">
	<h2>RNL API Instructions</h2>
	
	<hr>
	
	<p>The API provided here gives access to the data collected by Reading Nature's Library.</p>
	<p>The URL format is <strong>https://natureslibrary.co.uk/data/api/format/project_id</strong></p>
	<p>Where <strong>format</strong> is the output format. Current options are:
		<ul>
			<li>json</li>
			<li>csv</li>
			<li>xml</li>
		</ul>
	</p>
	
	<p>And <strong>project_id</strong> is the number of the project (batch of fossils) requested. Currently only project ID 1 is available.</p>
	
	<p><strong>Example API URLs:</strong></p>
	<p><a href="https://natureslibrary.co.uk/data/api/json/1">https://natureslibrary.co.uk/data/api/json/1</a></p>
	<p><a href="https://natureslibrary.co.uk/data/api/csv/1">https://natureslibrary.co.uk/data/api/csv/1</a></p>
	<p><a href="https://natureslibrary.co.uk/data/api/xml/1">https://natureslibrary.co.uk/data/api/xml/1</a></p>
	
	<p>If you are a non-technical user download the CSV format, which can be opened as a spreadsheet.</p>
</div>

</body>
</html>