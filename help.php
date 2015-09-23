<?php 
  
  echo "
    <h2>I.T. Support</h2>
    <br />
  ";

  if ( isset($_SESSION['security']) ) {
    echo "
      <table width=100%>
        <tr>
          <td>
            <div class='greenbox' style='width:300px;' align='center'>
              <a href='?page=email&type=ticket' style='padding:20px; font-size:18px; color:#ffffff;'>Submit a Help Desk Ticket</a>
            </div>
          </td>
          <td>
            <div class='greenbox' style='width:300px;' align='center'>
              <a href='?page=email&type=ticket' style='padding:20px; font-size:18px; color:#ffffff;'>Submit ARM Portal Feedback</a>
            </div>
          </td>
        </tr>
      </table>
      <br /><br />
  ";
  }

  $sql = "SELECT * FROM articles WHERE type = 'help' ORDER BY title";
  $result = mysql_query($sql);
  if ($result) {

    echo "<div style='width: 700px; margin-right: auto; margin-left: auto;'>";
    
    if ( hasPermission('help') ) {
      echo "
        <form name='wilbur' method='post' action='index.php?page=article'>
          <input type='hidden' name='action' value='add' />
          <input type='hidden' name='type' value='help' />
          <input type='submit' value='New Help Article' style='float:right' />
        </form>
      ";
    }

    echo "
      <h3>I.T. Help Articles</h3>
      <ul>
    ";

    while ($row = mysql_fetch_array($result)) {
      echo "<li class='sample' style='line-height: 25px;'><a href='?page=article&articleid=$row[id]'>$row[title]</a></li>";
    }
    echo "
      </ul>
      </div>
    ";
  }
?>

