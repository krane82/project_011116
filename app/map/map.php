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
        top: 0px;
        /*left: 25%;*/
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
	  #modalWait{
		  position:absolute;
		  top:0px; 
		  right:0px;
		  bottom:0px;
		  left:0px;
		  z-index:10;
		  background-color:rgba(0,0,0,0.5);
		  display:none
	  }
	  #modalRespond{
		  position:absolute;
		  top:0px; 
		  right:0px;
		  bottom:0px;
		  left:0px;
		  z-index:10;
		  background-color:white;
		  display:none;
          word-wrap:break-word;
	  }
	  .sk-circle {
  margin: 100px auto;
  width: 40px;
  height: 40px;
  position: relative;
}
.sk-circle .sk-child {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
}
.sk-circle .sk-child:before {
  content: '';
  display: block;
  margin: 0 auto;
  width: 15%;
  height: 15%;
  background-color: #333;
  border-radius: 100%;
  -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
          animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
}
.sk-circle .sk-circle2 {
  -webkit-transform: rotate(30deg);
      -ms-transform: rotate(30deg);
          transform: rotate(30deg); }
.sk-circle .sk-circle3 {
  -webkit-transform: rotate(60deg);
      -ms-transform: rotate(60deg);
          transform: rotate(60deg); }
.sk-circle .sk-circle4 {
  -webkit-transform: rotate(90deg);
      -ms-transform: rotate(90deg);
          transform: rotate(90deg); }
.sk-circle .sk-circle5 {
  -webkit-transform: rotate(120deg);
      -ms-transform: rotate(120deg);
          transform: rotate(120deg); }
.sk-circle .sk-circle6 {
  -webkit-transform: rotate(150deg);
      -ms-transform: rotate(150deg);
          transform: rotate(150deg); }
.sk-circle .sk-circle7 {
  -webkit-transform: rotate(180deg);
      -ms-transform: rotate(180deg);
          transform: rotate(180deg); }
.sk-circle .sk-circle8 {
  -webkit-transform: rotate(210deg);
      -ms-transform: rotate(210deg);
          transform: rotate(210deg); }
.sk-circle .sk-circle9 {
  -webkit-transform: rotate(240deg);
      -ms-transform: rotate(240deg);
          transform: rotate(240deg); }
.sk-circle .sk-circle10 {
  -webkit-transform: rotate(270deg);
      -ms-transform: rotate(270deg);
          transform: rotate(270deg); }
.sk-circle .sk-circle11 {
  -webkit-transform: rotate(300deg);
      -ms-transform: rotate(300deg);
          transform: rotate(300deg); }
.sk-circle .sk-circle12 {
  -webkit-transform: rotate(330deg);
      -ms-transform: rotate(330deg);
          transform: rotate(330deg); }
.sk-circle .sk-circle2:before {
  -webkit-animation-delay: -1.1s;
          animation-delay: -1.1s; }
.sk-circle .sk-circle3:before {
  -webkit-animation-delay: -1s;
          animation-delay: -1s; }
.sk-circle .sk-circle4:before {
  -webkit-animation-delay: -0.9s;
          animation-delay: -0.9s; }
.sk-circle .sk-circle5:before {
  -webkit-animation-delay: -0.8s;
          animation-delay: -0.8s; }
.sk-circle .sk-circle6:before {
  -webkit-animation-delay: -0.7s;
          animation-delay: -0.7s; }
.sk-circle .sk-circle7:before {
  -webkit-animation-delay: -0.6s;
          animation-delay: -0.6s; }
.sk-circle .sk-circle8:before {
  -webkit-animation-delay: -0.5s;
          animation-delay: -0.5s; }
.sk-circle .sk-circle9:before {
  -webkit-animation-delay: -0.4s;
          animation-delay: -0.4s; }
.sk-circle .sk-circle10:before {
  -webkit-animation-delay: -0.3s;
          animation-delay: -0.3s; }
.sk-circle .sk-circle11:before {
  -webkit-animation-delay: -0.2s;
          animation-delay: -0.2s; }
.sk-circle .sk-circle12:before {
  -webkit-animation-delay: -0.1s;
          animation-delay: -0.1s; }

@-webkit-keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}

@keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}
    </style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
  	<div id="modalWait">
	<div class="sk-circle">
  <div class="sk-circle1 sk-child"></div>
  <div class="sk-circle2 sk-child"></div>
  <div class="sk-circle3 sk-child"></div>
  <div class="sk-circle4 sk-child"></div>
  <div class="sk-circle5 sk-child"></div>
  <div class="sk-circle6 sk-child"></div>
  <div class="sk-circle7 sk-child"></div>
  <div class="sk-circle8 sk-child"></div>
  <div class="sk-circle9 sk-child"></div>
  <div class="sk-circle10 sk-child"></div>
  <div class="sk-circle11 sk-child"></div>
  <div class="sk-circle12 sk-child"></div>
</div>
</div>
<div id="modalRespond">
<div>
    <button type="button" onclick="saveAll()">Save postcodes to profile</button>
    <button type="button" style="float:right" id="closeButton" onclick="modalRespond.style.display='none'">Close List</button></div>
<div></div>
</div>
    <div id="floating-panel">
      <input id="address" type="textbox" placeholder="Address or ZIP">
      <input id="submit" type="button" value="Search">
	  <input id="radius" type="textbox" placeholder="betw. 1000 and 30, 30Km default,">
      <input id="seekCodes" type="button" value="Search Codes!">
    </div>
    <div id="map"></div>

    <script>
      var map;
	  var cityCircle=false;
	  var seek=document.getElementById('seekCodes');
	  var latitude=-25.274398;
	  var longitude=133.77513599999997;
	  var radius=30;
      var codes;
      var marker;
      var modalWait=document.getElementById('modalWait');
      var modalRespond=document.getElementById('modalRespond');
      function saveAll()
      {
          var coords=window.parent.document.getElementsByName('coords');
          var postcodes=window.parent.document.getElementsByName('postcodes');
          for(var i=0;i<coords.length;i++)
          {
              coords[i].value=latitude+':'+longitude+':'+radius;
              postcodes[i].value=codes;
          }
      }
	  seek.onclick=function(){
          modalWait.style.display="block";
		  $('#editClient').show;
		  radius=document.getElementById('radius').value || radius;
		  
//          (radius>500)?radius=500:(radius<30)?radius=30:radius;
//          document.getElementById('radius').value=radius;

          if(radius>1000)
		  {
			  radius=1000;
			  document.getElementById('radius').value=radius;
		  }
          if(radius<30)
          {
              radius=30;
              document.getElementById('radius').value=radius;
          }

          if(cityCircle) {cityCircle.setMap(null);}
		  drawCircle();
		  $.ajax({
            type: "POST",
			url: 'ajaxquery.php',
            data:  {"ltd":latitude,"lng":longitude,"radius":radius},
            success: function (data) {
			modalWait.style.display="none";
			modalRespond.children[1].innerHTML=data;
			modalRespond.style.display="block";
			codes=data;
		;}
        });
		  };
		  function drawCircle(){
		  cityCircle = new google.maps.Circle({
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: {lat: latitude, lng: longitude},
      radius: radius*1000
		  });}
	  function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: {lat: latitude, lng: longitude}
        });
		map.addListener('click', function(e) {
    placeMarkerAndPanTo(e.latLng, map);
  });

        var geocoder = new google.maps.Geocoder();

        document.getElementById('submit').addEventListener('click', function() {
          geocodeAddress(geocoder, map);
        });
      }
      function placeMarkerAndPanTo(latLng, map) {
          if(marker) {
              marker.setMap(null);
          }
          marker = new google.maps.Marker({
              position: latLng,
              map: map
          });
          latitude=latLng.lat();
          longitude=latLng.lng();
          map.panTo(latLng);
      }
      function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address,componentRestrictions: {
    country: 'AU'
  }},
            function(results, status) {
          if (status === 'OK') {
              placeMarkerAndPanTo(results[0].geometry.location, resultsMap);
          }
          else {
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