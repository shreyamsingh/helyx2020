<?php
    $currentDirectory = getcwd();
    $uploadDirectory = "/after/";


    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['jpeg','jpg','png']; // These will be the only file extensions allowed

    $fileName = $_FILES['the_file']['name'];
    $fileSize = $_FILES['the_file']['size'];
    $fileTmpName  = $_FILES['the_file']['tmp_name'];
    $fileType = $_FILES['the_file']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $before = 'before';
    //$target = $before.$fileExtension;
    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);

    if (isset($_POST['submit'])) {

      if (! in_array($fileExtension,$fileExtensionsAllowed)) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
      }

      if ($fileSize > 4000000) {
        $errors[] = "File exceeds maximum size (4MB)";
      }

      if (empty($errors)) {
       $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
      //  $didUpload = move_uploaded_file($fileExtensionT, $uploadPath);
        if ($didUpload) {
          echo "The file " . basename($fileName) . " has been uploaded";
            include('remaining.html');
        } else {
          echo "An error occurred. Please contact the administrator.";
        }
      } else {
        foreach ($errors as $error) {
          echo $error . "These are the errors" . "\n";
        }
      }

    }
?>
