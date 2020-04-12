<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/env.php';


$app = array();
$app['bucket_name'] = "trash-tracker2020.appspot.com";
$app['mysql_user'] = "root";
$app['mysql_password'] = "trashtracker123";
$app['mysql_dbname'] = "users";
$app['project_id'] = getenv('GCLOUD_PROJECT');

//$servername = '127.0.0.1:5432'; // for local testing
$servername = null; // to deploy
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

$sql2 = "SELECT user,points FROM pointentries ORDER BY points DESC limit 5";
$arr = array();
$points = array();
$result = mysqli_query($conn, $sql2);
while ($row = mysqli_fetch_assoc($result)) {
		$arr[] =  $row['user'];
		$points[] = $row['points'];
}

mysqli_close($conn);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
      <link rel ="stylesheet" type = "text/css" href="styles.css">
</head>
<body>
<font face="ink free">
  <h1><center><b>Leaderboard</b></center></h1>
  <h2><center><b>Here's today's top TrashTrackers!</b></center></h2> </font>
<div class="wrapper">
    <div class="lboard_section">
        <div class="lboard_tabs">
          <div class="tabs">
              <ul>
                <li data-li="today"><b>Today</b></li>
             </ul>
          </div>
        </div>

        <div class="lboard_wrap">
            <div class="lboard_item_today">
                <div class="lboard_mem">
                    <!-- <div class="img">
                        <img src="https://images.unsplash.com/photo-1578269174936-2709b6aeb913?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80" alt="picture_1" width="96" height="65">
                      </div> -->
                      <div class="name_bar">
                          <p><span><b>1: </span> <?php echo $arr[0] ?></b></p>
                          <div class="bar_wrap">
                              <div class="inner_bar" style="width: 15%">
                              </div>
                     </div>
                </div>
                  <div class="points"><b>
                      <?php echo $points[0] ?>
                    </b></div>

            </div>
            <div class="lboard_mem">
                <div class="img">

                  </div>
                  <div class="name_bar">
                      <p><span>2:                 </span><b><?php echo $arr[1] ?></b></p>
                      <div class="bar_wrap">
                          <div class="inner_bar" style="width: 15%">
                          </div>
                 </div>
            </div>
              <div class="points">
                  <b><?php echo $points[1] ?></b>
                </div>

        </div>

        <div class="lboard_mem">
            <div class="img">

              </div>
              <div class="name_bar">
                  <p><span>3:                 </span><b><?php echo $arr[2] ?></b></p>
                  <div class="bar_wrap">
                      <div class="inner_bar" style="width: 15%">
                      </div>
             </div>
        </div>
          <div class="points">
              <b><?php echo $points[2] ?></b>
            </div>

    </div>

      </div>



        </div>
      </div>
  </div>

</body>
</html>
