

function myMap() {
	var mapProp= {
	    center:new google.maps.LatLng(19.3274833,-99.228705),
	    zoom: 19,
	    mapTypeId: google.maps.MapTypeId.SATELLITE,
	    scaleControl: false,
	    streetViewControl: false,
	    overviewMapControl: false
	};
	var marker = new google.maps.Marker(
	{
	    position: mapProp['center'],
	    icon : 'img/marker-move.png'
	});
	var map=new google.maps.Map(document.getElementById("map"),mapProp);
	map.setTilt(0);
	marker.setMap(map);
	google.maps.event.addListener(map,'click',function() {
	    marker.setMap(null);
	    $('#map').removeClass('sombra');
	    marker = new google.maps.Marker(
	    {
	        position: mapProp['center'],
	        icon : 'img/marker-green.png'
	    });
	    marker.setMap(map);
	});
	function placeMarker(map, location) {
	    marker = new google.maps.Marker({
	        position: location,
	        map: map,
	        icon : 'img/marker-move.png',
	    });
	    infowindow.open(map,marker);
	}
}