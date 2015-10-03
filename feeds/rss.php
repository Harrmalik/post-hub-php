<?php

// Include necessary filesize
include_once '../inc/functions.inc.php';
include_once '../inc/db.inc.php';

// Open a database connection
$db = new PDO(DB_INFO, DB_USER, DB_PASS);

// Load all blog post
$p = getPosts($db, 'thread');

// Remove the fulldisp flag
array_pop($p);

// Perform basic data sanitization
$p = sanitizeData($p);

// Add a content type header to ensure proper execution
header('Content-Type: application/rss+xml');

// Output XML declaration
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

?>

<rss version="2.0">
  <channel>
    <title>Post Hub PHP</title>
    <link>http://localhost/post-hub-php/</link>
    <description>This blog is awesome.</description>
    <language>en-us</language>

    <?php
    // Loop through the posts and generate RSS items
    foreach($p as $p):
      // Escape HTML to avoid errors
      $post = htmlentities($p['content']);

      // Build the full URL to the post
      $url = 'http://localhost/post-hub-php/thread/' . $p['url'];

      // Format the date correctly for RSS pubDate
      $date = date(DATE_RSS, strtotime($p['created']));

    ?>
      <item>
          <title><?php echo $p['title']; ?></title>
          <description><?php echo $post; ?></description>
          <link><?php echo $url; ?></link>
          <guid><?php echo $url; ?></guid>
          <pubDate><?php echo $date; ?></pubDate>
      </item>
    <?php endforeach; ?>

  </channel>
</rss>
