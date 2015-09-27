<?php include_once 'views/header.php'; ?>

      <h1> Post Hub </h1>

      <div id="posts">
         <?php
            //If teh full display flag is set, show the post
            if($fulldisp == 1){
         ?>

         <h2><?php echo $e['title']; ?> </h2>
         <p><?php echo $e['content']; ?> </p>

         <?php if($page=='thread'): ?>
            <p class="backlink">
               <a href="./">Back to Latest Posts</a>
            </p>
         <?php endif; ?>

         <?php
            } else {
               //Loop through each post
               foreach($e as $post) {
         ?>
         <p><a href="?id=<?php echo $post['postID'] ?>">
            <?php echo $post['title'] ?> </a></p>

         <?php
            } // End the foreach loop
         } // End the else
         ?>
         <p class="backlink">
            <?php if($page=='thread'): ?>
            <a href="/post-hub-php/admin.php?page=<?php echo $page ?>">Write a new Post!</a>
            <?php endif; ?>
         </p>
      </div>

<?php include_once 'views/footer.php'; ?>
