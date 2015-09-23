<?php 
  if (isset($_GET['articleid'])) {
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
  

    ////////////////////////////
   //    ADD/EDIT ARTICLE    //
  ////////////////////////////


  if (isset($_POST['action'])) {                                          
    $action = $_POST['action'];

    if ($action == 'edit' && $_GET['articleid']) {

    }


    echo "
      <h3>" . ucfirst($action) . " " . ucfirst($type) . "</h3>
      <form method='post' action='index.php?page=article&articleid=$articleid'>
        <b>Title</b><br />
        <input type='text' name='title' size='80' value='$article[title]' /><br />
    ";
    if ($type == 'event') { 
      echo "
        <br />
        <b>Start Date/Time</b><br />
        <input type='text' name='startdate' id='datetimepicker' value='" . date('Y-m-d h:i:s', $article['startdate']) . "' /><br />
        <b>End Date/Time</b><br />
        <input type='text' name='enddate' id='datetimepicker2' value='" . date('Y-m-d h:i:s', $article['enddate']) . "' /><br />
        <input type='hidden' name='location' value'NA' />
      ";
    } elseif ($type == 'job') { 
      echo "
        <br />
        <b>Location</b><br />
        <input type='text' name='location' value='$article[location]' /><br />
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

  
  } elseif ($result) { 


    ////////////////////////
   //    VIEW ARTICLE    //
  ////////////////////////

    if ( hasPermission('admin') ||  $article['userid'] == $_SESSION['id']) {
      echo "
        <form method='post' action='index.php?page=article&articleid=$articleid'>
          <input type='hidden' name='action' value='edit' />
          <input type='hidden' name='type' value='$type' />
          <input type='submit' value='Edit Article' />
        </form>
      ";
    }
    echo "<h2>$article[title]</h2>";
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
      <form method='post' action='?page=article&articleid=$articleid'>
        <input type='submit' name='submit' value='Subscribe' style='float:right;' />
      </form>
      <span style='font-size: 8pt;'>
        By: <a class='hidelink' href='?page=profile&profileid=$article[userid]'>$userinfo[fname] $userinfo[lname]</a> - " . date('M j, Y @ g:i a', $article['date']) . "
      </span>
      <br /><br /><br /><br />
      
      <div style='width:60%; margin-right:auto; margin-left:auto; text-align:right;'>
    ";

        if ( hasPermission('comment') ) {
          echo "
            <form method='post' action='?page=article&articleid=$articleid'>
              <textarea style='width:100%; height:75px;'></textarea>
              <input style='margin-right:auto; margin-left:auto;' type='submit' name='submit' value='Comment' />
            </form>
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
