<?php

  if ($_GET['m'] && $_GET['y']) {
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

  echo '<table width=100% cellspacing="0" class="calendar">';
    echo '<tr bgcolor=#72aaff>';
      echo '<td><a style="text-decoration:none;color:#000000;" href="?page=home&m=' . $prevm . '&y=' . $prevy . '">&#9668;</a></td>';
      echo '<td colspan="5" align="center"><b>' . $month . ' ' . $y . '</b></td>';
      echo '<td align="right"><a style="text-decoration:none;color:#000000;" href="?page=home&m=' . $nextm . '&y=' . $nexty . '">&#9658;</a></td>';
    echo '</tr>';
    echo '<tr style="border-top: 1px solid #999999;" bgcolor=#aaccff>';
      echo '<td align="center"><b>S</b></td>';
      echo '<td align="center"><b>M</b></td>';
      echo '<td align="center"><b>T</b></td>';
      echo '<td align="center"><b>W</b></td>';
      echo '<td align="center"><b>T</b></td>';
      echo '<td align="center"><b>F</b></td>';
      echo '<td align="center"><b>S</b></td>';
    echo '</tr>';
    echo '<tr>';
    
      $counter = 1;
      $dayofweek = date('w', $caldate);
      $dayofmonth = date('j', $caldate);
      while ($dayofmonth <= $total_days) {          //START FILLING DAYS
        if ($dayofweek == 7) {
          $dayofweek = 0;
          echo '</tr>';
          echo '<tr>';
        }

        if ($dayofmonth == 1) {                                       //PREVIOUS MONTH DAYS
          $prevdate = $caldate - $dateunits['month']; 
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
        $endofday = strtotime($y . '-' . $m . '-' . $dayofmonth . ' 23:59:59');           //CURRENT MONTH DAYS
        $formated = date('Y-m-d', $caldate);
        $today = date('Y-m-d', time());
        $sql = "SELECT * FROM articles WHERE type = 'event' AND startdate BETWEEN '$caldate' AND '$endofday'";
        $result = mysql_query($sql); 
          if (mysql_num_rows($result) > 0) { 
            echo '<td class="cal-event" align="center">';
            if ($formated == $today) { echo "<b>"; }
            echo "<a href='?page=event&date=$formated'>$dayofmonth</a>";
            if ($formated == $today) { echo "</b>"; }
          } else {
            echo '<td class="cal-normal" align="center">';
            if ($formated == $today) { echo "<b>"; }
            echo $dayofmonth;
            echo mysql_error($result);
            if ($formated == $today) { echo "</b>"; }
          }
        echo '</td>'; 
        $dayofmonth++;
        $dayofweek++;
        $counter++;
      }  
        echo "<td>$today</td>";
      $tempday = 1;
      while ($dayofweek < 7) {                                                          //NEXT MONTH DAYS
        $nextdate = $caldate + $dateunits['month'];
        echo '<td class="cal-other" align="center">' . $tempday  . '</td>';
        $dayofweek++;
        $tempday++;
      }
    
    echo '</tr>';
  echo '</table>';


?>
