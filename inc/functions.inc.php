<?php

function getPosts($db, $page, $url=NULL) {
   // if post url was supplied, load the associated post
   if(isset($url)) {
      // Load specified entry
      $sql = "SELECT postID, page, title, content
              FROM posts
              WHERE url=?
              LIMIT 1";
      $q = $db->prepare($sql);
      $q->execute(array($url));

      // Save the returned post array
      $p = $q->fetch();

      // Set the fulldisp flag for a single post
      $fulldisp = 1;
   } else {
      // If no url was supplied, load all post titles
      $sql = "SELECT postID, page, title, content, url
              FROM posts
              WHERE page=?
              ORDER BY created DESC";
      $q = $db->prepare($sql);
      $q->execute(array($page));

      $p = NULL; // Declare the variable to avoid errors

      // Loop through returned results and store as an array
      while($row = $q->fetch()){
         if($page=='thread') {
            $p[] = $row;
            $fulldisp = 0;
         } else {
            $p[] = $row;
            $fulldisp = 1;
         }
      }
      
      /*
      * If no entries were returned, display a default
      * message and set the fulldisp flag to display a
      * single entry
      */
      if(!is_array($p)) {
         $fulldisp = 1;
         $p = array(
            'title' => 'No Entries Yet',
            'content' => '<a href="admin.php?page='.$page.'">Create a new Post!</a>'
         );
      }
   }

   // Return loaded data
   array_push($p, $fulldisp);

   return $p;
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









function makeUrl($title) {
   $patterns = array(
      '/\s+/',
      '/(?!-)\W+/'
   );
   $replacements  = array('-', '');

   return preg_replace($patterns, $replacements, strtolower($title));
}

 ?>
