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
  if ($field != 'leaveday') { $sql .= " WHERE leaveday IS NULL"; }
  $result = mysql_query($sql);

  if ($result) {

//////  CREATE EMPLOYEE ARRAY  //////

    while ($staff = mysql_fetch_array($result)) {

      if ($field == 'anniversary') { $day = strtotime($staff['startday']); } 
        else { $day = strtotime($staff["$field"]); }

      if ($field == 'birthday' || $field == 'anniversary') {
        $day = strtotime(date('M j ', $day) . date('Y', time()));
      }
      

//////  PUSH TO ARRAY  //////

      if ($day > $oldestdate && $day < $newestdate) {

        if ($field == 'anniversary') { 
          $amount = strval(($day - strtotime($staff['startday'])) / $dateunits['year']);
          $staffdate = "<br />($amount" . "yrs - " . date('n/j)', strtotime($staff['startday']));

          if ($amount > 0) {
            array_push($sidebar, "$staff[id] --  $staff[fname] --  $staff[lname] --  $staffdate");
          }

        } else {
          $staffdate = date('(n/j)', strtotime($staff["$field"]));
          array_push($sidebar, "$staff[id] --  $staff[fname] --  $staff[lname] --  $staffdate");
        }
            
      }
    }
  }

//////  DISPLAY RESULTS  //////

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

    $permissions = array( // ----LIST AVAILABLE PERMISSIONS----
      'admin',            // All Permissions
      'approve',          // Approve or reject pending items
      'article',          // Add articles
      'article_pend',     // Add articles that require approval
      'comment',          // Add comments
      'comment_pend',     // Add comments that require approval
      'event',            // Add events
      'event_pend',       // Add events that require approval
      'help',             // Add help articles
      'help_event',       // Add help articles that require approval
      'job',              // Add jobs
      'job_pend',         // Add jobs that require approval
      'profile',          // Edit your profile
      'profile_pend'      // Edit your profile, changes require approval
    );

    $hasPerm = array();

    //Fill hasPerm array with values, TRUE or FALSE, for each permission
    
    foreach ($permissions as $i) {
      if (strstr($_SESSION['security'], "$i")) {
        $hasPerm["$i"] = TRUE;
      } else {
        $hasPerm["$i"] = FALSE;
      }
    }

    // If user has admin permission and the function call is not checking for '*_pend'
    // return true. Otherwise, return true if type is true in $hasPerm array.

    if ( $hasPerm['admin'] ) {
      if ( !strstr($type, '_pend') ) {
        return TRUE;
      }
    } elseif ( $hasPerm["$type"] ) {
      return TRUE;
    } else {
      return FALSE;
    }
    
  } 
  return FALSE;
}


   /////////////////
  //  ADD MYSQL  //
 /////////////////

// I started building this function to build sql statements for me
// I abandoned it because it may be more work than it is worth to 
// make it modular.

function add_mysql($field) {
  foreach ($field as $i) {
    $mysql_text = "(";
    $mysql_text = $mysql_text . "$i, ";
  }
}


   /////////////////////
  //  VALIDATE DATA  //
 /////////////////////

function validate($data, $required) {
  $post = array();
  foreach ($data as $key => $value) {

////////////  DATES

    if ($key == 'startday' || $key == 'birthday' || $key == 'leaveday' || $key == 'startdate' || $key == 'enddate') {
      $arr = split('/', $value);
      $y = $arr[0];
      $m = $arr[1];
      $d = $arr[2];
      $thisyear = date('Y', time());
      if ($key == 'birthday' && $y >= $thisyear) { 
        message("Invalid $key."); 
        return FALSE;
      }
      if (!checkdate($m,$d,$y)) {
        message("Invalid $key");
        return FALSE;
      }
      if ($value != ''){
        if ($key == 'startdate') {
          $value  = "$value $data[hour_startdate]:$data[minute_startdate] $data[ampm_startdate]";
          $value = strtotime($value);
        } elseif ($key == 'enddate') {
          $value  = "$value $data[hour_enddate]:$data[minute_enddate] $data[ampm_enddate]";
          $value = strtotime($value);
        } else {
          $value = date('Y-m-d', strtotime($value));
        }
      }
    }

////////////  PHONES

    if ($key == 'phone' || $key == 'cellphone') {
      if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $value)) {
        message("Invalid $key number. <br /> Please use the following format: 000-000-0000");
        return FALSE;
      }
    }

////////////  EXTENSION

    if ($key == 'ext') {
      if (!preg_match("/^[0-9]{4}$/", $value)) {
        message("Invalid extension. <br /> Please use the following format: 0000");
        return FALSE;
      }
    }

////////////  EMAIL

    if ($key == 'email') {
      if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        message("Invalid email address.");
        return FALSE;
      }
    }

////////////  REQUIRED

    if (in_array($key, $required)) {
      if ( $value == '' ) {
        message("Please fill in all required fields.");
        return FALSE;
      }
    }

    if ( $key != '' && $value != '' ) {
      $post[$key] = mysql_real_escape_string($value);
    }

  }
  return $post;
}


   //////////////////////////////
  //  GENERATE SQL STATEMENT  //
 //////////////////////////////

function sqlgen($post) {

  $avoidKeys (
    "submit",
    "hour_startdate",
    "hour_enddate",
    "minute_startdate",
    "minute_enddate",
    "ampm_startdate",
    "ampm_enddate"
  );

  switch ($post['submit']) { 

////////// ADD
    case 'add':
      $sql = "INSERT INTO $table (";
      foreach ($post as $key => $value) {
        if (!in_array($key, $avoidKeys)) {
          $sql .= "$key, ";
        }
      }
      $sql .= ") VALUES (";
      foreach ($post as $key => $value) {
        if (!in_array($key, $avoidKeys)) {
          $sql .= "'$value', ";  
        }
      $sql .= ")";
      }
      break;

//////////  EDIT
    case 'edit':
      $sql = "UPDATE $table SET ";
      foreach ($post as $key => $value) {
        if (!in_array($key, $avoidKeys)) {
          $sql .= "$key='$value', ";
        }
      }
      break;

//////////  DELETE
    case 'del':
      $sql = "UPDATE $table SET del = 'y'";
      break;

//////////  DEFAULT
    default:

  }

  return $sql;

}


   ///////////////////
  //  TIME PICKER  //
 ///////////////////

function timePicker($post, $field) { 
  echo "<select name='hour_$field' class='time'>";
    $count = 0;
    while ($count < 12) {
      $count++;
      if (date('h', $post["$field"]) == $count) {
        echo "<option value='$count' selected>$count</option>";
      } else {
        echo "<option value='$count'>$count</option>";
      }
    }
  echo "
    </select>
    <b>:</b>
    <select name='minute_$field' class='time'>
      <option value='00'"; if (date('i', $post["$field"]) == '00') { echo " selected"; } echo ">00</option>
      <option value='15'"; if (date('i', $post["$field"]) == '15') { echo " selected"; } echo ">15</option>
      <option value='30'"; if (date('i', $post["$field"]) == '30') { echo " selected"; } echo ">30</option>
      <option value='45'"; if (date('i', $post["$field"]) == '45') { echo " selected"; } echo ">45</option>
    </select>
    <select name='ampm_$field' class='time'>
      <option value='am'"; if (date('a', $post["$field"]) == 'am') { echo " selected"; } echo ">AM</option>
      <option value='pm'"; if (date('a', $post["$field"]) == 'pm') { echo " selected"; } echo ">PM</option>
    </select>
  ";
}


   ////////////////////
  //  IMAGE UPLOAD  //
 ////////////////////

function imageUpload($user) {
  
  $allowed_exts = array(
    ".jpg",
    ".JPG",
    ".png",
    ".PNG",
    ".jpeg",
    ".JPEG"
  );

  $filename = $_FILES['picture']['name'];
  $file_ext = substr($filename, strpos($filename, '.'), strlen($filename)-1); 
  if (!in_array($file_ext, $allowed_exts)) {
    echo "Invalid File Extension";
    return FALSE;
  }
  var_dump($_FILES);
  $target_dest = "staff/$user$file_ext";
  if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_dest)) {
    return TRUE;
  } else {
    return FALSE;
  }
}

?>
