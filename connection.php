<?php
/**
 * Files will be stored at e.g. https://storage.googleapis.com/<appspot site url>/testthis.txt
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/env.php';


$app = array();
$app['bucket_name'] = "trash-tracker2020.appspot.com";
$app['mysql_user'] = "root";
$app['mysql_password'] = "trashtracker123";
$app['mysql_dbname'] = "users";
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
echo "\nConnected successfully\n";


/*$sql = "CREATE TABLE IF NOT EXISTS entries (user VARCHAR(255), pass VARCHAR(255))";

if (mysqli_query($conn, $sql)) {
  echo "Table created successfully";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}*/
echo "hello, world";
if (isset($_POST['username']) && isset($_POST['password'])) {
  $user = $_POST['username'];
  $pass = hash("sha256", $_POST['password']);
	$sql = "SELECT pass FROM pointentries WHERE user = '$user'";
	echo $user;
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	if (empty($row)) {
		$sql2 = "INSERT INTO pointentries (user, pass, points) VALUES ('$user', '$pass', 0)";
	  if (mysqli_query($conn, $sql2)) {
	    header('Location: TTmap.html');
	  } else {
	    echo "Error inserting: " . mysqli_error($conn);
	  }
	}

	else {
		if ($row['pass'] == $pass) {
			$sql2 = "UPDATE pointentries SET points = points + 1 WHERE user = '$user'";
			mysqli_query($conn, $sql2);
			header('Location: TTmap.html');
		}
		else {
			header('Location: index.php');
		}
	}

	//echo $row['pass'];


	/*if (empty($result)) {
		$sql2 = "INSERT INTO entries (user, pass) VALUES ('$user', '$pass')";
	  if (mysqli_query($conn, $sql2)) {
	    echo "Account created!";
	  } else {
	    echo "Error inserting: " . mysqli_error($conn);
	  }
	}
	else {
		if ($result == $pass) {
			echo $result;
			echo "You're logged in!";
		}
		else {
			echo $result;
			echo "Wrong password.";
		}
	}*/



}
else {
  echo "oh no!";
}
mysqli_close($conn);

/*function upload_object($bucketName, $objectName, $source)
{
    $storage = new StorageClient();
    $file = fopen($source, 'r');
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->upload($file, [
        'name' => $objectName
    ]);
    printf('Uploaded %s to gs://%s/%s' . PHP_EOL, basename($source), $bucketName, $objectName);
}


if ($_FILES) {
	if ($_FILES["uploaded_files"]["error"] > 0) {
		echo "Error: " . $_FILES["uploaded_files"]["error"] . "<br />";
	} elseif ($_FILES["uploaded_files"]["type"] !== "text/plain") {
		echo "File must be a .txt";
	} else {
		$file_handle = fopen($_FILES['uploaded_files']['tmp_name'], 'r');

		upload_object($app['bucket_name'],
						$_FILES['uploaded_files']['name'],
						$_FILES['uploaded_files']['tmp_name']
					);

		var_dump($_FILES);
		echo "\n\n";
		var_dump($file_handle);
	}
}*/
/*$sql = 'CREATE TABLE IF NOT EXISTS entries (user VARCHAR(255), pass VARCHAR(255))';
$stmt = $db->prepare($sql);
$stmt->execute();
echo "hi";
if (isset($_POST['username']) && isset($_POST['password'])) {
  echo $_POST['username'];
  $stmt = $db->prepare('INSERT INTO entries (user, pass) VALUES (:user, :pass)');
      $stmt->execute([
          ':user' => $_POST['username'],
          ':pass' => hash("sha256", $_POST['password']),
      ]);
  echo "hello!";
  $results = $db->query('SELECT * from entries');
  echo $results;
}*/





//echo "\ntesting gcloud php\n";

?>
