<!DOCTYPE html>
<html>
  <head>
    <title>Geocoding service</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    <div id="floating-panel">
      <input id="address" type="textbox" value="Sydney, NSW">
      <input id="submit" type="button" value="Geocode">
    </div>
    <div id="map"></div>
    <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
          center: {lat: -34.397, lng: 150.644}
        });
		map.addListener('click', function(e) {
    placeMarkerAndPanTo(e.latLng, map);
  });
  function placeMarkerAndPanTo(latLng, map) {
 // var marker = new google.maps.Marker({
 //   position: latLng,
 //   map: map
 // });
 console.log(latLng.lat());
 console.log(latLng.lng());
 var lat=latLng.lat();
 var lng=latLng.lng();
	//var urltext="ajax/get-all-australia-suburbs-inside.php?radius="+givenRad+"&lat="+lat+"&lng="+lng+"&rn="+rn;
  map.panTo(latLng);
}
        var geocoder = new google.maps.Geocoder();

        document.getElementById('submit').addEventListener('click', function() {
          geocodeAddress(geocoder, map);
        });
      }

      function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address,componentRestrictions: {
    country: 'AU'
  }}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            console.log(results[0].geometry.location.lat());
            console.log(results[0].geometry.location.lng());
			//var marker = new google.maps.Marker({
            //  map: resultsMap,
            //  position: results[0].geometry.location
            //});
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWELWBrUnLJM0psypjZIvAe09YGJ_NgIA&callback=initMap">
    </script>
  </body>
</html>