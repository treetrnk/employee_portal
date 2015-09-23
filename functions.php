<?php


   /////////////////////
  //  FILL SIDEBARS  //
 /////////////////////

function fillSidebar($start, $end, $field) {

  $sidebar = array();

  $dateunits = array (
    'year' => 31536000,
    'month' => 2592000,
    'week' => 604800,
    'day' => 86400,
    'hour' => 3600,
    'minute' => 60,
    'second' => 1
  );


  $oldestdate = time() - $start;
  $newestdate = time() + $end;
  
  $sql = "SELECT * FROM staff";
  if ($field == 'leaveday') { $sql .= " WHERE leaveday IS NOT NULL"; } 
    else { $sql .= " WHERE leaveday IS NULL"; }
  $result = mysql_query($sql);

  if ($result) {

//////  CREATE EMPLOYEE ARRAY  //////

    while ($staff = mysql_fetch_array($result)) {

      if ($field == 'anniversary') { $day = strtotime($staff['startday']); } 
        else { $day = strtotime($staff["$field"]); }

      if ($field == 'birthday' || $field == 'anniversary') {
        $day = strtotime(date('M j ', $day) . date('Y', time()));
      }
      
      if ($day > $oldestdate && $day < $newestdate) {

        if ($field == 'anniversary') { 
          $amount = strval(($day - strtotime($staff['startday'])) / $dateunits['year']);
          $staffdate = "<br />($amount" . "yrs - " . date('n/j)', strtotime($staff['startday']));
        } else {
          $staffdate = date('(n/j)', strtotime($staff["$field"]));
        }
            
        array_push($sidebar, "$staff[id] --  $staff[fname] --  $staff[lname] --  $staffdate");
      }
    }
  }

  if ($sidebar) {
    foreach ($sidebar as $i) {
      $employee = explode (' -- ', $i);
      $id = $employee[0];
      $fname = $employee[1];
      $lname = $employee[2];
      $day = $employee[3];
      echo "<li><a href='?page=profile&profileid=$id'>$fname $lname</a> $day</li>";
    }
  }
}


   ///////////////////
  //  LIMIT WORDS  //
 ///////////////////

function word_limit($string, $amnt) {
  $words = explode(" ", $string);
  return implode(" ", array_splice($words, 0, $amnt));
}


   ///////////////
  //  MESSAGE  //
 ///////////////

function message($message) {
  echo "<br /><div class='message'>$message</div><br />";
} 



   ////////////////////////////
  //  MULTIPAGE NAVIGATION  //
 ////////////////////////////

function multipageNav($limit, $total_rows) {
  global $_POST;
  global $_GET;
  global $page;
  $post = base64_encode(serialize($_POST)); //CONVERT POST ARRAY
  if (isset($_GET['offset'])) { $offset=$_GET['offset']; } else { $offset = 0; }

  $pages = $total_rows / $limit;            //FIND TOTAL PAGES NEEDED
  $pages_temp = round($pages);
  if ($pages > $pages_temp) { $pages = ($pages_temp + 1); } 

  if ($total_rows > $limit) {
    echo "<center>";
    if ($offset > 0) { 
      echo "<a href='?page=$page&offset=" . ($offset-$limit) . "&post=$post'>&laquo;Prev</a> &nbsp;"; 
    } else {
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }

    $i = 0;
    while ($pages > $i) {
      if (($offset/$limit)+1 == ($i+1)) {
        echo " " . ($i+1) . " ";
      } else {
        echo "&nbsp; <a href='?page=$page&offset=" . ($limit * $i) . "&post=$post'>" . ($i+1) . "</a> &nbsp;";
      }
      $i += 1;
    }
    
    if ($offset + $limit < $total_rows) { 
      echo "&nbsp; <a href='?page=$page&offset=" . ($offset+$limit) . "&post=$post'>Next&raquo;</a>"; 
    } else {
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    echo "</center>";
  }
}


   //////////////////////////////
  //  ARTICLE SECURITY CHECK  //
 //////////////////////////////

function hasPermission($type) {
  
  if ( isset($_SESSION['security']) ) {

    $s_admin        = strstr($_SESSION['security'], 'admin');
    $s_approve      = strstr($_SESSION['security'], 'approve');
    $s_article      = strstr($_SESSION['security'], 'article');
    $s_article_pend = strstr($_SESSION['security'], 'article_pend');
    $s_comment      = strstr($_SESSION['security'], 'comment');
    $s_comment_pend = strstr($_SESSION['security'], 'comment_pend');
    $s_event        = strstr($_SESSION['security'], 'event');
    $s_event_pend   = strstr($_SESSION['security'], 'event_pend');
    $s_help         = strstr($_SESSION['security'], 'help');
    $s_help_pend    = strstr($_SESSION['security'], 'help_pend');
    $s_job          = strstr($_SESSION['security'], 'job');
    $s_job_pend     = strstr($_SESSION['security'], 'job_pend');
    $s_profile      = strstr($_SESSION['security'], 'profile');
    $s_profile_pend = strstr($_SESSION['security'], 'profile_pend');

    if ( !strstr($type, '_pend') ) {
      if ($s_admin) { return TRUE; }
    }

    switch ($type) {
    
      case 'approve':
        if ($s_approve) { return TRUE; }
        break;

      case 'article':
        if ($s_article) { return TRUE; }
        break;
      case 'article_pend':
        if ($s_article_pend) { return TRUE; }
        break;

      case 'comment':
        if ($s_comment) { return TRUE; }
        break;
      case 'comment_pend':
        if ($s_comment_pend) { return TRUE; }
        break;
      
      case 'event':
        if ($s_event) { return TRUE; }
        break;
      case 'event_pend':
        if ($s_event_pend) { return TRUE; }
        break;
      
      case 'help':
        if ($s_help) { return TRUE; }
        break;
      case 'event_pend':
        if ($s_help_pend) { return TRUE; }
        break;
      
      case 'job':
        if ($s_job) { return TRUE; }
        break;
      case 'job_pend':
        if ($s_job_pend) { return TRUE; }
        break;

      case 'profile':
        if ($s_profile) { return TRUE; }
        break;
      case 'profile_pend':
        if ($s_profile_pend) { return TRUE; }
        break;
    }  
  
  }
  return FALSE;
}


   /////////////////
  //  ADD MYSQL  //
 /////////////////

function add_mysql($field) {
  foreach ($field as $i) {
    $mysql_text = "(";
    $mysql_text = $mysql_text . "$i, ";
  }
}



?>
