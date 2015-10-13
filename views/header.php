<?php

    session_start();
    $_SESSION['username'] = isset($_SESSION['username']) ? ($_SESSION['username']) : NULL;
    $_SESSION['loggedin'] = isset($_SESSION['loggedin']) ? ($_SESSION['loggedin']) : NULL;
   /*
   * Include the necessary files
   */
   include_once 'inc/functions.inc.php';
   include_once 'inc/db.inc.php';

   // Open a database connection
   $db = new PDO(DB_INFO, DB_USER, DB_PASS);

   /*
   * Figure out what page is being requested (default is blog)
   * Perform basic sanitization on the variable as well
   */
   $page = (isset($_GET['page'])) ? htmlentities(strip_tags($_GET['page'])) : 'thread';

   if(isset($_POST['action']) && $_POST['action'] == 'delete') {
     if($_POST['submit'] == 'Yes') {
       $url = htmlentities(strip_tags($_POST['url']));
       if(deletePost($db, $url)){
         header("Location: /post-hub-php/");
         exit;
       } else {
         exit('Error deleting the entry');
       }
     } else {
       header("Location: /post-hub-php/thread/$url");
       exit;
     }
   }

   // Determine if an post URL was passed
   $url = (isset($_GET['url'])) ? $_GET['url'] : NULL;

   // Load the posts
   $p = getPosts($db, $page, $url);

   // Get the fulldisp flag and remove it from the array
   $fulldisp = array_pop($p);

   // Sanitize the entry data
   $p = sanitizeData($p);

   if(isset($_GET['url'])) {
    //  $url= htmlentities(strip_tags($_GET['url']));

      if($page == 'delete')
        $confirm = confirmDelete($db, $url);

      // Set the legend of the form
      $legend = "Edit This Post";
   } else {
     // Check if we're creating a new user
     if($page == 'createadmin'){
       $isAdmin = TRUE;
       $create = createUserForm($isAdmin);
     } else if($page == 'createuser') {
       $isAdmin = FALSE;
       $create = createUserForm($isAdmin);
     }

     // Set the legend of the form
     $legend = "New Entry Submission";

     // Set variables to NULL if not editing
     $p['postID'] = NULL;
     $p['title'] = NULL;
     $p['content'] = NULL;
   }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

   <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <title> Post Hub </title>
      <link rel="alternate" type="application/rss+xml" title="Post Hub PHP- RSS 2.0" href="/post-hub-php/feeds/rss.php" />
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <link rel="stylesheet" href="../css/styles.css">
   </head>

   <body>
      <header class="row">
         <nav class="col-md-8 col-md-offset-2">
            <ul id="menu" class="nav nav-pills">
               <li role="presentation"><a href="/post-hub-php/thread/">Home</a></li>
               <li role="presentation"><a href="/post-hub-php/about/about-the-author">About</a></li>
               <?php if(!isset($_SESSION['loggedin'])): ?>
                 <li role="presentation"><a href="/post-hub-php/admin/login">Log In</a></li>
               <?php endif; ?>
            </ul>
         </nav>

         <div class="col-md-8 col-md-offset-2">
           <?php if(isset($_SESSION['loggedin'])): ?>
              <p class="alert alert-info" role="alert">
              <?php echo "Welcome " . $_SESSION['username']; ?>
              <a href="/post-hub-php/inc/update.inc.php?action=logout" class="alert-link">Log
              out</a>.
              </p>
            <?php endif; ?>
         </div>
      </header>
