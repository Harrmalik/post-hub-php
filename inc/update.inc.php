<?php

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit'] == 'Save Entry'
   && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['page'])) {
      // Include database credentials and connect to the database
      include_once 'db.inc.php';
      $db = new PDO(DB_INFO, DB_USER, DB_PASS);

      // Save the entry into the database
      $sql = "INSERT INTO posts (title, content, page) VALUES (?, ?, ?)";
      $q = $db->prepare($sql);
      $q->execute(array($_POST['title'], $_POST['content'], $_POST['page']));
      $q->closeCursor();

      // Get the ID of teh entry we just saved
      $id_obj = $db->query("SELECT LAST_INSERT_ID()");
      $id = $id_obj->fetch();
      $id_obj->closeCursor();

      // Sanitize the page information for use in the success URL
      $page = htmlentities(strip_tags($_POST['page']));

      // Send the user to the new entry
      header('Location:../?page='.$page.'&id='.$id[0]);
      exit;
   }
   // If both conditions aren't met, sends the user back to the main page
   else {
      header('Location: ../');
      exit;
   }

?>
