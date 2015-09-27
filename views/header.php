<?php
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

   // Determine if an post ID was passed in the URL
   $id = (isset($_GET['id'])) ? (int) $_GET['id'] : NULL;

   // Load the posts
   $e = getPosts($db, $page, $id);

   // Get the fulldisp flag and remove it from the array
   $fulldisp = array_pop($e);

   // Sanitize the entry data
   $e = sanitizeData($e);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

   <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <title> Post Hub </title>
      <link href="css/normalize.css" rel="stylesheet" type="text/css"/>
      <link href="css/foundation.min.css" rel="stylesheet" type="text/css"/>
      <link href="css/styles.css" rel="stylesheet" type="text/css"/>
   </head>

   <body>
      <header>
         <nav>
            <ul>
               <li><a href="index.php">Home</a></li>
            </ul>
         </nav>
      </header>
