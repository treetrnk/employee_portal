<?php 
if ($_GET['action'] && $_GET['type']) {
  $action = $_GET['action'];
  $type = $_GET['type'];

switch ($type) {

  case 'employee':                            /* PROFILE */

    if ($_POST['fname']) {
     
      /* INSERT INTO DATABASE */

    } 

  break;
  case 'job':





  break;
  case 'event':





  break;
  case 'article':


    //ADD ARTICLE
    if ($_GET['action'] == "add") {
      if (isset($_POST['title']) && isset($_POST['body'])) {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $userid = $_SESSION['id'];
        $time = date("Y-m-d H:i:s", time());
        $sql = "INSERT INTO articles (title, body, date, userid, type) VALUES ('$title', '$body', '$time', '$userid', 'article')";
        
        if (mysql_query($sql)) {
          $message = "Article added!!!";
        } else {
          $message = "There was a problem";
        }
      } else {
        $page = "edit";
        $message = "Please fill all fields.";
      }
    }



}


} else {
  echo "";
}
?>
