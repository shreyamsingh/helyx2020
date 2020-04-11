
<?php
  /*require 'autoload.php';*/
  echo "<h1>Hello, world</h1>";
  try {
    if (extension_loaded("mongodb")) {

      //$bulk = new MongoDB\Driver\BulkWrite;

      // code for insertion
      /*$doc = [

      'clientName' => 'Raizel',

      'email' => 'raizel@whatever.com',

      'tag' => 'Admin User',

      ];

      $bulk->insert($doc);*/

      // code for deletion
      //$bulk->delete(['topic_name' => "Delete MongoDB Document"], ['limit' => 1]);

      $manager = new MongoDB\Driver\Manager("mongodb+srv://smsingh:trashtracker123@trashtracker-zxpwj.gcp.mongodb.net/test?retryWrites=true&w=majority");
      $query = new MongoDB\Driver\Query([]);

      //$result = $manager->executeBulkWrite('mydb.users', $bulk);
      $cursor = $manager->executeQuery("mydb.users", $query);
      /*foreach ($cursor as $document) {
        echo "<p>hihihi</p>";
        echo var_dump($document);
      }*/
    }
  }

  catch (MongoConnectionException $e) {
    echo var_dump($e);
  }

 ?>
