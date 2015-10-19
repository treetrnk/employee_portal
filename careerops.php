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

  if ( hasPermission('job') ) {
    echo "
      <br />
      <form method='post' action='?page=article'>
        <input type='hidden' name='action' value='add' />
        <input type='hidden' name='type' value='job' />
        <input type='submit' value='Add Position' style='float:right;' />
      </form>
    ";
  } 

  echo "
    <h2>Career Opportunities</h2><br />
    <div style='width: 700px; margin-right: auto; margin-left: auto;'>
  ";

  foreach ($offices as $o) {

    $identifier = preg_replace("/[^a-zA-Z0-9]/", "", $o);

    echo "
      <div class='togglebtn' onClick='hidediv(\"$identifier\")' id='$identifier-btn'><span id='$identifier-arw' style='float:right;font-weight:normal;'>&#9650; &nbsp;&nbsp;</span>&nbsp;&nbsp;$o</div>
      <div class='togglediv' id='$identifier'><ul><br />
    ";

    $sql = "SELECT * FROM articles WHERE location LIKE '%$o%' AND type = 'job' and del = 'n' ORDER BY title";
    $result = mysql_query($sql);
    
    if (mysql_num_rows($result)) {

      while ($job = mysql_fetch_array($result)) {
        echo "
          <li><a href='?page=article&articleid=$job[id]'><b>$job[title]</b></a></li><br /> 
        ";
      }

    } else {
      echo "<li>None found</li><br />";
    }

    echo "
        </ul>
      </div>
    ";

  }


?>
