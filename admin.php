<?php include_once 'views/header.php'; ?>

      <h1> Post Hub </h1>
      <form method="post" action="/post-hub-php/inc/update.inc.php">
      <fieldset>
      <legend>New Post Submission</legend>
      <label>Title
      <input type="text" name="title" maxlength="150" />
      </label>
      <label>Content
      <textarea name="content" cols="45" rows="10"></textarea>
      </label>
      <input type="hidden" name="page" value="<?php echo $page ?>" />
      <input type="submit" name="submit" value="Save Entry" />
      <input type="submit" name="submit" value="Cancel" />
      </fieldset>
      </form>

<?php include_once 'views/footer.php'; ?>
