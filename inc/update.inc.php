<?php
// Include the functions so you can create a URL
include_once 'functions.inc.php';

// Include the image handling class
include_once 'images.inc.php';

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit'] == 'Save Entry'
   && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['page'])) {
      // Create a URL to save in the database
      $url = makeUrl($_POST['title']);

      if(isset($_FILES['image']['tmp_name'])) {
        try {
            // Instantiate the class and set a save path
            $img = new ImageHandler("/post-hub-php/imgs");

            // Process the file and store the returned path
            $img_path = $img->processUploadedImage($_FILES['image']);
        }
        catch(Exception $e) {
            // If an error occurred, output your custom error message
            die($e->getMessage());
        }
      } else {
          // Avoids a notice fi no image was uploaded
          $img_path = NULL;
      }

      // Include database credentials and connect to the database
      include_once 'db.inc.php';
      $db = new PDO(DB_INFO, DB_USER, DB_PASS);

      // Edit an existing post
      if(!empty($_POST['postID']))
      {
        $sql = "UPDATE posts
                SET title=?, image=?, content=?, url=?
                WHERE postID=?
                LIMIT 1";
        $q = $db->prepare($sql);
        $q->execute(array(
          $_POST['title'],
          $img_path,
          $_POST['content'],
          $url,
          $_POST['postID']
        ));
        $q->closeCursor();
      } else {
        // create the post into the database
        $sql = "INSERT INTO posts (title, image, content, page, url) VALUES (?, ?, ?, ?, ?)";
        $q = $db->prepare($sql);
        $q->execute(array(
            $_POST['title'],
            $img_path,
            $_POST['content'],
            $_POST['page'],
            $url
          )
        );
        $q->closeCursor();

        // Get the ID of teh entry we just saved
        $id_obj = $db->query("SELECT LAST_INSERT_ID()");
        $id = $id_obj->fetch();
        $id_obj->closeCursor();
      }

      // Sanitize the page information for use in the success URL
      $page = htmlentities(strip_tags($_POST['page']));

      // Send the user to the new post
      header('Location: /post-hub-php/'.$page.'/'.$url);
      exit;
   }
   // If both conditions aren't met, sends the user back to the main page
   else {
      header('Location: ../');
      exit;
   }

?>
