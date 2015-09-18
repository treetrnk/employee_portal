<!----REAL CONTENT---->
<?php if (isset($_SESSION['security']) && $_SESSION['security'] > 1) { ?>
<form name="henry" method="post" action="index.php?page=article">
  <input type="hidden" name="action" value="add" />
  <input type="hidden" name="type" value="article" />
  <input type="submit" style="float: right;" value="New Article" />
</form>


<?php }

$limit = 15;

  echo '<h2>Recent Posts</h2>';
  $sql = "SELECT * FROM articles WHERE type = 'article' ORDER BY date DESC";
  $total_rows = mysql_num_rows(mysql_query($sql));
  $sql = $sql . " LIMIT $limit";
  if (isset($_GET['offset'])) { $sql = $sql . " OFFSET $_GET[offset]"; }

  $result = mysql_query($sql);
  if ($result) {
    multipageNav($limit, $total_rows);
    echo '<ul style="list-style-type: none;">';
    while ($row = mysql_fetch_array($result)) { 
      $userids = $row['userid'];
      $usersql = mysql_query("SELECT * FROM staff WHERE id = '$userids'");
      $userinfo = mysql_fetch_array($usersql);

      echo "<li>"; 
        echo "<b><a href='?page=article&articleid=$row[id]' style='font-size:12pt'>$row[title]</a></b>";
        echo "<br />";
        echo "<span style='font-size: 8pt;'>By: <a class='hidelink' href='?page=profile&profileid=$row[userid]'>$userinfo[fname] $userinfo[lname]</a> - "; 
        echo date('M j, Y @ g:i a', $row['date']);
        echo "</span>";
        echo "<br />";
        echo "<br />";
      echo "</li>";

    }
    echo '</ul>';
    multipageNav($limit, $total_rows);
  } else {
    echo "It didn't work :(";
    echo mysql_error();
  }





?>
  


<!----SAMPLE CONTENT
<h3 class="sample">Sample Article 1</h3>
<p class="sample">
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque faucibus risus a risus gravida varius. Quisque porta congue felis, nec molestie nisi semper eu. Pellentesque ut ultrices ante, quis mattis mauris. Donec id condimentum nibh. Duis ut aliquet augue. Donec ornare ipsum rutrum sollicitudin mattis. Ut vel placerat dolor. . . . <span style="float: right" class="sample"><a href="?page=article">Read More</a></span></p>
    <hr width=90% class="sample" />
  <h3 class="sample">Sample Article 2</h3>
<p class="sample">
Aenean cursus volutpat neque, nec fermentum tortor porttitor eu. Praesent rhoncus aliquet suscipit. Morbi porta scelerisque leo. Ut semper sapien non nisl gravida, eu laoreet dui commodo. Integer semper augue sit amet turpis mattis, non scelerisque libero rhoncus. Sed vel libero eleifend, tincidunt purus ac, molestie quam. Mauris in blandit felis. . . . <span style="float: right" class="sample"><a href="?page=article">Read More</a></span></p>
  <hr width=90% class="sample" />
<h3 class="sample">Sample Article 3</h3>
<p class="sample">
  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque faucibus risus a risus gravida varius. Quisque porta congue felis, nec molestie nisi semper eu. Pellentesque ut ultrices ante, quis mattis mauris. Donec id condimentum nibh. Duis ut aliquet augue. Donec ornare ipsum rutrum sollicitudin mattis. Ut vel placerat dolor. . . . <span style="float: right" class="sample"><a href="?page=article">Read More</a></span></p>
  <hr width=90% class="sample" />
<h3 class="sample">Sample Article 4</h3>
<p class="sample">
Aenean cursus volutpat neque, nec fermentum tortor porttitor eu. Praesent rhoncus aliquet suscipit. Morbi porta scelerisque leo. Ut semper sapien non nisl gravida, eu laoreet dui commodo. Integer semper augue sit amet turpis mattis, non scelerisque libero rhoncus. Sed vel libero eleifend, tincidunt purus ac, molestie quam. Mauris in blandit felis. . . . <span style="float: right" class="sample"><a href="?page=article">Read More</a></span></p>

---->
