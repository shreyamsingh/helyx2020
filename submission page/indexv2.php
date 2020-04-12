<?php
  function gps2Num($coordPart){
    $parts = explode('/', $coordPart);
    if(count($parts) <= 0)
    return 0;
    if(count($parts) == 1)
    return $parts[0];
    return floatval($parts[0]) / floatval($parts[1]);
   }

   function get_image_location($image = ''){
    $exif = exif_read_data($image, 0, true);
    if($exif && isset($exif['GPS'])){
        $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
        $GPSLatitude    = $exif['GPS']['GPSLatitude'];
        $GPSLongitudeRef= $exif['GPS']['GPSLongitudeRef'];
        $GPSLongitude   = $exif['GPS']['GPSLongitude'];

        $lat_degrees = count($GPSLatitude) > 0 ? gps2Num($GPSLatitude[0]) : 0;
        $lat_minutes = count($GPSLatitude) > 1 ? gps2Num($GPSLatitude[1]) : 0;
        $lat_seconds = count($GPSLatitude) > 2 ? gps2Num($GPSLatitude[2]) : 0;

        $lon_degrees = count($GPSLongitude) > 0 ? gps2Num($GPSLongitude[0]) : 0;
        $lon_minutes = count($GPSLongitude) > 1 ? gps2Num($GPSLongitude[1]) : 0;
        $lon_seconds = count($GPSLongitude) > 2 ? gps2Num($GPSLongitude[2]) : 0;

        $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
        $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;

        $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
        $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));

        return array('latitude'=>$latitude, 'longitude'=>$longitude);
    }
    else{
        return false;
      }
}
$imageURL = "land.jpg";
$imgLocation = get_image_location($imageURL);
if(!empty($imgLocation))
  {
    $imgLat = $imgLocation['latitude'];
    $imgLng = $imgLocation['longitude'];
  //  echo '<p>Latitude: '.$imgLat.' | Longitude: '.$imgLng.'</p>';
  }
  else{
  //  echo'<p>Geotags not found.</p>';
  }
 ?>
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9DiHQ7OyF9Bf-U4eFGDHmUk91qQQk2sM&callback=initMap"></script>
 <script>
function intialize(){
     var myCenter = new google.maps.LatLng(<?php echo $imgLat; ?>, <?php echo $imgLng; ?> );
     var mapProp = { zoom: 10,  center: myCenter, mapTypeId:google.maps.MapTypeId.ROADMAP };

     var map = new google.maps.Map(document.getElementById("map"), mapProp);

     var marker  = new google.maps.Marker({ position:myCenter, map: map, optimized: false, title: 'Trash!' });
 //var marker = new google.maps.Marker({position: USA, map: map});
 //var map = new google.maps.Map(document.getElementById('map'), {zoom: 4, center: USA});

 //var map = new google.maps.Map(
 //document.getElementById('map'), {zoom: 4, center: uluru});
 //The marker, positioned at Uluru
 //var marker = new google.maps.Marker({position: uluru, map: map});

      marker.setMap(map);
   }
   google.maps.event.addDomListener(window,'load',intialize);
   </script>
 <style>
 #map{
   width: 75%;
   height: 490px;
   float:right;
 }

 #image{
   width: 50%;
   height: 0px;
 }
 #image img{width:0.5%, height:10px;}

 .blurb {
   display: flex;
   position: static;
   /* top: 200px;
   left: 50px; */
   color: rgb(0, 0, 0);
   height: 100px;
   justify-content: center;
   font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
   font-size: 20px;
   background-color: deepskyblue;
   justify-content: center;
 }
 header {
   display: flex;
   position: static;
   top: 0;
   left: 0;
   right: 0;
   height: 100px;
   justify-content: center;
   line-height: 50px;
   background-color: rgb(38, 104, 36);
   color: rgb(255, 255, 255);
   font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
 }
 main {
   background-color: deepskyblue;
   justify-content: center;
 }
 </style>


  <header>
      <h1>TrashTracker</h1>
  </header>
  <div class="blurb">
      <p>An intelligent tracker to aid in the cleanup of discarded waste</p>
  </div>
<?php  if(!empty($imgLocation))
  {
    $imgLat = $imgLocation['latitude'];
    $imgLng = $imgLocation['longitude' ];
    echo '<p align=center>Latitude: '.$imgLat.' | Longitude: '.$imgLng.'</p>';
  }
  else{
    echo'<p align=center>Geotags not found.</p>';
  }
   ?>
      <div id="image"><img src="<?php echo $imageURL; ?>" height="490" width=50% /></div>
      <div id="map"></div>
</div>
