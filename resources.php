<?php 

  $sql = "SELECT DISTINCT(category) FROM articles WHERE type = 'resources' AND del = 'n' ORDER BY category";
  $result = mysql_query($sql);

  /*
  if ( hasPermission('resources') ) {
    echo "
      <form method='post' action='index.php?page=article' style='float:right;'>
        <input type='hidden' name='action' value='add' />
        <input type='hidden' name='type' value='resources' />
        <input type='submit' value='Add Category' style='float:right;' />
      </form>
    ";
  }
   */

  echo "
    <h2>Resources</h2>
    <div style='width: 700px; margin-right: auto; margin-left: auto;'>
  ";

  if ($result) {
    $count = 0;
    while ($category = mysql_fetch_array($result)) {
      $count++;
      $identifier = "resc$count";
      
      echo "
        <div class='togglebtn' onClick='hidediv(\"$identifier\")' id='$identifier-btn'><span id='$identifier-arw' style='float:right;font-weight:normal;'>&#9650; &nbsp;&nbsp;</span>&nbsp;&nbsp;$category[category]</div>
        <div class='togglediv' id='$identifier'><br />
      ";

      $subcat_result = mysql_query(
        "SELECT DISTINCT(subcat) FROM articles 
        WHERE type = 'resources' 
        AND category = '$category[category]' 
        AND del = 'n' ORDER BY subcat"
      );
      
      while ($subcat = mysql_fetch_array($subcat_result)) {

        if ( hasPermission('resources') ||  $article['userid'] == $_SESSION['id']) {
          echo "
            <form method='post' action='index.php?page=article'>
              <input type='hidden' name='action' value='add' />
              <input type='hidden' name='type' value='resources' />
              <input type='hidden' name='category' value='$category[category]' />
              <input type='hidden' name='subcat' value='$subcat[subcat]' />
              <input type='submit' value='Add Article' style='float:right; position:relative; top:18px;' />
            </form>
          ";
        }

        echo "
          <h3>$subcat[subcat]</h3>
          <ul>
        ";

        $article_result = mysql_query(
          "SELECT * FROM articles 
          WHERE type = 'resources' 
          AND category = '$category[category]' 
          AND subcat = '$subcat[subcat]' 
          AND del = 'n' ORDER BY title"
        );

        while ($article = mysql_fetch_array($article_result)) {
          $type = $article['type'];
          $articleid = $article['id'];

          echo "
            <li><a href='?page=article&articleid=$articleid'>$article[title]</a></li>
          ";

        }

        echo "</ul>";

      }

      echo "<br /></div>";

    }
  }


?>
