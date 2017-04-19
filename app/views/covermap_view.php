<?php
//print '<pre>';
//var_dump($data);die();?>
<div class="row">
<div id="map" class="col-lg-6 col-sm-12 col-xs-12" style="height:400px"></div>
</div>

<div class="table-responsive">
        <table class="table" style="word-wrap:break-word; width:100%">
            <thead>
            <tr>
                <td>Cover (times)</td>
                <td>Postcodes</td>
            </tr>
            </thead>
            <tbody>
<?php
        foreach ($data['cover'] as $key=>$item)
        {
            print '<tr><td>'.$key.'</td>';
            print '<td>'.implode(', ',$item).'</td></td>';
        }

 ?>
            </tbody>
        </table>
</div>
<script>
    var map;
    var latitude=-25.274398;
    var longitude=133.77513599999997;
    var coords=JSON.parse('<?php print $data['coords']?>');
    console.log(coords);

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: {lat: latitude, lng: longitude}
    });
    map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });
    function placeMarkerAndPanTo(latLng, map) {
        // var marker = new google.maps.Marker({
        //   position: latLng,
        //   map: map
        // });
        latitude=latLng.lat();
        longitude=latLng.lng();
        map.panTo(latLng);
    }
//    var geocoder = new google.maps.Geocoder();
//
//    document.getElementById('submit').addEventListener('click', function() {
//        geocodeAddress(geocoder, map);
//    });
    for (var i=0;i<coords.length;i++)
    {
        drawCircle(coords[i][0],coords[i][1],coords[i][2])
    }
}
    function drawCircle(lat,lon,rad){
        cityCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: {lat: +lat, lng: +lon},
            radius: +rad*1000
        });}

    </script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWELWBrUnLJM0psypjZIvAe09YGJ_NgIA&callback=initMap">
</script>