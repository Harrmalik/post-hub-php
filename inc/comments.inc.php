<?php

include_once 'db.inc.php';

class Comments {
    // Our database connection
    public $db;

    // Upon class instantiation, open a database connection
    public function __construct(){
        // Open a database connection and store it
        $this->db = new PDO(DB_INFO, DB_USER, DB_PASS);
    }











    // An array for containing the post
    public $comments;










    // Display a form for users to enter new comments with
    public function showCommentForm($post_id, $name =NULL) {
        return <<<FROM
        <form action="/post-hub-php/inc/update.inc.php" method="post" id="comment-form" class="form-horizontal">
          <fieldset>
            <legend>Post a Comment</legend>
            <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" maxlength="75" value="$name"/>
            </div>

            <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="text" name="email" maxlength="150" />
            </div>

            <div class="form-group">
            <label for="comment">Comment</label>
            <textarea class="form-control" name="comment" rows="10" cols="45"></textarea>
            </div>

            <input type="hidden" name="post_id" value="$post_id" />
            <input type="submit" name="submit" value="Post Comment" class="btn btn-default" />
            <input type="submit" name="submit" value="Cancel" class="btn btn-default" />
          </fieldset>
        </form>
FROM;
    }













    public function saveComment($cf) {
        // Sanitize the data and store in variables
        $post_id = htmlentities(strip_tags($cf['post_id']), ENT_QUOTES);
        $name = htmlentities(strip_tags($cf['name']), ENT_QUOTES);
        $email = htmlentities(strip_tags($cf['email']), ENT_QUOTES);
        $comment = htmlentities(strip_tags($cf['comment']), ENT_QUOTES);
        // Keep formatting of comments and remove extra whitespace
        $comment = nl2br(trim($comment));

        // Generate and prepare the SQL command
        $sql = "INSERT INTO comments (post_id, name, email, comment)
                VALUE (?, ?, ?, ?)";
        if($q = $this->db->prepare($sql)) {
            // Execute the command, free used memory, and return trader_get_unstable_period
            $q->execute(array($post_id, $name, $email, $comment));
            $q->closeCursor();
            return TRUE;
        } else {
            // If something went wrong, return false
            return FALSE;
        }
    }

    public function editComment($cf) {
      $comment = htmlentities(strip_tags($cf['comment']), ENT_QUOTES);
      // Keep formatting of comments and remove extra whitespace
      $comment = nl2br(trim($comment));
      $sql ="UPDATE Comments
             SET comment =?
             WHERE id= ?
             LIMIT 1";

      $q = $db->prepare($sql);
      $q->execute(array($comment, $cf['id']));
    }













    // Load all comments for a thread into memory
    public function getComments($post_id) {
        // Get all the comments for teh post
        $sql = "SELECT id, name, email, comment, date
                FROM comments
                WHERE post_id=?
                ORDER BY date DESC";
        $q = $this->db->prepare($sql);
        $q->execute(array($post_id));

        // Loop through returned rows
        while($comment = $q->fetch()){
           //Store in memory for later use
           $this->comments[] = $comment;
        }

        // Set up a default response if no comments exist
        if(empty($this->comments)) {
          $this->comments[] = array(
            'id' => NULL,
            'name' => NULL,
            'email' => NULL,
            'comment' => "There are no comments on this post.",
            'date' => NULL
          );
        }
    }










    // Generates HTML markup for displaying comments
    public function showComments($post_id) {
        // Initialize the variable in case no comments exist
        $display = NULL;

        // Load the comments for the post
        $this->getComments($post_id);

        // Loop through the stored comments
        foreach($this->comments as $c) {
            // Prevent empty fields if no comments exist
            if(!empty($c['date']) && !empty($c['name'])) {
                // Outputs similar to: July 8, 2009 at 4:39PM
                $format = "F j, Y \a\\t g:iA";

                //Convert $c['date'] to a timestamp, then format
                $date = date($format, strtotime($c['date']));

                // Generate a byline for the comment
                $byline = "<span><strong>$c[name]</strong><small>[Posted on $date]</small></span><br>";

                if(isset($_SESSION['loggedin']) && $_SESSION['username'] == $c['name'] || $_SESSION['loggedin'] == 1) {
                  // Generate delete link for the comment display
                  $admin = ["<br><a href=\"/post-hub-php/inc/update.inc.php"
                           . "?action=comment_delete&id=$c[id]\""
                           . "class=\" btn-xs admin btn btn-danger\">delete</a>",

                           "<a href=\"/post-hub-php/inc/update.inc.php"
                                    . "?action=comment_edit&id=$c[id]\""
                                    . "class=\"admin\">edit</a>"];
                } else {
                  $admin = NULL;
                }

            } else {
                // If no comments exist, set $byline & $admin to null
                $byline = NULL;
                $admin = NULL;
            }

            // Assemble the pieces into a formatted comment
            $display .= "<p class=\"comment\">$byline$c[comment]$admin[0]</p>";
        }

        // Return all the formatted comments as a string
        return $display;
    }









    // Ensure the user really wants to delete the comment
    public function  confirmDelete($id) {
        // Store the post if available
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../";

        return <<<FROM
        <html>
        <head>
        <title>Please Confirm Your Decision</title>
        <link rel="stylesheet" type="text/css" href="/post-hub-php/css/default.css" />
        </head>
        <body>
        <form action ="/post-hub-php/inc/update.inc.php" method="post">
          <fieldset>
              <legend>Are You Sure?</legend>
              <p>Are you sure you want to delete this comment?</p>

            <input type="hidden" name="id" value="$id" />
            <input type="hidden" name="action" value="comment_delete" />
            <input type="hidden" name="url" value="$url" />
            <input type="submit" name="confirm" value="Yes" class="btn btn-danger" />
            <input type="submit" name="confirm" value="No" class="btn btn-default" />
          </fieldset>
        </form>
        </body>
        </html>

FROM;
    }












    // Removes the comment corresponding to $id from the database
    public function deleteComment($id){
      $sql = "DELETE FROM comments
              WHERE id=?
              LIMIT 1";
      if($q = $this->db->prepare($sql)) {
          // Execute teh command, free used memory, and return true
          $q->execute(array($id));
          $q->closeCursor();
          return TRUE;
      } else {
          // If somehting went wrong, return false
          return FALSE;
      }
    }



}

?>
