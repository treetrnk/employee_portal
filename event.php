<?php
  echo "<div style='float: right;'>";
  include('mini-event-calendar/calendar.php');
  echo "</div>";
  echo "<h2>Events</h2>";
  if ($_GET['date']) {
    $date = strtotime($_GET['date'] . ' 00:00:00');
    $endofdate = $date + $dateunits['day'] - 1;
    $fulldate = date('l, M. j, Y', strtotime($_GET['date']));
    $sql = "SELECT * FROM articles WHERE type = 'event' AND del = 'n' AND startdate BETWEEN '$date' AND '$endofdate' ORDER BY startdate ASC";
    $result = mysql_query($sql); 
    if ($result) { 
      echo "<h3>" . $fulldate . "</h3>";
      echo '<ul style="list-style-type: none;">';
      while ($row = mysql_fetch_array($result)) {
        $userids = $row['userid'];
        $usersql = mysql_query("SELECT * FROM staff WHERE id = '$userids'");
        $userinfo = mysql_fetch_array($usersql);
        echo "<li>";
          echo "<b><a href='?page=article&articleid=" . $row['id'] . "'>" . $row['title'] . "</a>";
          echo "<br />";
          echo date('g:i a', $row['startdate']) . "</b>";
          echo "<br />";
          echo "<span style='font-size: 8pt;'>By: <a class='hidelink' href='?page=profile&profileid=" . $row['userid'] . "'>" . $userinfo['fname'] . " " . $userinfo['lname'] . "</a></span>";
          echo "<br />";
          echo "<br />";
        echo "</li>";
      }
      echo "</ul>";
    } else {
      echo 'Fail: ' . mysql_error();
    }
  } else {
    echo "<h3>Event Not Found!</h3>";
  }
?>
