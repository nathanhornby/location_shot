<?
	// API keys
	require_once('../shotli_location_private.php');

	// Geocode fetch
	function geocode($location){
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($location)."&key=".$_SERVER['GOOGLE_KEY'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		$data = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($data);

		$location = '';
		if($data->results){
			$location['address1'] = $data->results[0]->address_components[0]->long_name;
			$location['address2'] = $data->results[0]->address_components[1]->long_name;
			$location['address3'] = $data->results[0]->address_components[2]->long_name;
			$location['address4'] = $data->results[0]->address_components[3]->long_name;
			$location['address5'] = $data->results[0]->address_components[4]->long_name;
			$location['address6'] = $data->results[0]->address_components[5]->long_name;
			$location['latitude'] = $data->results[0]->geometry->location->lat;
			$location['longitude'] = $data->results[0]->geometry->location->lng;
			return $location;
		}else{
			return false;
		};
	};

	// Weather fetch
	function weather($lat, $lng){
		$url = "https://api.forecast.io/forecast/".$_SERVER['FORECAST_KEY']."/".$lat.",".$lng;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		$data = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($data);

		$weather = '';
		if(!isset($data->error)){
			$weather['summary'] = $data->currently->summary;
			$weather['icon'] = $data->currently->icon;
			date_default_timezone_set ($data->timezone);
			$weather['time'] = date("H:i",$data->currently->time);
			return $weather;
		}else{
			return false;
		};
	};

	// Instagram fetch
	function instagram($tag){
		$safe_tag = str_replace(' ', '', $tag);
		$safe_tag = strtolower($safe_tag);
		$url = "http://instagram.com/tags/".strtolower($safe_tag)."/feed/recent.rss";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		$data = curl_exec($ch);
		curl_close($ch);

		try{
			$images = new SimpleXmlElement($data, LIBXML_NOCDATA);
		}catch(Exception $e){
			return false;
		}

		if($images){
			return $images;
		}else{
			return false;
		};
	}

	// Run app on request
	if($_GET['location']){
		// Get lat/lng of search query
		$location = geocode($_GET['location']);
		if($location){
			// Get weather
			$weather = weather($location['latitude'],$location['longitude']);
			// Get images
			$images = instagram($location['address1']);
			
			// Set zoom level and place string based on number of address components
			$zoom = '';
			$place = '';
			switch (true) {
				case $location['address6']:
					$zoom = 15;
					$place = $location['address1'].", ".$location['address2'].", ".$location['address3'].", ".$location['address4'].", ".$location['address5'].", ".$location['address6'];
					break;
				case $location['address5']:
					$zoom = 14;
					$place = $location['address1'].", ".$location['address2'].", ".$location['address3'].", ".$location['address4'].", ".$location['address5'];
					break;
				case $location['address4']:
					$zoom = 13;
					$place = $location['address1'].", ".$location['address2'].", ".$location['address3'].", ".$location['address4'];
					break;
				case $location['address3']:
					$zoom = 12;
					$place = $location['address1'].", ".$location['address2'].", ".$location['address3'];
					break;
				case $location['address2']:
					$zoom = 9;
					$place = $location['address1'].", ".$location['address2'];
					break;
				case $location['address1']:
					$zoom = 5;
					$place = $location['address1'];
					break;
				default:
					$zoom = false;
					$place = false;
					break;
			}

			// Get map
			$map = "http://maps.googleapis.com/maps/api/staticmap?center=".urlencode($location['address1'])."&zoom=".$zoom."&size=640x640&scale=2&maptype=road&style=visibility:off&style=visibility:off&style=feature:water|visibility:on|hue:0x88c8ea|saturation:-10|lightness:1&style=feature:landscape|visibility:on|hue:0xf3f4f4|saturation:-100|lightness:1&style=feature:poi.park|visibility:on|hue:0x91c9ae|saturation:10|lightness:1&style=feature:road|visibility:on&style=feature:road.arterial|visibility:on|hue:0xfddf84|saturation:100|lightness:1&style=feature:road.highway|visibility:on|hue:0xfddf84|saturation:100|lightness:1&style=feature:road.local|visibility:on|hue:0xffffff|saturation:-100|lightness:100&style=feature:road|element:geometry.stroke|visibility:off&style=element:labels|visibility:off";
		}	
	}
?>