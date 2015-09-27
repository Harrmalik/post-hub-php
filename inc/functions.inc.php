<?php

function getPosts($db, $postID=NULL) {
   // if post id was supplied, load the associated post
   if(isset($postID)) {
      // Load specified entry
      $sql = "SELECT title, content
              FROM posts
              WHERE postID=?
              LIMIT 1";
      $q = $db->prepare($sql);
      $q->execute(array($_GET['id']));

      // Save the returned post array
      $e = $q->fetch();

      // Set the fulldisp flag for a single post
      $fulldisp = 1;
   } else {
      // If no post was supplied, load all post titles
      $sql = "SELECT postID, title
              FROM posts
              ORDER BY created DESC";
      // Loop through returned results and store as an array
      foreach($db->query($sql) as $row){
         $e[] = array(
               'id' => $row['postID'],
               'title' => $row['title']
         );
      }
      // Set the fulldisp flag for multiple posts
      $fulldisp = 0;
      /*
      * If no entries were returned, display a default
      * message and set the fulldisp flag to display a
      * single entry
      */
      if(!is_array($e)) {
         $fulldisp = 1;
         $e = array(
            'title' => 'No Entries Yet',
            'content' => '<a href="/admin.php">Create a new Post!</a>'
         );
      }
   }

   // Return loaded data
   array_push($e, $fulldisp);

   return $e;
}

function sanitizeData($data) {
   // If $data is not an array, run strip_tags()
   if(!is_array($data)){
      // Remove all tags execpt <a> tags
      return strip_tags($data, "<a>");
   } else {
      // Call sanitizeData recursively for each array element
      return array_map('sanitizeData', $data);
   }
}

 ?>
