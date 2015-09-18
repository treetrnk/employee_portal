<?php

  if (isset($_GET['m']) && isset($_GET['y'])) {
    $m = $_GET['m'];
    $y = $_GET['y'];
    $caldate = $y . '-' . $m . '-01';
    $caldate = strtotime($caldate);
  } else {
    $m = date('n', time());
    $y = date('Y', time());
    $caldate = $y . '-' . $m . '-01';
    $caldate = strtotime($caldate);
  }

  $month = date('F', $caldate);
  $total_days = date('t', $caldate);
  $nextdate = $caldate + 32 * $dateunits['day'];
  $nextm = date('n', $nextdate);
  $nexty = date('Y', $nextdate);
  $prevdate = $caldate - 10 * $dateunits['day'];
  $prevm = date('n', $prevdate);
  $prevy = date('Y', $prevdate);
  /*
  if ($m == 12) {
    $nextm = 1;
    $nexty = $y+1;
  } else{
    $nextm = $m+1;
    $nexty = $y;
  }
  if ($m == 1) {
    $prevm = 12;
    $prevy = $y-1;
  } else{
    $prevm = $m-1;
    $prevy = $y;
  }
*/

  echo '<table width=100% cellspacing="0" class="calendar">';
    echo '<tr bgcolor=#72aaff>';
      echo '<td><a style="text-decoration:none;color:#000000;" href="?page=home&m=' . $prevm . '&y=' . $prevy . '">&#9668;</a></td>';
      echo "<td colspan='5' align='center'><a style='text-decoration:none;color:#000000;' href='?page=home'><b>$month $y</b></td>";
      echo "<td align='right'><a style='text-decoration:none;color:#000000;' href='?page=home&m=$nextm&y=$nexty'>&#9658;</a></td>";
    echo '</tr>';
    echo '<tr style="border-top: 1px solid #999999;" bgcolor=#aaccff>';
      echo '<td align="center"><b>Su</b></td>';
      echo '<td align="center"><b>Mo</b></td>';
      echo '<td align="center"><b>Tu</b></td>';
      echo '<td align="center"><b>We</b></td>';
      echo '<td align="center"><b>Th</b></td>';
      echo '<td align="center"><b>Fr</b></td>';
      echo '<td align="center"><b>Sa</b></td>';
    echo '</tr>';
    echo '<tr>';
    
      $dayofweek = date('w', $caldate);
      $dayofmonth = 1;
      while ($dayofmonth <= $total_days) {          //START FILLING DAYS
        if ($dayofweek == 7) {
          $dayofweek = 0;
          echo '</tr>';
          echo '<tr>';
        }

        if ($dayofmonth == 1) {                                       //PREVIOUS MONTH DAYS
          $prevtotal = date('t', $prevdate);
          $daysbefore = $dayofweek;
          while ($daysbefore > 0) {
            $tempday = $prevtotal - $daysbefore + 1;
            $prevdate = date('Y-m-d', strtotime($prevy . '-' . $prevm . '-' . $tempday));
            $sql = "SELECT * FROM articles WHERE type = 'event' AND eventdate BETWEEN '$prevdate 00:00:00' AND '$prevdate 23:59:59'";
            echo '<td class="cal-other" align="center">' . $tempday . '</td>';
            $daysbefore--; 
          } 
        }  
        $caldate = strtotime($y . '-' . $m . '-' . $dayofmonth . ' 00:00:00');           //CURRENT MONTH DAYS
        $endofday = $caldate + $dateunits['day'] - 1;           
        $formatted = date('Y-m-d', $caldate);
        $today = date('Y-m-d', time());
        $sql = "SELECT * FROM articles WHERE type = 'event' AND startdate BETWEEN '$caldate' AND '$endofday'";
        $result = mysql_query($sql); 
          if (mysql_num_rows($result) > 0) { 
            echo '<td class="cal-event" align="center">';
            echo "<a href='?page=event&date=$formatted'"; 
            if ($formatted == $today) { echo 'style="border: 1px solid #454545;border-radius: 3px;"'; }
            echo ">$dayofmonth</a>";
          } else {
            echo '<td class="cal-normal" align="center">';
            if ($formatted == $today) { echo '<span style="border: 1px solid #454545;border-radius: 3px;">'; }
            echo $dayofmonth;
            //echo mysql_error($result);
            if ($formatted == $today) { echo '</span>'; }
          }
        echo '</td>'; 

        $dayofmonth++;
        $dayofweek++;
      }  
      $tempday = 1;
      while ($dayofweek < 7) {                                                          //NEXT MONTH DAYS
        echo '<td class="cal-other" align="center">' . $tempday  . '</td>';
        $dayofweek++;
        $tempday++;
      }
    
    echo '</tr>';
  echo '</table>';


?>
