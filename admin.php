<?php include_once 'views/header.php';
// If the user is logged in, we can contiune
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==1):
?>
<section class="col-md-8 col-md-offset-2">

      <h1> Post Hub </h1>
      <?php if($page == 'delete'): {
                echo $confirm;
              }
            else:
      ?>
      <form method="post" action="/post-hub-php/inc/update.inc.php" enctype="multipart/form-data">
      <fieldset>
        <legend><?php echo $legend ?></legend>
        <div class="form-group">
        <label>Title
          <input class="form-control" type="text" name="title" maxlength="150" value="<?php echo $p['title'] ?>" />
        </label>
      </div>

        <div class="form-group">
        <label>Image
          <input type="file" name="image" />
        </label>
       </div>

        <div class="form-group">
        <label>Content
          <textarea class="form-control" name="content" cols="45" rows="10"><?php echo $p['content'] ?></textarea>
        </label>
      </div>


        <input type="hidden" name="postID" value="<?php echo $p['postID'] ?>" />
        <input type="hidden" name="page" value="<?php echo $page ?>" />
        <input type="submit" name="submit" value="Save Entry" class="btn btn-primary" />
        <input type="submit" name="submit" value="Cancel" class="btn btn-default" />
      </fieldset>
      </form>
      <?php endif; ?>

    <?php elseif($page == 'createuser' || $page == 'createadmin'): {
          echo $create;} else: ?>

        <form action="/post-hub-php/inc/update.inc.php" method="post">
          <fieldset>
              <legend>Please Log in to continue</legend>
              <label for="username">Username</label>
              <input class="form-control" type="text" name="username" maxlength="75" />

              <label for="password">Password</label>
              <input class="form-control" type="text" name="password" maxlength="75" />
              <br>

              <input type="submit" name="submit" value="Log In" class="btn btn-primary" />
              <a href="/post-hub-php/admin/createuser" class="btn btn-link">Sign Up</a>
              <input type="hidden" name="action" value="login" class="btn btn-default" />

          </fieldset>
        </form>
    <?php endif; // Ends the section available to logged in users ?>
</section>
<?php include_once 'views/footer.php'; ?>
