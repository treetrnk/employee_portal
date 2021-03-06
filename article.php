<?php 
  if (isset($_GET['articleid']) && $_GET['articleid'] != "") {
    $articleid = $_GET['articleid'];
    $sql = "SELECT * FROM articles WHERE id = '$articleid'";
    $result = mysql_query($sql);
    if ($result) {
      $article = mysql_fetch_array($result);
    }
  }
  if (isset($_POST['type'])) { 
    $type = $_POST['type']; 
  } elseif ($article['type']) { 
    $type = $article['type']; 
  } else { 
    $type = "article"; 
  }
  

  if (isset($_POST['category']) && isset($_POST['subcat'])) {
    $article['category'] = $_POST['category'];
    $article['subcat'] = $_POST['subcat'];
  }

    ////////////////////////////
   //    ADD/EDIT ARTICLE    //
  ////////////////////////////


  if (isset($_POST['action'])) {                                          
    $action = $_POST['action'];

    echo "
      <h3>" . ucfirst($action) . " " . ucfirst($type) . "</h3>
      <form method='post' action='index.php?page=article&articleid=$articleid'>
    ";

    if ($type == 'resources') {
      echo "
        <b>Category</b><br />
        <select name='category'>
          <option value='' default>Choose one...</option>
          <option value='General Administration'"; 
            if ($article['category'] == 'General Administration') { echo " selected "; } 
            echo ">General Administration</option>
          <option value='Health & Safety'"; 
            if ($article['category'] == 'Health & Safety') { echo " selected "; } 
            echo ">Health & Safety</option>
          <option value='Human Resources'"; 
            if ($article['category'] == 'Human Resources') { echo " selected "; } 
            echo ">Human Resources</option>
          <option value='Marketing'"; 
            if ($article['category'] == 'Marketing') { echo " selected "; } 
            echo ">Marketing</option>
          <option value='Standard Operating Procedures & Checklists'"; 
            if ($article['category'] == 'Standard Operating Procedures & Checklists') { echo " selected "; } 
            echo ">Standard Operating Procedures & Checklists</option>
        </select><br /><br />

        <b>Subcategory</b><br />
        <select name='subcat'>
          <option value='' default>Choose one...</option>
          <option value='General'"; 
            if ($article['subcat'] == 'General') { echo " selected "; } 
            echo ">General</option>
          <option value='Standards'";
            if ($article['subcat'] == 'Standards') { echo " selected "; } 
            echo ">Standards</option>
          <option value='Meeting Minutes'";
            if ($article['subcat'] == 'Meeting Minutes') { echo " selected "; } 
            echo ">Meeting Minutes</option>
        </select><br /><br />
      "; 
    }
    echo "
        <b>Title</b><br />
        <input type='text' name='title' size='80' value='$article[title]' /><br />
    ";
    if ($type == 'event') { 
      echo "
        <br />
        <b>Start Date/Time</b><br />
        <input type='text' name='startdate' id='datepicker' value='" . date('Y/m/d', $article['startdate']) . "' />
      ";
        timePicker($article, 'startdate');
      echo "
        <br /><br />
        <b>End Date/Time</b><br />
        <input type='text' name='enddate' id='datepicker2' value='" . date('Y/m/d', $article['enddate']) . "' />
      ";
        timePicker($article, 'enddate');
      $checked = "";
      if ($article['category'] == 'holiday') { $checked = 'checked'; }
      echo "
        <br /><br />
        <input type='hidden' name='location' value'NA' />
        <br />
        <input type='checkbox' value='holiday' $checked name='category' /> <b>Holiday</b><br />
      ";
    } elseif ($type == 'job') { 
      echo "
        <br />
        <b>Location</b><br />
        <select name='location'>
          <option value='' default>Choose one...</option>
          <option value='Company-wide'"; 
            if ($article['location'] == 'Company-wide') { echo " selected "; } 
            echo ">Any</option>
          <option value='Canonsburg, PA'"; 
            if ($article['location'] == 'Canonsburg, PA') { echo " selected "; } 
            echo ">Canonsburg, PA</option>
          <option value='Columbia, MD'"; 
            if ($article['location'] == 'Columbia, MD') { echo " selected "; } 
            echo ">Columbia, MD</option>
          <option value='Hershey, PA'"; 
            if ($article['location'] == 'Hershey, PA') { echo " selected "; } 
            echo ">Hershey, PA</option>
          <option value='State College, PA'"; 
            if ($article['location'] == 'State College, PA') { echo " selected "; } 
            echo ">State College, PA</option>
          <option value='Wilkes-Barre, PA'"; 
            if ($arcticle['location'] == 'Wilkes-Barre, PA') { echo " selected "; } 
            echo ">Wilkes-Barre, PA</option>
        </select><br />
      ";
    } else { 
      echo "
        <input type='hidden' name='eventdate' value='NA' />
        <input type='hidden' name='location' value='NA' />
      ";
    }
    echo "
        <br />
        <b>Body</b><br />
        <textarea name='body' id='body' rows='20' cols='120'>$article[body]</textarea>
        <script>
          CKEDITOR.replace('body');
        </script>
        <br />
        <br />
        <input type='hidden' name='submit' value='$action' />
        <input type='hidden' name='type' value='$type' />
        <input type='submit' value='Submit' />
      </form>
    ";

  
  } elseif (isset($article)) { 


    ////////////////////////
   //    VIEW ARTICLE    //
  ////////////////////////

    if ( hasPermission('admin') ||  $article['userid'] == $_SESSION['id']) {
      echo "
        <form method='post' action='index.php?page=article&articleid=$articleid'>
          <input type='hidden' name='action' value='edit' />
          <input type='hidden' name='type' value='$type' />
          <input type='submit' name='submit' value='Delete' style='float:right; position:relative; top:18px;' />
          <input type='submit' value='Edit' style='float:right; position:relative; top:18px;' />
        </form>
      ";
    }

    if ($article['type'] == 'resources') {
      echo "
        <a href='?page=resources'>&lt; return to Resources</a>
      ";
    }

    if ($article['type'] == 'help') {
      echo "
        <a href='?page=help'>&lt; return to I.T. Help</a>
      ";
    }

    echo "<h2>$article[title]</h2>";
    if ($article['category'] == "holiday") { echo "<b>Holiday Event</b><br /><br />"; }
    if ($article['type'] == 'resources') {
      echo "
        <b>Category:</b> $article[category]<br />
        <b>Subcategory:</b> $article[subcat]<br /><br />
      ";
    }
    if ($article['type'] == 'event') {
        echo "<b>" . date('l, M. j Y', $article['startdate']) . "</b><br />";
      if (date('Y-m-d', $article['startdate']) == date('Y-m-d', $article['enddate'])) {
        echo date('g:i A', $article['startdate']) . " - ";
        echo date('g:i A', $article['enddate']);
      }else{
        echo "<b>" . date('g:i A', $article['startdate']) . "</b>";
      }


    } elseif ($article['type'] == 'job') {
      echo "<b>$article[location]</b>";
    }
    $userids = $article['userid'];
    $usersql = mysql_query("SELECT * FROM staff WHERE id = '$userids'");
    $userinfo = mysql_fetch_array($usersql);

      echo "
        $article[body]
        <br /><br />
      ";

    if (isset($_SESSION['id'])) {
      $current_user = mysql_fetch_array(mysql_query("SELECT * FROM staff WHERE id = $_SESSION[id]"));
      if (strstr($current_user['subscriptions'], "($articleid)")) { $subscribe = "Unsubscribe"; } else { $subscribe = "Subscribe"; }
      echo "
        <form method='post' action='?page=article&articleid=$articleid'>
          <input type='submit' name='submit' value='$subscribe' style='float:right;' />
        </form>
      ";
    }

    echo "
        <span style='font-size: 8pt;'>
          By: <a class='hidelink' href='?page=profile&profileid=$article[userid]'>$userinfo[fname] $userinfo[lname]</a> - " . date('M j, Y @ g:i a', $article['date']) . "
        </span>
    ";

    echo "
      <br /><br /><br /><br />
      
      <div style='width:60%; margin-right:auto; margin-left:auto; text-align:right;'>
    ";

        if ( hasPermission('comment') ) {
          echo "
            <form method='post' action='?page=article&articleid=$articleid'>
              <textarea name='comment' style='width:100%; height:75px;'></textarea>
              <input style='margin-right:auto; margin-left:auto;' type='submit' name='submit' value='Comment' />
            </form>
          ";
        }

    $sql = "SELECT * FROM comments WHERE parentid = '$articleid' ORDER BY date";
    $result = mysql_query($sql);
    $count = 0;
    if (mysql_num_rows($result)) {
      echo "<h3 style='text-align:left;'>Comments</h3>";
    }
    while ($row = mysql_fetch_array($result)) {
      $count ++;

      $usersql = mysql_query("SELECT * FROM staff WHERE id = '$row[userid]'");
      $user = mysql_fetch_array($usersql);

      echo "

        <div class='people-list' style='min-height: 80px; border:1px solid #dedede;";
          if ($count % 2 == 0) { echo 'background: #dedede;'; } 
          echo "'>

        <a href='?page=profile&profileid=$user[id]'>
          <img src='";
            if ($user['picture']) { echo "$user[picture]"; } else { echo "img/no_pic1.png"; } 
            echo "?" . rand(1, 9999) . "' width='75' height='75' />
        </a> 
        <p style='text-align:left;'>
          <a class='hidelink' href='?page=profile&profileid=$row[userid]'><u>$user[fname] $user[lname]</u></a>:<br />
          $row[text]
        </p>
        
        <span style='font-size: 8pt;'>
          " . date('M j, Y @ g:i a', $row['date']) . "
        </span>

        
        </div>
      ";

    }
        


    echo "
      </div>
    ";  
     
 
  } else {


    ///////////////////////////////
   //    CANNOT FIND ARTICLE    //
  ///////////////////////////////


    echo "<h2>No Article Found</h2>The article you are looking for could not be found.<br />";   

  }
?>


<br /><br /><br />
