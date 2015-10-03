<?php

// Start the session
session_start();
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
   } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Post Comment') {
      // Include and instantiate the Comments class
      include_once 'comments.inc.php';
      $comments = new Comments();

      // Save the comment
      if($comments->saveComment($_POST)){
          // If available, store the entry the user came FROM
          $loc = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '../';

          // Send the user back to the Post
          header('Location: '.$loc);
          exit;
      } else {
          exit('Something went wrong while saving the comment.');
      }
   } else if($_GET['action'] == 'comment_delete') {
      // If the delete link is clicked on a comment, confirm it here

      // Include and instantiate the Comments class
      include_once 'comments.inc.php';
      $comments = new Comments();
      echo $comments->confirmDelete($_GET['id']);
      exit;
   } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'comment_delete') {
        // If the confirmDelete() form was submitted, handle it here

        // If set, store the post from which we came
        $loc = isset($_POST['url']) ? $_POST['url'] : '../';

        // If the user clicekd 'Yes', continue with deletion
        if($_POST['confirm'] == 'Yes') {
            // Include and instantiate the Comments class
            include_once 'comments.inc.php';
            $comments = new Comments();

            // Delete the comment and return to the post
            if($comments->deleteComment($_POST['id'])) {
                header('Location: '.$loc);
                exit;
            } else {
                // If deleting fails, output an error message
                exit('Could not delete the comment.');
            }
        }

        // If the user clicked "No", do nothing and return to the post
        else {
          header('Location: '.$loc);
          exit;
        }
   } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login'
      && !empty($_POST['username']) && !empty($_POST['password'])) {
        // If a  user is trying to log in, check it here

        // Include database credentials and connect to the database
        include_once 'db.inc.php';
        $db = new PDO(DB_INFO, DB_USER, DB_PASS);
        $sql = "SELECT COUNT(*) AS num_users
                FROM admin
                WHERE username=?
                AND password=SHA1(?)";
        $q = $db->prepare($sql);
        $q->execute(array($_POST['username'], $_POST['password']));
        $response = $q->fetch();
        $_SESSION['loggedin'] = ($response['num_users'] > 0) ? 1 : NULL;

        header('Location: /post-hub-php');
        exit;

   }else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'createuser'
    && !empty($_POST['username']) && !empty($_POST['password'])) {
          // Include database credentials and connect to the database
          include_once 'db.inc.php';
          $db = new PDO(DB_INFO, DB_USER, DB_PASS);
          $sql = "INSERT INTO admin (username, password)
                  VALUES(?, SHA1(?))";
          $q = $db->prepare($sql);
          $q->execute(array($_POST['username'], $_POST['password']));

          header('Location: /post-hub-php/');
          exit;
   } else if($_GET['action'] == 'logout') {
      session_destroy();
      header('Location: ../');
      exit;
   }else {
     // If both conditions aren't met, sends the user back to the main page
      header('Location: ../');
      exit;
   }

?>
