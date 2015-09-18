<?php 
  if ($_GET['profileid']) {
    $profileid = $_GET['profileid'];
      
    $sql = "SELECT * FROM staff WHERE id = '$profileid' LIMIT 1";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 1) {
      $row = mysql_fetch_array($result);
    }
  }
  
  if (isset($_POST['action'])) {                    /* ADD or EDIT EMPLOYEE */
    $action = $_POST['action'];
    
    echo '<form method="post" action="?page=profile&profileid=' . $profileid . '">';
    if ($action == 'edit') { echo '<h2>Edit Profile</h2>'; }else{ echo '<h2>Add New Employee</h2>'; }
    echo '<table width=100%>';
      echo '<tr>';
        echo '<td width="160" valign="top">  <!----PICTURE---->';
          if ($row['picture']) { 
            echo '<img src="' . $row['picture'] . '" width="150" />'; 
          } else { 
            echo '<img src="img/no_pic1.png" width="150" />'; }
          echo "<br /><br /><input type='file' style='width: 175px;' accept='image/*' name='picture' />";
        echo '</td>';
        echo '<td valign="top">  <!----INFORMATION---->';
          echo '<div style="margin-left: 0px; margin-right: 75px; width=300px; text-align: right;line-height: 27px;"><b>';
            echo 'First Name: <input type="text" name="fname" value="' . $row['fname'] . '" /><br />';
            echo 'Last Name: <input type="text" name="lname" value="' . $row['lname'] . '" /><br />';
            echo 'Initials: <input type="text" name="initials" value="' . $row['initials'] . '" /><br />';
            echo 'Title: <input type="text" name="title" value="' . $row['title'] . '" /><br />';
            echo 'Office:&nbsp;&nbsp;&nbsp; <select name="location">';
              echo "<option default>Choose one...</option>";
              echo "<option value='Canonsburg, PA'"; if ($row['location'] == 'Canonsburg, PA') { echo " selected "; } echo ">Canonsburg, PA</option>";
              echo "<option value='Columbia, MD'"; if ($row['location'] == 'Columbia, MD') { echo " selected "; } echo ">Columbia, MD</option>";
              echo "<option value='Hershey, PA'"; if ($row['location'] == 'Hershey, PA') { echo " selected "; } echo ">Hershey, PA</option>";
              echo "<option value='State College, PA'"; if ($row['location'] == 'State College, PA') { echo " selected "; } echo ">State College, PA</option>";
              echo "<option value='Wilkes-Barre, PA'"; if ($row['location'] == 'Wilkes-Barre, PA') { echo " selected "; } echo ">Wilkes-Barre, PA</option></select><br />";
            echo 'Email: <input type="text" name="email" value="' . $row['email'] . '" /><br />';
            echo 'Phone: <input type="text" name="phone" value="' . $row['phone'] . '" /><br />';
            echo 'Extension: <input type="text" name="ext" value="' . $row['ext'] . '" /><br />';
            echo 'Start Day: <input type="text" id="datepicker" name="startday" value="' . date('Y/m/d', strtotime($row['startday'])) . '" /><br />';
            echo 'Birthday: <input type="text" id="datepicker2" name="birthday" value="' . date('Y/m/d', strtotime($row['birthday'])) . '" /><br />';
            echo 'Show Birthday: <input type="checkbox" name="showbday" value="y"';
            if ($row['showbday'] == 'y') { echo ' checked'; }
            echo ' /><br />';
          echo '</b></div>';
        echo '</td>';
        echo  '<td valign="top" width=35%>  <!----ABOUT---->';
          echo '<div class="profile-about">';
            echo '<h3>Areas of expertise...</h3>';
            echo '<textarea cols="36" rows="5" name="description" style="padding: 5px;">' .  $row['description'] . '</textarea>';
          echo '</div>';
          echo '<div class="profile-about">';
            echo '<h3>A bit about me...</h3>';
            echo '<textarea cols="36" rows="5" name="about" style="padding: 5px;">' . $row['about'] . '</textarea>';
          echo '</div>';
        echo '</td>';
      echo '</tr>';
    echo '</table>';
    echo '<input type="hidden" name="submit" value="' . $action . '" />';
    echo '<input type="hidden" name="type" value="employee" />';
    echo '<center><input type="submit" value="Submit" style="margin-left: auto; margin-right: auto;" /></center>';
    echo '</form>';
  
  } else {
    
    if (mysql_num_rows($result) == 1) {
      if ( hasPermission('admin') || $_SESSION['id'] == $row['id'] ) {                  /* VIEW PROFILE */
?>

      <form method="post" action="?page=profile&profileid=<?php echo $row['id']; ?>">
        <input type="hidden" name="action" value="edit" />
        <input style="float: right;" type="submit" value="Edit Profile" />
      </form>
    <?php } ?> 
   <h2><?php echo $row['fname'] . ' ' . $row['lname']; ?></h2>
    <table width=100%>
      <tr>
        <td width="160" valign="top">  <!----PICTURE---->
          <img src="<?php if ($row['picture']) { echo $row['picture']; } else { echo 'img/no_pic1.png'; } ?>" width="150" />
        </td>
        <td valign="top">  <!----INFORMATION---->
          <b><?php echo $row['fname'] . ' ' . $row['lname']; ?></b> (<?php echo $row['initials']; ?>)<br />
          <?php echo $row['title']; ?><br />
          <?php if ($row['location']) { echo $row['location'] . '<br />'; } ?><br />
          <a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a><br />
          <?php if ($row['phone'] && $row['ext']) {echo $row['phone'] . ' x' . $row['ext'];} ?><br /><br />
          <?php if ($row['startday']) { ?><u>Start Date:</u> <?php echo date('F j, Y', strtotime($row['startday'])); } ?><br />
          <?php if ($row['birthday'] && $row['showbday'] == 'y') { ?><u>Birthday:</u> <?php echo date('F j', strtotime($row['birthday'])); } ?>
        </td>
        <td valign="top" width=35%>  <!----ABOUT---->
          <div class="profile-about">
            <h3>Areas of Expertise...</h3>
            <p style="padding: 5px;"><?php echo $row['description']; ?></p>
          </div>
          <div class="profile-about">
            <h3>A bit about me...</h3>
            <p style="padding: 5px;"><?php echo $row['about']; ?></p>
          </div>

        </td>
      </tr>
    </table>
  
<?php   
    } else {
      echo "<h2>Profiles</h2>";
      echo "Profile not found!";
    }
  }
?>
