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


   //////////////////////
  //  SECURITY CHECK  //
 //////////////////////

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
      'profile_pend',     // Edit your profile, changes require approval
      'resources'         // Edit the resources page
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
        return FALSE;
      }
      if (!checkdate($m,$d,$y)) {
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
        return FALSE;
      }
    }

////////////  EXTENSION

    if ($key == 'ext') {
      if (!preg_match("/^[0-9]{4}$/", $value)) {
        return FALSE;
      }
    }

////////////  EMAIL

    if ($key == 'email') {
      if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return FALSE;
      }
    }

////////////  REQUIRED

    if (in_array($key, $required)) {
      if ( $value == '' ) {
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

  $results = array();
  $results['success'] = FALSE;

  $filename = str_replace(' ', '_', $_FILES['picture']['name']);
  $file_ext = substr($filename, strpos($filename, '.'), strlen($filename)-1); 
  $target_dest = "staff/$user$file_ext";
  $path = "staff/$user$file_ext";

  if (!in_array($file_ext, $allowed_exts)) {
    $results['message'] = "Invalid File Extension";
    return $results;
  } elseif (!$_FILES['picture']['tmp_name']) {
    $results['message'] = "Something went wrong. " . var_export($_FILES, TRUE) . " ";
  } elseif (copy($_FILES['picture']['tmp_name'], $target_dest)) {
  
    if ($path != $_SESSION['picture']) { unlink($_SESSION['picture']); }

    $results = array(
      'filename'  =>  $filename,
      'file_ext'  =>  $file_ext,
      'path'      =>  $path,
      'message'   =>  "Image uploaded. \n",
      'success'   =>  TRUE
    );

  } else {
    $results['message'] = "Image could not be moved to target destination. ";
  }
  return $results;
}


   //////////////////////////
  //  UPDATE SUBSCRIBERS  //
 //////////////////////////

function updateSubscribers($articleid) {
  
  $art_sql = mysql_query("SELECT * FROM articles WHERE id = '$articleid'");
  $article = mysql_fetch_array($art_sql);

  $sql = mysql_query("SELECT * FROM staff WHERE subscriptions LIKE '%($articleid)%'"); 
  while ($row = mysql_fetch_array($sql)) {

    $to = $row['email'];
    $subject = "ARM PORTAL - An article you are subscribed to has been updated";
    $body = "Article #$article[id], titled '$article[title]', has been updated. \n";
    $body .= "Please follow the link below to view the article: \n \n";
    $body .= "$website/?page=article&articleid=$article[id]";
    $headers = 'From: ARMPORTAL@armgroup.net' . "\r\n" . 
      'Reply-To: ARMPORTAL@armgroup.net' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
    mail ($to, $subject, $body, $headers);

  }
}


   //////////////////////////
  //  AREAS OF EXPERTISE  //
 //////////////////////////

function expertiseList() {
  $areasOfExpertise = array (     ////  AREAS OF EXPERTISE  ////
    'Hydrogeology',
    'Engineering Geology',
    'Subsurface',
    'Bedrock Characterization',
    'Natural Resource Evaluation',
    'Oil and Gas',
    'Mining Exploration & Development',
    'Hydrology',
    'PADEP',
    'SRBC & other Regulatory Permitting',
    'Water Supply',
    'SRBC',
    'Phase I/II ESA',
    'Property Transaction Due Dilligence',
    'Fill Management',
    'Site Remediation and Brownfield/Act 2',
    'Ecological',
    'Wetlands',
    'Cultural Resources',
    'Threatened and Endangered Species',
    'Aquatic Assessments',
    'Watershed Management/Improvement',
    'Environmental Permitting',
    'NEPA',
    'EHS',
    'EERPs',
    'PPC',
    'SPCC',
    'Spill Planning',
    'IH',
    'ACM',
    'LBP',
    'Hazmat',
    'IAQ',
    'Water Resources/Dams',
    'Geotechnical',
    'Soil Mechanics',
    'Foundations',
    'Sinkhole',
    'Landfill/Solid Waste Management Facilities',
    'Renewable Energy Services',
    'Civil Engineering',
    'Environmental Engineering',
    'Engineering',
    'GIS',
    'GPS',
    'Surveying',
    'Stormwater Management',
    'E&S Planning',
    'Wastewater Treatment/Permitting',
    'PADOT Highway Occupancy Permitting (HOP)',
    'Oil & Gas',
    'Construction Oversight/Certification',
    'Electrical (AETA)',
    'Mechanical (AETA)',
    'Controls/Automation (AETA)',
    'Eviromental Geophysics',
    'Geotechnical Geophysics',
    'Borehole',
    'IT Computer Support',
    'Human Resources',
    'Accounting',
    'Marketing',
    'Ornithology',
    'Aquatic Surveys',
    'Stream Surveys',
    'Botany',
    'Threatened & Endangered Species'
  );
  sort($areasOfExpertise);
  return $areasOfExpertise;
}

?>
