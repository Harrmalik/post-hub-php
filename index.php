<?php
   /*
   * Include the necessary files
   */
   include_once 'inc/functions.inc.php';
   include_once 'inc/db.inc.php';

   // Open a database connection
   $db = new PDO(DB_INFO, DB_USER, DB_PASS);

   // Determine if an entry ID was passed in the URL
   $id = (isset($_GET['id'])) ? (int) $_GET['id'] : NULL;

   // Load the entries
   $e = getPosts($db, $id);

   // Get the fulldisp flag and remove it from the array
   $fulldisp = array_pop($e);

   // Sanitize the entry data
   $e = sanitizeData($e);

?>

<?php include_once 'views/header.php'; ?>

      <h1> Post Hub </h1>

      <div id="posts">
         <?php
            //If teh full display flag is set, show the entry
            if($fulldisp == 1){
         ?>

         <h2><?php echo $e['title']; ?> </h2>
         <p><?php echo $e['content']; ?> </p>

         <p class="backlink">
            <a href="./">Back to Latest Entries</a>
         </p>
         <?php
            } else {
               //Loop through each post
               foreach($e as $post) {
         ?>
         <p><a href="?id=<?php echo $post['id'] ?>">
            <?php echo $post['title'] ?> </a></p>

         <?php
            } // End the foreach loop
         } // End the else
         ?>
         <p class="backlink">
            <a href="admin.php">Post a New Entry</a>
         </p>
      </div>

<?php include_once 'views/footer.php'; ?>
