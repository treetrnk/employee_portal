<?php
session_start();
date_default_timezone_set('America/New_York');

$website = '192.168.1.64';

function add_mysql($field) {
  foreach ($field as $i) {
    $mysql_text = "(";
    $mysql_text = $mysql_text . "$i, ";
  }
}


//==============SET TIMEOUT==============
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900) && isset($_SESSION['id'])) {
      // last request was more than 30 minutes ago
  session_unset();     // unset $_SESSION variable for the run-time 
  session_destroy();   // destroy session data in storage
  $message = "Logged out due to inactivity.";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$todaystart = date('Y-m-d', time());
$todaystart = strtotime($todaystart . ' 00:00:00');



//==============CONNECT TO DATABASE==============

include "servers.php";

mysql_connect("$mysql_host", "$mysql_user", "$mysql_pass") or die("Cannot connect to database");
mysql_select_db("$mysql_db") or die("Cannot find database");



//==============CHECK FOR PAGE==============
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 'home';
}

//==============LIMIT WORDS FUNCTION==============
function word_limit($string, $amnt) {
  $words = explode(" ", $string);
  return implode(" ", array_splice($words, 0, $amnt));
}


//==============TIME ARRAY==============
$dateunits = array (
  'year' => 31536000,
  'month' => 2592000,
  'week' => 604800,
  'day' => 86400,
  'hour' => 3600,
  'minute' => 60,
  'second' => 1
);



//============== MESSAGE FUNCTION =============
function message($message) {
  echo "<div class='message'>$message</div><br /><br />";
} 



//==============MULTIPAGE NAVIGATION==============
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


//==============UNICODE NUMBER ARRAY==============
$unicnumbers = array (
  0 => '&#9450;',
  1 => '&#9312;',
  2 => '&#9313;',
  3 => '&#9314;',
  4 => '&#9315;',
  5 => '&#9316;',
  6 => '&#9317;',
  7 => '&#9318;',
  8 => '&#9319;',
  9 => '&#9320;',
  10 => '&#9321;',
  11 => '&#9322;',
  12 => '&#9323;',
  13 => '&#9324;',
  14 => '&#9325;',
  15 => '&#9326;',
  16 => '&#9327;',
  17 => '&#9328;',
  18 => '&#9329;',
  19 => '&#9330;',
  20 => '&#9331:;',
  21 => '&#9331:;+',
);


//==============AUTHENTICATE LOGIN==============
if ( isset($_POST['login']) && $_POST['login'] == "y") { 
  if ($_POST['user'] && $_POST['pass']) {
    $ldap_conn = ldap_connect($ldap_server2, 389) or die ("Could not connect to LDAP server.");
    $user = strtolower($_POST['user']);
    $pass = $_POST['pass'];
    $ldaplogin = "armgroup\\$user";
    if ($ldap_conn) {
      if ($bind = ldap_bind($ldap_conn, $ldaplogin, $pass)) {
        $sql = "SELECT * FROM staff WHERE username='$user'";
        $result = mysql_query($sql);
        $count = mysql_num_rows($result);
        $message = 'Login Successful!';
        if ($count == 1) {
          $userdata = mysql_fetch_array($result);
          $_SESSION['id'] = $userdata['id'];
          $_SESSION['user'] = $userdata['username'];
          $_SESSION['fname'] = $userdata['fname'];
          $_SESSION['lname'] = $userdata['lname'];
          $_SESSION['email'] = $userdata['email'];
          $_SESSION['security'] = $userdata['security'];
        } else {
          $fname = ucfirst(substr($user, 0, 1));
          $lname = ucfirst(substr($user, 1));
          $email = "$user@armgroup.net";
          $security = "comment article_pend profile";
          $sql = "INSERT INTO staff (username, fname, lname, email, security)  VALUES ('$user', '$fname', '$lname', '$email', '$security')";
          $userdata = mysql_query($sql);
          if ($userdata) {
            $userid = mysql_insert_id();
            $_SESSION['id'] = $userid;
            $_SESSION['user'] = $user;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['security'] = $security;
            $message = "New user added. Please update your <a href='?page=profile&profileid=$userid'>profile</a>!";
          } else {
            $message = mysql_error();
          }
        }
        $page = 'home';
      } else {
        $page = "login";
        $message = $message . "Wrong username and/or password.<br /><br />";
      }
    } else {
      $message =  "LDAP did not connect.";
    } 

    /* $pass = sha1($user.mysql_real_escape_string(stripslashes($_POST['pass'])));
    $user = mysql_real_escape_string(stripslashes($_POST['user']));
    $sql = "SELECT * FROM staff WHERE username='$user' AND password='$pass'";
    $result = mysql_query($sql);
    $count = mysql_num_rows($result); */

  } else {
    $page = "login";
    $message = "You did not provide<br /> your username and/or  password.<br /><br />";
  }
} 

//==============LOGOUT SCRIPT==============
if (isset($_GET['logout'])) {                                                      //LOGOUT SCRIPT
  $_SESSION = array();
  session_destroy();
}

if ( $page == 'event' && isset($_GET['date']) ) {                            //EVENTS - REDIRECT IF ONLY 1 RESULT
  $date = strtotime($_GET['date'] . ' 00:00:00');
  $endofdate = $date + $dateunits['day'] - 1; 
  $fulldate = date('l, M. j, Y', strtotime($_GET['date']));
  $sql = "SELECT * FROM articles WHERE type = 'event' AND startdate BETWEEN '$date' AND '$endofdate'";
  $result = mysql_query($sql); 
  if (mysql_num_rows($result) == 1) {
    $row = mysql_fetch_array($result);
    $page = 'article';
    $_GET['articleid'] = $row['id'];
  }
}





//============ARTICLE SECURITY CHECK FUNCTION================
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




//==============ADD OR EDIT STUFF==============
if (isset($_POST['submit'])) {                      
  $submit = $_POST['submit'];
  if (isset($_POST['type'])) {
    $type = $_POST['type'];


    function pendingResponse($id) {
      if ($_POST['submit'] == 'Approve') {
        $verb = "APPROVED";
        $line2 = "To view your $_POST[type] please follow this link: $website/index.php?page=article&articleid=$id"; 
      } elseif ($_POST['submit'] == 'Reject') {
        $verb = "REJECTED";
        $line2 = "If you have any questions, please direct them to $_SESSION[email].";
      }
      $to = $_POST['useremail'];
      $subject = "ARM PORTAL - Submission #$_POST[id] has been $verb";
      $body = "
        The following submission to ARM PORTAL was $verb by $_SESSION[fname] $_SESSION[lname].
        $line2


        Your submission:
            
            TITLE:
                    $_POST[title]

            BODY:
                    " . strip_tags($_POST['body'])
      ;
      $headers = 'From: helpdesk@armgroup.net' . "\r\n" .
        'Reply-To: ' . $_SESSION['email'] . "\r\n" .  
        'X-Mailer: PHP/' . phpversion();

      mail($to, $subject, $body, $headers);

    }



    switch ($type) {                                ////////////////
                                                   //    STAF    //
      case 'employee':                            //////////////// 
        $table = "staff";    
        if ( hasPermission('profile_pend') ) { $table = "staffPending"; $submit = 'add'; $message = $table; }

        if (isset($_POST['fname']) && isset($_POST['lname']) && $_POST['birthday']) { //CHECK REQUIRED FIELDS
          extract($_POST, EXTR_OVERWRITE);
          $username = strtolower(substr($fname, 0, 1) . $lname);
          $birthday = date('Y-m-d', strtotime($birthday));
          $startday = date('Y-m-d', strtotime($startday));
          $description = "";
          foreach ($_POST['description'] as $i) {
            if ($description != "") { $description .= ' -- '; }
            $description .= $i;
          }
          if ($email == false) {$email = $username . '@armgroup.net';}


          switch ($_POST['submit']) {

            case 'add':   ////////////////  ADD  ////
    
              $password = sha1($username . $lname . date("md", $birthday));
              $emp_sql = "INSERT INTO $table (username, password, fname, lname, initials, birthday, startday, location, title, email, phone, ext, cellphone, picture, description, about, security, updateid) VALUES ('$username', '$password', '$fname', '$lname', '$initials', '$birthday', '$startday', '$location', '$title', '$email', '$phone', '$ext', '$cellphone', '$picture', '$description', '$about', '1', '$profileid' )"; 
              break;


            case 'edit':   ///////////////  EDIT  ////

              if ($_GET['profileid']) {
                $profileid = $_GET['profileid'];
                $emp_sql = "UPDATE $table SET username='$username', fname='$fname', lname='$lname', initials='$initials', birthday='$birthday', showbday='$showbday', startday='$startday', location='$location', title='$title', email='$email', phone='$phone', ext='$ext', cellphone='$cellphone', picture='$picture', description='$description', about='$about' WHERE id=$profileid";
              } else {
                $message .= 'No profile provided.<br />';
              }
              break;


            default:  ////////////////  DEFAULT  ////

              $message .= "Submit is not set. <br /> Submit = " . $submit . ".<br />";
          }
            

          if (mysql_query($emp_sql)) { // IF SUCCESS
            if (!$_GET['profileid']) { $_GET['profileid'] = mysql_insert_id(); }
            $message .= "Profile " . $submit . "ed successfully!!!";
          } else { 
            $message .= "There was a problem.<br />" . mysql_error() . ".";
          }
        } else { //IF REQUIRED FIELDS ARE EMPTY
          $page = "edit";
          $message = "Please fill all required fields.";
        }

      break;
      case 'resources':
      case 'job':                           ////////////////////
      case 'event':                        //    ARTICLES    // 
      case 'article':                     ////////////////////
      case 'help':

        $table = "articles";
       
          if ( hasPermission($type . "_pend") ) {$table = 'articlesPending'; $submit = "add"; }

          if (isset($_POST['title']) && isset($_POST['body']) && hasPermission($type)) { //CHECK REQUIRED FIELDS
            extract($_POST, EXTR_OVERWRITE);
            $userid = $_SESSION['id'];
            $startdate = strtotime($startdate);
            $enddate = strtotime($enddate);
            $date = time();
            

            switch ($_POST['submit']) {

              case 'add':   ///////////////  ADD  ////
               
                $art_sql = "INSERT INTO $table (title, body, userid, date, startdate, enddate, location, type, del) VALUES ('$title', '$body', '$userid', '$date', '$startdate', '$enddate', '$location', '$type', 'n')";
                $_GET['articleid'] = mysql_insert_id();
                $verb = "added";
                break;

                
              case 'edit':  //////////////  EDIT  ////

                if ($_GET['articleid']) {
                  $articleid = $_GET['articleid'];
                  $art_sql = "UPDATE articles SET title='$title', body='$body', startdate='$startdate', enddate='$enddate', type='$type' WHERE id=$articleid";
                }
                $verb = "edited";
                break;


              case 'Approve':   //////////////  APPROVE  ////

                $sql = "UPDATE articlesPending SET del = 'y' WHERE id = $_POST[id]";
                if (mysql_query($sql)) {
                  $art_sql = "INSERT INTO $table (title, body, userid, date, startdate, enddate, location, type, del) VALUES ('$title', '$body', '$penduserid', '$date', '$startdate', '$enddate', '$location', '$type', 'n')";
                  $_GET['articleid'] = mysql_insert_id();
                  pendingResponse($_GET['articleid']);
                }
                $verb = "approved";
                break;


              case 'Reject':  //////////////////  REJECT  ////

                pendingResponse($_POST['id']);
                $art_sql = "UPDATE articlesPending SET del = 'y' WHERE id = $_POST[id]";
                $verb = "rejected";
                break;

              
              case 'delete':  /////////////////  DELETE  ////
            
                //add delete sql
                break;


              default:        //////////////////  DEFAULT  ////
             
                $message .= 'No profile provided.<br />';
                $message .= "Submit is not set. <br /> Submit = " . $submit . ".<br />";
              }
            
            
            if (mysql_query($art_sql)) { // IF SUCCESS
              if (!$_GET[articleid]) { $_GET[articleid] = mysql_insert_id(); }
              $message .= ucfirst($type) . " " . $verb . " successfully!!!";
            } else { 
              $message .= "There was a problem.<br />" . mysql_error() . ".";
            }
          } else { //IF REQUIRED FIELDS ARE EMPTY
            $message = "Please fill all fields.";
          }

      break;
    }
  }
}




//============== EMAIL FEEDBACK AND TICKETS ===============
if (isset($_SESSION['security'])) {
  if (isset($_POST['submit']) && $_POST['submit'] == "Send") { 
    if (isset($_POST['subject']) && isset($_POST['body'])) {
      if ($_POST['type'] == 'feedback') {
        $subject = "ARM PORTAL - Feedback - $_POST[subject]";
      } elseif ($_POST['type'] == 'ticket') {
        $subject = $_POST['subject'];
      }
      
      $to = "helpdesk@armgroup.net";
      $body = $_POST['body'];
      if ($_POST['type'] == 'ticket') { 
        $body .= "


          Sent from ARM PORTAL
        ";
      }
      $headers = 'From: ' . $_SESSION['email'] . "\r\n" . 
        'Reply-To: ' . $_SESSION['email'] . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

      mail ($to, $subject, $body, $headers);
      $message = ucfirst($_POST['type']) . " submitted successfully!";


    } else {
      $page = "email";
      $_GET['type'] = $_POST['type'];
      $message = "Please fill out all fields.";

    }
  }
}




//============== FILL SIDEBARS FUNCTION ================
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




//==============USED FOR SAMPLE CONTENT==============
$_GET['sample'] = 'y';

?>
