<?php 
  $sql = "SELECT * FROM articles WHERE type = 'help' ORDER BY title";
  $result = mysql_query($sql);
  if ($result) {
    echo '<h2>I.T. Help</h2>';
    if ( hasPermission('help') ) {
      echo '<form name="wilbur" method="post" action="index.php?page=article">';
        echo '<input type="hidden" name="action" value="add" />';
        echo '<input type="hidden" name="type" value="help" />';
      echo '<input type="submit" value="New Help Article" />';
      echo '</form>';
    }
    echo '<div style="width: 700px; margin-right: auto; margin-left: auto;">';
    echo '<ul>';
    while ($row = mysql_fetch_array($result)) {
      echo '<li class="sample" style="line-height: 25px;"><a href="?page=article&articleid=' . $row['id'] . '">' . $row['title'] . '</a></li>';
    }
    echo '</ul>';
    echo '</div>';
  }
?>

