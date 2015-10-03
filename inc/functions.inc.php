<?php

function getPosts($db, $page, $url=NULL) {
   // if post url was supplied, load the associated post
   if(isset($url)) {
      // Load specified entry
      $sql = "SELECT postID, page, title, image, content, created
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
      $sql = "SELECT postID, page, title, image, content, url, created
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









function adminLinks($page, $url) {
    // Format the link to be followed for each odbc_setoption
    $editURL = "/post-hub-php/admin/$page/$url";
    $deleteURL = "/post-hub-php/admin/delete/$url";

    // Make a hyperlink and add it to an array
    $admin['edit'] = "<a href=\"$editURL\">edit</a>";
    $admin['delete'] = "<a href=\"$deleteURL\">delete</a>";

    return $admin;
}









function confirmDelete($db, $url) {
  $p = getPosts($db, '', $url);

  return <<<FORM
  <form action="/post-hub-php/admin.php" method="post">
    <fieldset>
      <legend>Are You Sure?</legend>
      <p>Are you sure you want to delete the post "$p[title]"?</p>
      <input type="submit" name="submit" value="Yes" />
      <input type="submit" name="submit" value="No" />
      <input type="hidden" name="action" value="delete" />
      <input type="hidden" name="url" value="$url" />
    </fieldset>
  </form>
FORM;

}









  function deletePost($db, $url){
    $sql= "DELETE FROM posts
           WHERE url=?
           LIMIT 1";
    $q = $db->prepare($sql);
    return $q->execute(array($url));
  }









  function formatImage($img=NULL, $alt=NULL) {
    if($img != NULL) {
      return '<img src="'.$img.'" alt="'.$alt.'" />';
    } else {
      return NULL;
    }
  }










  function createUserForm() {
    return <<<FORM
    <form action="/post-hub-php/inc/update.inc.php" method="post">
      <fieldset>
        <legend>Create a New Administrator</legend>
        <label for="username">Username</label>
        <input type="text" name="username" maxlength="75" />

        <label for="password">Password</label>
        <input type="text" name="password" maxlength="75" />

        <input type="submit" name="submit" value="Create" />
        <input type="submit" name="submit" value="Cancel" />
        <input type="hidden" name="action" value="createuser" />
      </fieldset>
    </form>
FORM;
  }

?>
