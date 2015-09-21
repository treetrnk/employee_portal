<?php 

  if (isset($_SESSION)) {


    if ($_GET['type'] == "feedback") {
      echo "<h2>Submit Feedback</h2>";
    } else {
      echo "<h2>Submit a Help Desk Ticket</h2>";
      $_GET['type'] = 'ticket';
    }
    
    echo "
      <br />
      <h3>Instructions:</h3>
      <p>
      (----instructions go here----)
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
     $message = "You must be logged in to use this page";
  }

?>
