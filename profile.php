<?php 
  if ($_GET['profileid']) {
    $profileid = $_GET['profileid'];
      
    $sql = "SELECT * FROM staff WHERE id = '$profileid' LIMIT 1";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 1) {
      $row = mysql_fetch_array($result);
    }
  }

  $areasOfExpertise = array (     ////  AREAS OF EXPERTISE  ////
    'Water Supply', 
    'Hydrogeology', 
    'SRBC',
    'Fill Management',
    'Site Remediation',
    'Brownfield/Act 2',
    'Ecological/Wetlands',
    'Cultural Resources',
    'Threatened and Endagered Species',
    'Aquatic Assessments',
    'Watershed Management/Improvement',
    'Environmental Permitting',
    'NEPA',
    'Spill Planning',
    'Hazmat',
    'Water Resources/Dams',
    'Grotechnical',
    'Soil Mechanics',
    'Foundations',
    'Sinkhole',
    'Landfill',
    'Solid Waste Management Facilities',
    'Renewable Energy Services',
    'GIS',
    'GPS',
    'Surveying',
    'Stormwater Management',
    'E&S Planning',
    'Wastewater Treatment/Permitting',
    'PADOT Highway Occupancy Permitting (HOP)',
    'Oil & Gas',
    'Construction Oversight/Certification',
    'Electrical',
    'Mechanical',
    'Controls/Automation',
    'Environmental/Geotechnical',
    'Borehole',
    'Marketing',
    'Human Resources',
    'Accounting',
    'Graphic Design',
    'Computer Support',
    'Web Development',
    'IT Infrastructure'
  );
  sort($areasOfExpertise);


         ////////////////////
        //  EDIT PROFILE  //
       ////////////////////

  if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    echo "<form method='post' action='?page=profile&profileid=$profileid'>";
    if ($action == 'edit') { echo "<h2>Edit Profile</h2>"; }else{ echo "<h2>Add New Employee</h2>"; }
    echo "
      <table width=100%>
        <tr>
          <td width='160' valign='top'>  <!----PICTURE---->";
            if ($row['picture']) { 
              echo "<img src='$row[picture]' width='150' />"; 
            } else { 
              echo "<img src='img/no_pic1.png' width='150' />"; }
    echo "
            <br /><br /><input type='file' style='width: 175px;' accept='image/*' name='picture' />
          </td>
          <td valign='top'>  <!----INFORMATION---->
            <div style='margin-left: 0px; margin-right: 75px; width=300px; text-align: right;line-height: 27px;'><b>
              First Name: <input type='text' name='fname' value='$row[fname]' /><br />
              Last Name: <input type='text' name='lname' value='$row[lname]' /><br />
              Initials: <input type='text' name='initials' value='$row[initials]' /><br />
              Title: <input type='text' name='title' value='$row[title]' /><br />
              Office:&nbsp;&nbsp;&nbsp; <select name='location'>
                <option default>Choose one...</option>
                <option value='Canonsburg, PA'"; 
                  if ($row['location'] == 'Canonsburg, PA') { echo " selected "; } 
                  echo ">Canonsburg, PA</option>
                <option value='Columbia, MD'"; 
                  if ($row['location'] == 'Columbia, MD') { echo " selected "; } 
                  echo ">Columbia, MD</option>
                <option value='Hershey, PA'"; 
                  if ($row['location'] == 'Hershey, PA') { echo " selected "; } 
                  echo ">Hershey, PA</option>
                <option value='State College, PA'"; 
                  if ($row['location'] == 'State College, PA') { echo " selected "; } 
                  echo ">State College, PA</option>
                <option value='Wilkes-Barre, PA'"; 
                  if ($row['location'] == 'Wilkes-Barre, PA') { echo " selected "; } 
                  echo ">Wilkes-Barre, PA</option></select><br />
              Email: <input type='text' name='email' value='$row[email]' /><br />
              Office #: <input type='text' name='phone' value='$row[phone]' /><br />
              Extension: <input type='text' name='ext' value='$row[ext]' /><br />
              Cell #: <input type='text' name='cellphone' value='$row[cellphone]' /><br />
              Start Day: <input type='text' id='datepicker' name='startday' value='" . date('Y/m/d', strtotime($row['startday'])) . "' /><br />
              Birthday: <input type='text' id='datepicker2' name='birthday' value='" . date('Y/m/d', strtotime($row['birthday'])) . "' /><br />
              Show Birthday: <input type='checkbox' name='showbday' value='y'";
                if ($row['showbday'] == 'y') { echo ' checked'; }
                echo " /><br />
              </b></div>
            </td>
            <td valign='top' width=35%>  <!----ABOUT---->
              <div class='profile-about'>
                <h3>Areas of expertise...</h3>
                <center>
                  <span class='smalltext' style='color:#333333'>(Use Ctrl+Click to select multiple items)</span>
                </center>
                <select name='description[]' size=10 style='width:100%' multiple>
                ";
      
                  foreach ($areasOfExpertise as $i) {
                    echo "<option value='$i'";
                    if (strstr($row['description'], $i)) {
                      echo " selected";
                    } 
                    echo ">$i</option>";
                  }

                echo "
                </select>
              </div>
              <div class='profile-about'>
                <h3>A bit about me...</h3>
                <textarea cols='36' rows='5' name='about' style='padding: 5px;'>$row[about]</textarea>
              </div>
            </td>
          </tr>
        </table>
        <input type='hidden' name='submit' value='$action' />
        <input type='hidden' name='type' value='employee' />
        <center><input type='submit' value='Submit' style='margin-left: auto; margin-right: auto;' /></center>
      </form>
    ";
  
  } else {
    
         ////////////////////
        //  VIEW PROFILE  //
       ////////////////////

    if (mysql_num_rows($result) == 1) {
      if ( hasPermission('admin') || $_SESSION['id'] == $row['id'] ) {
        echo "
          <form method='post' action='?page=profile&profileid=$row[id]'>
            <input type='hidden' name='action' value='edit' />
            <input style='float: right;' type='submit' value='Edit Profile' />
          </form>
        ";
      }
      
      $description = explode(' -- ', $row['description']);
      echo "
       <h2>$row[fname] $row[lname]</h2>
        <table width=100%>
          <tr>
            <td width='160' valign='top'>  <!----PICTURE---->
              <img src=";
                if ($row['picture']) { echo $row['picture']; } else { echo 'img/no_pic1.png'; } 
                echo " width='150' />
            </td>
            <td valign='top'>  <!----INFORMATION---->
              <b>$row[fname] $row[lname]</b> ($row[initials])<br />
              $row[title]<br />";
              if ($row['location']) { echo $row['location'] . '<br />'; } 
                echo "<br />
              <u>Email</u>: <a href='mailto:$row[email]'>$row[email]</a><br />";
              if ($row['phone'] && $row['ext']) { echo "<u>Office #</u>: $row[phone] x$row[ext] <br />";} 
              if ($row['cellphone']) { echo "<u>Cell #</u>: $row[cellphone] <br />";} 
                echo "<br />";
              if ($row['startday']) { echo "<u>Start Date</u>: " . date('F j, Y', strtotime($row['startday'])) . "<br />"; } 
              if ($row['birthday'] && $row['showbday'] == 'y') { echo "<u>Birthday:</u>" . date('F j', strtotime($row['birthday'])); }
                echo "
            </td>
            <td valign='top' width=35%>  <!----ABOUT---->
              <div class='profile-about'>
                <h3>Areas of Expertise...</h3>
                <ul>
                ";

                foreach ($description as $i) {
                  echo "<li>$i</li>";
                }  

                echo "
                </ul>
              </div>
              <div class='profile-about'>
                <h3>A bit about me...</h3>
                <p style='padding: 5px;'>$row[about]</p>
              </div>
            </td>
          </tr>
        </table>
      ";

  
    } else {
      echo "
        <h2>Profiles</h2>
        Profile not found!
      ";
    }
  }
?>
