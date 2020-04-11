<?php
  session_start();
  require_once('connect.php');

  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    echo $username;
    echo $password;
    $bulk = new MongoDB\Driver\BulkWrite;
    $doc = [

    'username' => $username,

    'password' => $password,

    ];
    echo $doc;
    $bulk->insert($doc);
    $query = new MongoDB\Driver\Query([]);

    $result = $manager->executeBulkWrite('mydb.users', $bulk);
    $cursor = $manager->executeQuery("mydb.users", $query);
    foreach ($cursor as $document) {
      echo "<p>hihihi</p>";
      //echo var_dump($document);
    }
  }

 ?>

<html>
<head>
  <form method="post" action="index.php">
  <fieldset>
    <label for="username">Username: </label><input type="text" name="username" /><br>
    <label for="password">Password: </label><input type="password" name="password" /><br>
    <input type="submit" value="Sign Up">
  </fieldset>
  </form>
</head>
</html>
