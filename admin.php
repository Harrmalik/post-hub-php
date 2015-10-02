<?php include_once 'views/header.php'; ?>

      <h1> Post Hub </h1>
      <?php if($page == 'delete'):{
                echo $confirm;}
            else:
      ?>
      <form method="post" action="/post-hub-php/inc/update.inc.php" enctype="multipart/form-data">
      <fieldset>
        <legend><?php echo $legend ?></legend>
        <label>Title
          <input type="text" name="title" maxlength="150" value="<?php echo $p['title'] ?>" />
        </label>
        <label>Image
          <input type="file" name="image" />
        </label>
        <label>Content
          <textarea name="content" cols="45" rows="10"><?php echo $p['content'] ?></textarea>
        </label>

        <input type="hidden" name="postID" value="<?php echo $p['postID'] ?>" />
        <input type="hidden" name="page" value="<?php echo $page ?>" />
        <input type="submit" name="submit" value="Save Entry" />
        <input type="submit" name="submit" value="Cancel" />
      </fieldset>
      </form>

      <?php endif;?>

<?php include_once 'views/footer.php'; ?>
