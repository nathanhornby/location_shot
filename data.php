<?
	error_reporting(0);
	
	// Private settings
	require_once('../shotli_location_private.php');

	// Curl
	function curl_it($url){
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_HEADER, false);
		curl_setopt($c, CURLOPT_USERAGENT, $_SERVER["LOCATIONSHOT_USERAGENT"]);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		 
		$data = curl_exec($c);
		curl_close($c);

		return $data;
	}

	// Geocode fetch
	function geocode($location){
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($location)."&key=".$_SERVER['GOOGLE_KEY'];

		$data = curl_it($url);

		$data = json_decode($data);

		$location = '';
		if($data->results){
			$i = 0;
			foreach ($data->results[0]->address_components as $address_component[]) {
				$location['address'.($i+1)] = $address_component[$i]->long_name;
				$i++;
			}
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
		
		$data = curl_it($url);

		$data = json_decode($data);

		$weather = '';
		if(!isset($data->error)){
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
		$url = "http://instagram.com/tags/".strtolower($safe_tag)."/feed/recent.rss";

		$data = curl_it($url);

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
			$instagram = instagram($location['address1']);
			foreach ($instagram->channel->item as $image) {
				$images[] = array('title' => (string)$image->title, 'image' => (string)$image->guid) ;
			}

			// Set address search string
			$center = urlencode($location['address1']);

			// Set zoom level and place string based on number of address components
			$zoom = round( (count($location)-2) * 3.2 );
			$place = implode( ', ', array_splice($location, 0, (count($location)-2) ) );

			// Get map
			$map = "http://maps.googleapis.com/maps/api/staticmap?center=".$center."&zoom=".$zoom."&size=640x640&scale=2&maptype=road";

			// Encode data
			$raw = array("location" => $place, "weather" => $weather, "map" => $map, "images" => $images);
			$data = json_encode($raw);

			// Output
			echo $data;
		}
		else{
			return false;
		}	
	}else{
		return false;
	}
?>