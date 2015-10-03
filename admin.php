<?php include_once 'views/header.php';
// If the user is logged in, we can contiune
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1):
?>

      <h1> Post Hub </h1>
      <?php if($page == 'delete'): {
                echo $confirm;
              } elseif($page == 'createuser'): {
                echo $create;
              }
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
      <?php endif; ?>

    <?php else: ?>
        <form action="/post-hub-php/inc/update.inc.php" method="post">
          <fieldset>
              <legend>Please Log in to continue</legend>
              <label for="username">Username</label>
              <input type="text" name="username" maxlength="75" />

              <label for="password">Password</label>
              <input type="text" name="password" maxlength="75" />

              <input type="submit" name="submit" value="Log In" />
              <input type="hidden" name="action" value="login" />
          </fieldset>
        </form>
    <?php endif; // Ends the section available to logged in users ?>

<?php include_once 'views/footer.php'; ?>
