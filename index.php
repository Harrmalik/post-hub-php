
<?php include_once 'views/header.php'; ?>


<!-- <h1 class="col-md-8 col-md-offset-2 page-header"> Posts </h1> -->
      <div id="posts" class="col-md-8 col-md-offset-2">
         <?php
            //If the full display flag is set, show the post
            if($fulldisp == 1){
               // Get the URL if one wasn't passed
               $url = (isset($url)) ? $url : $p['url'];

              // Build the admin links
              $admin = (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) ? adminLinks($page, $url) :
                        array('edit' => NULL, 'delete' => NULL);

               // Format the image if one exists
               $img = formatImage($p['image'], $p['title']);

               if($page == 'thread') {
                  // Load the comment object
                  include_once 'inc/comments.inc.php';
                  $comments = new Comments();
                  $comment_disp = $comments->showComments($p['postID']);
                  $comment_form = ($_SESSION['username']) ? $comments->showCommentForm($p['postID'], $_SESSION['username']) : NULL;
               } else {
                  $comment_form = NULL;
               }
         ?>

         <article>
         <h1 class="page-header"><small><?php echo $p['title']; ?> </small></h1>
         <?php echo $img ?>
         <p><?php echo $p['content'] ?></p>
         <p>
            <?php echo $admin['edit'] ?>
            <?php if($page=='thread') echo $admin['delete'] ?>
         </p>

         </article>

         <?php if($page=='thread'): ?>
            <p class="backlink">
               <a href=".." class="btn btn-link">Back to Latest Posts</a>
            </p>
            <h3> Comments for This Post </h3>
         <?php echo $comment_disp, $comment_form;
            if($_SESSION['loggedin'] == NULL){
              echo "<a href='/post-hub-php/admin/login' class='btn btn-default'>Log in to comment</a>";
            }
         ?>


         <?php endif; ?>
         <?php
            } else {
               //Loop through each post
               foreach($p as $post) {
                 // messed up and created 3 null objects, this hides them for the mean time
                 // please go back and fix
                 if($post == NULL){}
                  else{
         ?>
         <!-- <a href="/post-hub-php/<?php echo $post['page'] ?>/<?php echo $post['url'] ?>"> -->

         <h2 class="page-header">
            <small><?php echo $post['title'] ?></small></h3>
            <article>
          <p><?php echo substr($post['content'],0,80).'<br>...
            <a href="/post-hub-php/'.$post['page'].'/'.$post['url'].'">Read More</a>'; ?></p>
          </article>
            <?php } ?>
            <hr>
         <?php
            } // End the foreach loop
         } // End the else
         ?>
         <p class="backlink">
            <?php if($page=='thread' && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1): ?>
            <a href="/post-hub-php/admin/<?php echo $page ?>" class="btn btn-primary">Write a new Post!</a>
            <?php endif; ?>
         </p>
         <p>
           <a href="/post-hub-php/feeds/rss.php" class="btn btn-link">Subscribe via RSS!</a>
         </p>
      </div>
<?php include_once 'views/footer.php'; ?>
