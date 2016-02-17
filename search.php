<?php 

  $limit = 10;
  if (isset($_GET['post'])) { $_POST = unserialize(base64_decode($_GET['post'])); }
  if (isset($_GET['offset'])) { $offset = $_GET['offset']; } else { $offset = 0; }
  $search = $_POST['search'];
  $sql = "SELECT * FROM articles WHERE title LIKE '%$search%' 
    OR body LIKE '%$search%' OR location LIKE '%$search%' AND del='n'";
  $total_rows = mysql_num_rows(mysql_query($sql));
  $sql = $sql . " ORDER BY date DESC LIMIT " . $limit . " OFFSET " . $offset;         // END OF QUERY
  $result = mysql_query($sql);

  echo "
    <h2>Search results for '$search':</h2><br />
    <div style='margin-right:auto; margin-left:auto;'> 
  ";


  if (isset($_POST['search'])) {           // SEARCH BAR KEYWORDS
   
    if ($result) {

      multiPageNav($limit, $total_rows);
      echo "<br /><br />";

      while ($row = mysql_fetch_array($result)) {

        $count ++;

        echo "
          <div class='people-list' style='padding:15px; height:150px; border:1px solid #dedede;";
            if ($count % 2 == 0) { echo 'background: #dedede;'; }
            echo "'>
          
            <b>" . ucfirst($row['type']) . ": 
            <a href='?page=article&articleid=$row[id]'>$row[title]</a></b><br />
        
            <p>" . word_limit(strip_tags($row['body']), 30) . " . . . </p><br />
            ";

            $userids = $row['userid'];
            $usersql = mysql_query("SELECT * FROM staff WHERE id = '$userids'");
            $userinfo = mysql_fetch_array($usersql);
            echo "
              <span style='font-size: 8pt;'>
                By: <a class='hidelink' href='?page=profile&profileid=$row[userid]'>$userinfo[fname] $userinfo[lname]</a> - " . date('M j, Y @ g:i a', $row['date']) . "
      </span>

          </div>
        ";
      
      }      
      echo "<br /><br />";
      multiPageNav($limit, $total_rows);

    } else {
      echo "<p>No results found . . .</p>";
    }
  } else {
    echo "<p>Please enter search criteria . . . </p>";
  }
  echo "</div>";
?>
