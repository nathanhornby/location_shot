$(document).ready(function(){
	$("#location_search").submit(function(e){
		e.preventDefault();

		var query = $("#location_search input[name='location']").val();

		if(query !== ''){
			// Hide it
			$("#location_bar").css('top','-100px');
			$("#location_map").css('top','-400px');
			$("#location_images").fadeOut(function(){
				$(this).html('');
			});

			// Get data
			$.ajaxSetup({
				cache : false
			});
			$.ajax({
				data: "location="+query,
				url: "data.php"
			})
			.done(function(data){
				var result = $.parseJSON(data);

				// Update location, time and weather
				$("#location_search input[name='location']").val(result.location);
				$("#location_meta").html('<p>'+result.weather.time+' <img id="location_weather" src="img/climacons/'+result.weather.icon+'.svg" /></p>');

				// Update map
				$("#location_map").attr('style','background-image:url('+result.map+'&style=visibility:off&style=visibility:off&style=feature:water|visibility:on|hue:0x88c8ea|saturation:-10|lightness:1&style=feature:landscape|visibility:on|hue:0xf3f4f4|saturation:-100|lightness:1&style=feature:poi.park|visibility:on|hue:0x91c9ae|saturation:10|lightness:1&style=feature:road|visibility:on&style=feature:road.arterial|visibility:on|hue:0xfddf84|saturation:100|lightness:1&style=feature:road.highway|visibility:on|hue:0xfddf84|saturation:100|lightness:1&style=feature:road.local|visibility:on|hue:0xffffff|saturation:-100|lightness:100&style=feature:road|element:geometry.stroke|visibility:off&style=element:labels|visibility:off);');

				// Insert images
				if(result.images){
					$.each(result.images,function(index,value){
						$("#location_images").append('<img src="'+value+'"" />');
					});
					$("#location_images").append('<div class="clear"></div>');
				}else{
					$("#location_images").append('<p>There are no images for this location</p>');
				}
				

				// Load it all in
				$("#location_bar").css('top','0');
				$("#location_map").css('top','0');
				$("#location_images").delay(600).fadeIn();
			})
			.fail(function(){
				alert('Something went wrong :(');
			});
		}
	});
});