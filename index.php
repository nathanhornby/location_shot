<!DOCTYPE html>

	<head>

		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />

		<!-- Meta -->
		<title>Location Shot</title>

		<!-- Open Graph Meta -->
		<meta property="og:title" content="Location Shot" />
		<meta property="og:description" content="Real-time snapshots of anywhere in the world." /> 
		<meta property="og:type" content="website" />
		<meta property="og:url" content="http://shot.li/location" />
		<meta property="og:site_name" content="Location Shot" />
		
		<!-- Favicons -->
		<link rel="shortcut icon" href="favicon.ico" />
		
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400" />
		<link rel="stylesheet" type="text/css" href="css/app.css?<?php echo filemtime(dirname(__FILE__).'/css/app.css'); ?>" />

	</head>
	<body>

		<!-- Layout -->
		<section id="location_bar">
			<form id="location_search">
				<div id="form_fade"></div>
				<input type="text" name="location" placeholder="Search location" autocomplete="off" />
			</form>
			<div id="location_meta">
			</div>
			<div class="clear"></div>
		</section>

		<div id="content">
		</div>

		<!-- JS -->
		<script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="application/javascript" src="js/app.min.js?<?php echo filemtime(dirname(__FILE__).'/js/app.min.js'); ?>"></script>

	</body>
	
</html>
