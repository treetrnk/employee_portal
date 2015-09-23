<?php 

  if ($_GET['type'] == "feedback") {
    echo "<h2>Submit ARM Portal feedback</h2>";
  } else {
    echo "<h2>Submit a Help Desk ticket</h2>";
    $_GET['type'] = 'ticket';
  }
  
  if (isset($_SESSION['fname'])) {

    echo "
      <br />
      <p>
    ";
    
    if ($_GET['type'] == "feedback") {
      echo "
        This form can be used to report a bug, request features, or ask questions. All ARM Portal Feedback submissions should be in regards to the ARM Portal website only. If you are submitting a bug report, please include the following information
        <ul>
          <li>The issue itself</li>
          <li>What you were doing prior to the problem occuring</li>  
          <li>Any error messages that were displayed</li>
        </ul>
      ";
    } else {
      echo "
        When submitting a ticket, please be descriptive and include following information:
        <ul>
          <li>The issue itself</li>
          <li>What you were doing prior to the problem occuring</li>
          <li>Any error messages that were displayed</li>
          <li>Any recent changes to your computer (updates, installs, hard shutdowns, etc.)</li>
        </ul>
      ";
    }

    echo "
      </p><br /><br />
      
      <div style='width: 80%; margin-left:auto; margin-right:auto;'>
        <form method='post' action='index.php'>
          <label>Subject: <br />
          <input type='text' name='subject' style='width:100%; align: center;' /></label><br /><br />
          <label>Body: <br /> 
          <textarea name='body' style='width: 100%; height: 250px;'></textarea><br /><br />
          <input type='hidden' name='type' value='$_GET[type]' />
          <div style='text-align:center; width:100%;'>
            <input type='submit' name='submit' value='Send'  /><br /><br />
          </div>
        </form>
      </div>
      ";      


  } else {
      echo "<p>You must be logged in to use this page. If you are having trouble logging in, please email I.T. at <a href ='mailto:helpdesk@armgroup.net'>helpdesk@armgroup.net</a></p>";
  }

?>
