<?php
  $wssql = "SELECT * FROM articles WHERE type = 'workshare' AND del = 'n' ORDER BY date DESC LIMIT 3 ";

  if ( hasPermission('workshare') ) {
    echo "
      <br />
      <form method='post' action='?page=article'>
        <input type='hidden' name='action' value='add' />
        <input type='hidden' name='type' value='workshare' />
        <input type='submit' value='Add Job' style='float: right;' />
      </form>
    ";
  } 

  echo "<h2>Workshare</h2>";

  $result = mysql_query($wssql);
  if (mysql_num_rows($result)) {
    echo "<ul>";
    while ($workshare = mysql_fetch_array($result)) {
      echo "<li><a href='?page=article&articleid=$workshare[id]'>$workshare[title]</a><br />";
    }
    echo "</ul>";
  } else {
    echo "<p>There are no current work share opportunities.</p>";  
  }

?>
