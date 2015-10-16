<?php 

  $type = "job";

  $offices = array(
    "Any",
    "Hershey, PA",
    "State College, PA",
    "Columbia, MD",
    "Wilkes-barre, PA",
    "Canonsburg, PA"
  );
  
  sort($offices);

  echo "
    <h2>Areas of Expertise</h2>
    <div style='width: 700px; margin-right: auto; margin-left: auto;'>
  ";

  foreach ($offices as $o) {

    $identifier = preg_replace("/[^a-zA-Z0-9]/", "", $o);

    echo "
      <div class='togglebtn' onClick='hidediv(\"$identifier\")' id='$identifier-btn'><span id='$identifier-arw' style='float:right;font-weight:normal;'>&#9650; &nbsp;&nbsp;</span>&nbsp;&nbsp;$o</div>
      <div class='togglediv' id='$identifier'><br />
    ";

    $sql = "SELECT * FROM articles WHERE location LIKE '%$o%' AND type = 'job' ORDER BY title";
    
    if ($result = mysql_query($sql)) {

      while ($job = mysql_fetch_array($result)) {
        if ( hasPermission('admin') ||  $job['userid'] == $_SESSION['id']) {
          echo "
            <form method='post' action='index.php?page=article&articleid=$job[id]'>
              <input type='hidden' name='action' value='edit' />
              <input type='hidden' name='type' value='$type' />
              <input type='submit' name='submit' value='Delete' style='float:right; position:relative; top:18px;' />
              <input type='submit' value='Edit' style='float:right; position:relative; top:18px;' />
            </form>
          ";
        }
        echo "
          <a href='?page=article&articleid=$job[id]'><b>$job[title]</b></a><br /> 
          $job[body]
          <br /><br />
        ";
      }

    } else {
      echo "None found.";
    }

    echo "
      </div>
    ";

  }


?>
