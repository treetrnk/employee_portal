<?php

  if ( hasPermission('approve') ) {

    if (isset($_GET['pendpage'])) {
      $pendpage = $_GET['pendpage'];
    } else {
      $pendpage = "articles";
    }
    
    $limit = 5;
      
    /////////////  ARTICLE DATA  ////////////

    $sql = "SELECT * FROM articlesPending WHERE del = 'n'";
    $article_num = mysql_num_rows(mysql_query($sql));
    if ( $pendpage == 'articles' ) { $total_rows = $article_num; }
    if ( $article_num > 20 ) { $article_num = 21; } 
    $sql .= " LIMIT $limit";
    if (isset($_GET['offset'])) { $sql .= " OFFSET $_GET[offset]"; }
    $article_result = mysql_query($sql);
    if ($article_result) {} else { echo mysql_error(); }

    /////////////  STAFF DATA  ////////////
/*
    $sql = "SELECT * FROM staffPending WHERE del = 'n'";
    $staff_num = mysql_num_rows(mysql_query($sql));
    if ( $pendpage == 'staff' ) { $total_rows = $staff_num; }
    if ( $staff_num > 20 ) { $staff_num = 21; }  
    $sql .= " LIMIT $limit";
    if (isset($_GET['offset'])) { $sql .= " OFFSET $_GET[offset]"; }
    $staff_result = mysql_query($sql);
    if ($staff_result) {} else { echo mysql_error(); }
 */
      ///////////////////////
     //  PAGE BODY START  //
    ///////////////////////

    echo "
      <table width=80% border='0' align='center'>
        <tr>
          <td align='center' width=50%>
            <h2>";
              if ($pendpage == "articles") {
                echo "Pending Articles";
              } else {
                echo "<a href='?page=pending&pendpage=articles'>Articles</a>";
              }
              echo " <span style='color: #ff0000;'>$unicnumbers[$article_num]</span>
            </h2>
      ";
      if ($pendpage == "articles") { echo "<hr width=100%>"; }
      
/*              //////// STAFF HEADER /////////
      echo "
          </td>
          <td align='center' width=50%>
            <h2>
      ";

      if ($pendpage == "staff") {
        echo "Staff";
      } else {
        echo "<a href='?page=pending&pendpage=staff'>Staff</a>";
      }
      echo " <span style='color: #ff0000;'>$unicnumbers[$staff_num]</span>
        </h2>";  */
      if ($pendpage == "staff") { echo "<hr width=100%>"; }

    echo "</td></tr></table>";


    if ( $pendpage == 'articles' ) { multiPageNav($limit, $article_num); } else { multiPageNav($limit, $staff_num); }


        ////////////////////////
       //  ARTICLES PENDING  //
      ////////////////////////

    if ($pendpage == "articles") { 
      $count = 1;
      while ($row = mysql_fetch_array($article_result)) {

        $count ++;

        $sql = "SELECT * FROM staff WHERE id = $row[userid] AND del = 'n'";
        $result = mysql_query($sql);
        $userinfo = mysql_fetch_array($result);
        
        if ( isset($row['startdate']) ) { $startdate = date('Y-m-d h:i:s', $row['startdate']); } 
        if ( isset($row['enddate']) ) { $enddate = date('Y-m-d h:i:s', $row['enddate']); } 
        $type = ucfirst($row['type']);
        echo "
          <div class='people-list' "; if ( $count % 2 == 0 ) { echo "style='background: #dedede;'"; } echo ">  
            
            <form method='post' action='index.php?page=$page&pending=y'>
            <table width=90% border=0 cellpadding=5>
              <tr>
                <th>
                  Author:
                </th>
                <td width=90% >
                  <input type='hidden' name='useremail' value='$userinfo[email]' />
                  <input type='hidden' name='penduserid' value='$row[userid]' />
                  $userinfo[fname] $userinfo[lname]
                </td>
              </tr>
              <tr>
                <th>
                  Type:
                </th>
                <td width=90% >
                  <select name='type'>
                    <option value='article'"; if ($row['type'] == 'article') { echo ' selected'; } echo ">Article</option>
                    <option value='event'"; if ($row['type'] == 'event') { echo ' selected'; } echo ">Event</option>
                    <option value='help'"; if ($row['type'] == 'help') { echo ' selected'; } echo ">I.T. Help</option>
                    <option value='job'"; if ($row['type'] == 'job') { echo ' selected'; } echo ">Job Listing</option>
                  </select>
                </td>
              </tr>
              <tr>
                <th>
                  Title: 
                </th>
                <td>
                  <input type='text' name='title' value='$row[title]' size=100% />
                </td>
              </tr>
              <tr>
                <th>
                  Location: 
                </th>
                <td>
                  <select name='location'>
                    <option value='' default>Choose one...</option>
                    <option value='Canonsburg, PA'"; 
                      if ($row['location'] == 'Canonsburg, PA') { echo ' selected'; } 
                      echo ">Canonsburg, PA</option>
                    <option value='Canonsburg, PA'"; 
                      if ($row['location'] == 'Columbia, MD') { echo ' selected'; } 
                      echo ">Canonsburg, PA</option>
                    <option value='Columbia, MD'"; 
                      if ($row['location'] == 'Hershey, PA') { echo ' selected'; } 
                      echo ">Hershey, PA</option>
                    <option value='Canonsburg, PA'"; 
                      if ($row['location'] == 'State College, PA') { echo ' selected'; } 
                      echo ">State College, PA</option>
                    <option value='Canonsburg, PA'"; 
                      if ($row['location'] == 'Wilkes-Barre, PA') { echo ' selected'; } 
                      echo ">Wilkes-Barre, PA</option>
                  </select> (job listings only)
                </td>
              </tr>
              <tr>
                <th>
                  Date/Time: 
                </th>
                <td>
                  Start: <br />
                  <input type='text' name='startdate' id='datepicker$count' value='$startdate' placeholder='(events only)' /> ";
                    timePicker($row, 'startdate'); echo "
                  <br />
                  End: <br />
                  <input type='text' name='enddate' id='datepicker1$count' value='$enddate' placeholder='(events only)' /> ";
                    timePicker($row, 'enddate'); echo "
                </td>
              </tr>
              <tr>
                <th>
                  Body: 
                </th>
                <td>
                  <textarea id='body$count' name='body'>$row[body]</textarea>
                  <script>
                    CKEDITOR.replace('body$count');
                  </script>
                </td>
              </tr>
              <tr>
                <td align='center' colspan='2'>
                  <input type='hidden' name='id' value='$row[id]' />
                  <input type='hidden' name='date' value='$row[date]' />
                  <input type='hidden' name='date' value='$row[date]' />
                  <input type='hidden' name='count' value='$count' />
                  
                  <input type='submit' name='submit' value='Approve' /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  <input type='submit' name='submit' value='Reject' />
                </td>
              </tr>
            </table>
            </form>

          </div>    
         ";
                 
      }


  
  
    } else {

        ////////////////////////
       //    STAFF PENDING   //
      ////////////////////////
    
    }

  }

  if ( $pendpage == 'articles' ) { multiPageNav($limit, $article_num); } else { multiPageNav($limit, $staff_num); }



?>
