<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/env.php';


$app = array();
$app['bucket_name'] = "trash-tracker2020.appspot.com";
$app['mysql_user'] = "root";
$app['mysql_password'] = "trashtracker123";
$app['mysql_dbname'] = "map";
$app['project_id'] = getenv('GCLOUD_PROJECT');

$servername = '127.0.0.1:5432'; // for local testing
//$servername = null; // to deploy
$username = $app['mysql_user'];
$password = $app['mysql_password'];
$dbname = $app['mysql_dbname'];
$dbport = null;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname,
	$dbport, "/cloudsql/trash-tracker2020:us-central1:database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['lat']) && isset($_POST['long'])) {
    $lat = $_POST['lat'];
    $long = $_POST['long'];
  	$sql = "INSERT INTO latlong (lat, lng) VALUES ($lat, $long)";

		mysqli_query($conn, $sql);
}
$sql2 = "SELECT * FROM latlong";
$arr = array();
$result = mysqli_query($conn, $sql2);
while ($row = mysqli_fetch_assoc($result)) {
		$arr[$row['lat']] =  $row['lng'];
}

mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
    <head>
        <title>TrashTracker</title>
        <link rel="stylesheet" href="TTmapstyle.css">
    </head>

    <body>
        <header>
            <h1>TrashTracker</h1>
        </header>

        <main>
						<div class="top">
	            <div class="blurb">
	                <p class="text">An intelligent tracker to aid in the cleanup of discarded waste</p>
							</div>
								<div class="form">
								<form method="post" action="form.php">
									<div class="button">
										<input type = "submit" name="" value= "Add Trash">
									</div>
								</form>
								</div>
						</div>


            <!-- Map scripts -->
            <div id="map"></div>
            <script>
                function initMap() {

									var image = {
	url: 'https://findicons.com/files/icons/1580/devine_icons_part_2/512/trash_recyclebin_empty_closed.png',
	// This marker is 20 pixels wide by 32 pixels high.
	scaledSize: new google.maps.Size(30, 32),
	// The origin for this image is (0, 0).
	origin: new google.maps.Point(0, 0),
	// The anchor for this image is the base of the flagpole at (0, 32).
	anchor: new google.maps.Point(20, 25)
};
                    var USA = {lat: 37.28, lng: -104.66};
                    var Denver = {lat: 39.76, lng: -105.00};
                    var arr = <?php echo json_encode($arr, JSON_PRETTY_PRINT) ?>;
										var map = new google.maps.Map(document.getElementById('map'), {zoom: 4, center: USA});
										var arr2 = [];
                    for (x in arr) {
											var place = {lat: parseFloat(x), lng: parseFloat(arr[x])};
                      arr2.push(new google.maps.Marker({position: place, map: map, icon: image}));
                    }

                    var marker = new google.maps.Marker({position: USA, map: map, icon: image});
                    var marker2 = new google.maps.Marker({position: Denver, map: map, icon: image});
                }
            </script>
            <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9DiHQ7OyF9Bf-U4eFGDHmUk91qQQk2sM&callback=initMap"></script>
        </main>
    </body>
</html>
