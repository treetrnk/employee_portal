<?php 

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

  $counter = 1;
  foreach ($subcategories as $sc) {
    $type = 'resources';
    $identifier = "resc$counter";
    
    echo "
      <div class='togglebtn' onClick='hidediv(\"$identifier\")' id='$identifier-btn'><span id='$identifier-arw' style='float:right;font-weight:normal;'>&#9650; &nbsp;&nbsp;</span>&nbsp;&nbsp;$sc</div>
      <div class='togglediv' id='$identifier'>
    ";

        $sql = "SELECT * FROM articles WHERE type = 'resources' AND del = 'n' AND subcat = '$sc' ORDER BY title";
        $result = mysql_query($sql);

    
        if ( hasPermission('resources')) {
          echo "
            <form method='post' action='index.php?page=article'>
              <input type='hidden' name='action' value='add' />
              <input type='hidden' name='type' value='$type' />
              <input type='hidden' name='subcat' value='$sc' />
              <input type='submit' value='Add Article' style='float:right; position:relative; top:18px;' />
            </form>
          ";
        }


        echo "
          <ul>
            <br />
        ";

        if ($result) {
          while ($article = mysql_fetch_array($result)) {
            echo "
                <li><a href='?page=article&articleid=$article[id]'>$article[title]</a></li>
            ";
          }

          echo "
            </ul><br />
          </div>
          ";

          $counter ++;
        }

  }


?>
