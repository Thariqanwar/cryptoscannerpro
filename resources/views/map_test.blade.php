<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        #set{ height: 300px; width: 300px;}
        #map{position: static !important;}
    </style>
</head>
<body>
    <div id='set'>
        <input type="text" id="lat" name="lat" readonly="yes"><br>
        <input type="text" id="lng" name="lng" readonly="yes">
        <div id="map"></div>


    </div>

    <script type="text/javascript">
    //map.js
     
    //Set up some of our variables.
    var map; //Will contain map object.
    var marker = false; ////Has the user plotted their location marker? 
            
    //Function called to initialize / create the map.
    //This is called when the page has loaded.
    function initMap() {
     
        //The center location of our map.
        var centerOfMap = new google.maps.LatLng(52.357971, -6.516758);
     
        //Map options.
        var options = {
          center: centerOfMap, //Set center.
          zoom: 16 //The zoom value.
        };
     
        //Create the map object.
        map = new google.maps.Map(document.getElementById('map'), options);
        infoWindow = new google.maps.InfoWindow;
        //Listen for any clicks on the map.
        google.maps.event.addListener(map, 'click', function(event) {                
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if(marker === false){
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });
                //Listen for drag events!
                google.maps.event.addListener(marker, 'dragend', function(event){
                    markerLocation();
                });
            } else{
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            markerLocation();
        });
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            alert('latitude:'+pos.lat+' longitude:'+pos.lng);

            infoWindow.setPosition(pos);
            infoWindow.setContent('<p id="cc">Location found.</p>');
            infoWindow.open(map);
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                                  'Error: The Geolocation service failed.' :
                                  'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    }
            
    //This function will get the marker's current location and then add the lat/long
    //values to our textfields so that we can save the location.
    function markerLocation(){
        //Get location.
        var currentLocation = marker.getPosition();
        
        alert('latitude:'+currentLocation.lat()+' longitude:'+currentLocation.lng());
        //Add lat and lng values to a field that we can save.
        document.getElementById('lat').value = currentLocation.lat(); //latitude
        document.getElementById('lng').value = currentLocation.lng(); //longitude
    }
            
            
    //Load the map when the page has finished loading.
    google.maps.event.addDomListener(window, 'load', initMap);
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAi66ciopo43vyY7Dn4zqKaeIswzLrf-q0&callback=initMap">
    </script>
</body>
</html>

