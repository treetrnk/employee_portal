<?php 

  $sql = "SELECT * FROM articles WHERE type = 'resources' LIMIT 1";
  $result = mysql_query($sql);
  if ($result) {
    $article = mysql_fetch_array($result);
    $type = $article['type'];
    $articleid = $article['id'];
  }
  
  echo "
    <h2>$article[title]</h2>
    <div style='width: 700px; margin-right: auto; margin-left: auto;'>
  ";
  
    if ( hasPermission('admin') ||  $article['userid'] == $_SESSION['id']) {
      echo "
        <form method='post' action='index.php?page=article&articleid=$articleid'>
          <input type='hidden' name='action' value='edit' />
          <input type='hidden' name='type' value='$type' />
          <input type='submit' value='Edit Article' style='float:right;' />
        </form>
      ";
    }

    echo "
      $article[body]
      <br /><br />

    </div>
    ";

?>
