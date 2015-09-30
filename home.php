<?php 

if ( hasPermission('article') ) {
  echo "
    <form name='henry' method='post' action='index.php?page=article'>
      <input type='hidden' name='action' value='add' />
      <input type='hidden' name='type' value='article' />
      <input type='submit' style='float: right; position:relative; top:20;' value='New Article' />
    </form>
  "; 
}

$limit = 15;

  echo '<h2>Recent Posts</h2>';
  $sql = "SELECT * FROM articles WHERE type = 'article' AND del = 'n' ORDER BY date DESC";
  $total_rows = mysql_num_rows(mysql_query($sql));
  $sql = $sql . " LIMIT $limit";
  if (isset($_GET['offset'])) { $sql = $sql . " OFFSET $_GET[offset]"; }

  $result = mysql_query($sql);
  if ($result) {
    echo '<ul style="list-style-type: none;">';
    while ($row = mysql_fetch_array($result)) { 
      $userids = $row['userid'];
      $usersql = mysql_query("SELECT * FROM staff WHERE id = '$userids'");
      $userinfo = mysql_fetch_array($usersql);

      echo "
        <li> 
          <b><a href='?page=article&articleid=$row[id]' style='font-size:12pt'>$row[title]</a></b>
          <br />
          <span style='font-size: 8pt;'>
            By: <a class='hidelink' href='?page=profile&profileid=$row[userid]'>$userinfo[fname] $userinfo[lname]</a>
            -  " . date('M j, Y @ g:i a', $row['date']) . "
          </span>
          <br />
          <br />
        </li>
      ";

    }
    echo '</ul>';
    multipageNav($limit, $total_rows);
  } else {
    echo "It didn't work :(";
    echo mysql_error();
  }


?>
