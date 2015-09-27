<?php

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit'] == 'Save Entry'
   && !empty($_POST['title']) && !empty($_POST['content'])) {
      // Include database credentials and connect to the database
      include_once 'db.inc.php';
      $db = new PDO(DB_INFO, DB_USER, DB_PASS);

      // Save the entry into the database
      $sql = "INSERT INTO posts (title, content) VALUES (?, ?)";
      $q = $db->prepare($sql);
      $q->execute(array($_POST['title'], $_POST['content']));
      $q->closeCursor();

      // Get the ID of teh entry we just saved
      $id_obj = $db->query("SELECT LAST_INSERT_ID()");
      $id = $id_obj->fetch();
      $id_obj->closeCursor();

      // Send the user to the new entry
      header('Location:../?id='.$id[0]);
      exit;
   }
   // If both conditions aren't met, sends the user back to the main page
   else {
      header('Location: ../');
      exit;
   }

?>
