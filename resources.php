<?php 

  $sql = "SELECT * FROM articles WHERE type = 'resources' ORDER BY title";
  $result = mysql_query($sql);
  
  if ( hasPermission('resources') ) {
    echo "
      <form method='post' action='index.php?page=article' style='float:right;'>
        <input type='hidden' name='action' value='add' />
        <input type='hidden' name='type' value='resources' />
        <input type='submit' value='Add Category' style='float:right;' />
      </form>
    ";
  }

  echo "
    <h2>Resources</h2>
    <div style='width: 700px; margin-right: auto; margin-left: auto;'>
  ";

  if ($result) {
    while ($article = mysql_fetch_array($result)) {
      $type = $article['type'];
      $articleid = $article['id'];
      $identifier = "resc$article[id]";
      
      echo "
        <div class='togglebtn' onClick='hidediv(\"$identifier\")' id='$identifier-btn'><span id='$identifier-arw' style='float:right;font-weight:normal;'>&#9650; &nbsp;&nbsp;</span>&nbsp;&nbsp;$article[title]</div>
        <div class='togglediv' id='$identifier'>
      ";
          if ( hasPermission('resources') ||  $article['userid'] == $_SESSION['id']) {
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

    }
  }


?>
