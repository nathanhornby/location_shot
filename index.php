<!DOCTYPE html>

<?php require('app.php'); ?>

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
				<input type="text" name="location" placeholder="<?= ($place)?$place:'Location' ?>" autocomplete="off" />
			</form>
			<div id="location_meta">
				<p><?= $weather['time'] ?> <img id="location_weather" src="img/climacons/<?= $weather['icon'] ?>.svg" /></p>
			</div>
			<div class="clear"></div>
		</section>
		<section id="location_map" style="background-image: url('<?= $map ?>');"></section>
		<section id="location_images">
			<?php
			if($images){
				foreach($images->channel->item as $image){
					echo '<img src="'.$image->guid.'" />';
				}
			}else{
				echo'<p>No images to display</p>';
			}
			?>
			<div class="clear"></div>
		</section>

		<!-- JS -->
		<script type="application/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="application/javascript" src="js/app.min.js?<?php echo filemtime(dirname(__FILE__).'/js/app.min.js'); ?>"></script>

	</body>
	
</html>
