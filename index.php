<?php include_once 'views/header.php'; ?>

      <h1> Post Hub </h1>

      <div id="posts">
         <?php
            //If the full display flag is set, show the post
            if($fulldisp == 1){
               // Get the URL if one wasn't passed
               $url = (isset($url)) ? $url : $p['url'];

               // Build the admin links
               $admin = adminLinks($page, $url);

               // Format the image if one exists
               $img = formatImage($p['image'], $p['title']);
         ?>

         <h2><?php echo $p['title']; ?> </h2>
         <p><?php echo $img, $p['content'] ?> </p>
         <p>
            <?php echo $admin['edit'] ?>
            <?php if($page=='thread') echo $admin['delete'] ?>
         </p>

         <?php if($page=='thread'): ?>
            <p class="backlink">
               <a href="..">Back to Latest Posts</a>
            </p>
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
         <h3>
            <?php echo $post['title'] ?></h3>
          <p><?php echo substr($post['content'],0,80).'<br />...
            <a href="/post-hub-php/'.$post['page'].'/'.$post['url'].'">Read More</a>'; ?></p>
            <?php } ?>
         <?php
            } // End the foreach loop
         } // End the else
         ?>
         <p class="backlink">
            <?php if($page=='thread'): ?>
            <a href="/post-hub-php/admin/<?php echo $page ?>">Write a new Post!</a>
            <?php endif; ?>
         </p>
      </div>

<?php include_once 'views/footer.php'; ?>
