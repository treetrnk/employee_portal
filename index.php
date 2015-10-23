<?php

//LINK TO ENGINE - PHP PROCESSING
include('engine.php');

?>
<html>
  <head>
    <title>ARM Group Inc. | Employee Portal</title>
    <!---DATEPICKER FROM: http://xdsoft.net/jqplugins/datetimepicker/ --->
    <link rel="stylesheet" type="text/css" href="datetimepicker-master/jquery.datetimepicker.css"/ >
    <link rel="stylesheet" type="text/css" href="mini-event-calendar/cal-style.css"/ >
    <script src="datetimepicker/jquery.js"></script>
    <script src="datetimepicker/jquery.datetimepicker.js"></script>
    <script>
      $(function() {
        $( '*[id^="datetimepicker"]' ).datetimepicker();
        $( '#datetimepicker2' ).datetimepicker();
        $( '#datetimepicker3' ).datetimepicker();
        $( '*[id^="datepicker"]' ).datetimepicker({
          timepicker:false, 
          format:'Y/m/d'
        });
        $( '#datepicker2' ).datetimepicker({
          timepicker:false, 
          format:'Y/m/d'
        });
        $( '#datepicker3' ).datetimepicker({
          timepicker:false, 
          format:'Y/m/d'
        });
      });
    </script>
    <!---<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script>
      $(function() {
        $( "#datepicker" ).datepicker();
      });
    </script>--->
    <style type="text/css">
      html, body, .wrapper {
        height: 100%;
      }
      body {
        background: url("img/sky.jpg") fixed no-repeat;
        background-size: 100% 110%;
        margin: 0px;
        font-family: Helvetica;
        font-size: 13px;
      }
      a:link, a:visited, a:active {
        color: #0066ff;
      }
      a:hover {
        color: #0000ff;
      }
      .wrapper {
        height: auto;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        background: #FFFFFF;
      }
      hr {
       display: block; 
       height: 2px;
       border: 0; 
       border-top: 2px solid #f3c60d ;
       margin-right: auto;
       margin-left: auto;
       padding: 0;
      }
      h1 {
        font-family: Times New Roman;
      }
      .header {
        width: 95%;
        margin-left: auto;
        margin-right: auto;
      }
      .header tr td {
        width: 33%;
        background: #FFFFFF;
        padding-bottom: 10px;
        padding-top: 10px;
      }
      .nav-icons tr td {
        width: 50px;
      }
      .nav-icons tr td a {
        font-size: 8pt;
      }
      .navlinkbar {
        margin-left: auto;
        margin-right: auto;
        text-align: left;
        background: url('img/nav.png');
        /*background: -webkit-linear-gradient(#023AA7, #6387CD); /* For Safari 5.1 to 6.0 */
        /*background: -o-linear-gradient(#023AA7, #6387CD); /* For Opera 11.1 to 12.0 */
        /*background: -moz-linear-gradient(#023AA7, #6387CD); /* For Firefox 3.6 to 15 */
        /*background: linear-gradient(#023AA7, #6387CD);
        border-left: solid 1px #386BC8;
        border-bottom: solid 1px #386BC8;
        border-top: solid 1px #85ACF4;*/
        border-right: solid 1px #85ACF4;
        color: #FFFFFF;
        font-weight: bold;
        line-height: 32px;
        width: 95%;
      }
      .navlink:active, .navlink:visited, .navlink:link {
        color: #FFFFFF;
        font-size:  13px;
        font-weight: lighter;
        text-decoration: none;
        line-height: 32px;
        padding-right: 15px;
        padding-left: 15px;
        padding-top: 8px;
        padding-bottom: 8px;
        margin: 0px;
        border-left: solid 1px #386BC8;
        border-right: solid 1px #85ACF4;
        width: 50px;
        height: 30px;
      }
      .navlink:hover {
        color: #000000;
        text-decoration: none;
        background: #8ca7db;
      }
      .navblock {
        background: #0D59A8;
        width: 100%;
        height: 30px;
      }
      .banner {
        background: url('img/banner3.jpg');
        width: 95%;
        height: 150px;
        background-size: 100% 100%;
        margin-top: 5px;
        margin-right: auto;
       margin-left: auto;
        <?php if ($page != 'home') { echo 'display: none;'; } ?>
      }
      .content {
        width: 95%;
        margin-left: auto;
        margin-right: auto;
        background: #FFFFFF;
        vertical-align: top;
      }
      .content table tr td {
        font-size: 14px;
      }
      .main-content {
        width: 100%
      }
      .sidebar {
        width: 200px;
        padding: 5px;
        font-size: 13px;
        vertical-align: top;
      }
      .sidebar h3 {
        text-align: center;
        background: -webkit-linear-gradient(#33935e, #017e44); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#33935e, #017e44); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#33935e, #017e44); /* For Firefox 3.6 to 15 */
        background: linear-gradient(#33935e, #017e44);
        /* background: #017e44; */
        border-radius: 5px;
        -moz-border-radius: 5px;
        color: #ffffff;
        padding-top: 5px;
        padding-bottom: 5px;
      }
      .sidebar table tr td {
        font-size: 12px;
      }
      .sidebar ul {
        margin-left: -20px;
      }
      .smalltext {
        font-size: 11.5px;
      }
      .footer-links {
        width: 100%;
        height: 150px;
        background: #4C74C1;
      }
      .footer-links table {
        position: relative;
        top: 50%;
        transform: translateY(-50%);
      }
      .footer-links td {
        color: #FFFFFF;
        font-size: 13px;
      }
      .footer-links th {
        color: #FFFFFF;
        font-size: 14px;
        font-weight: normal;
        text-align: left;
      }
      .footer-links a {
        color: #ffffff;
      }
      .footer-links a:hover {
        color: #000000;
      }
      .footer {
        width: 100%;
        height: 75px;
        background: #33935e;
        text-align: center;
        color: #ffffff;
        font-size: 11px;
      }
      .login {
        width: 220px; 
        margin-left: auto; 
        margin-right: auto; 
        text-align: right;
        background: #eeeeee;
        border: 3px solid #017e44;
        border-radius: 15px;
        -moz-border-radius: 15px;
        padding: 20px;
      }
      .people-list {
        width: 100%;
        padding: 5px;
      }
      .people-list img {
        float: left;
        padding-right: 10px;
      }
      .people-list table.people-info {
        float: right;
        width: 50%;
      }
      .profile-about {
        max-width: 350px;
      }
      .profile-about h3 {
        text-align: center;
        background: -webkit-linear-gradient(#33935e, #017e44); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#33935e, #017e44); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#33935e, #017e44); /* For Firefox 3.6 to 15 */
        background: linear-gradient(#33935e, #017e44);
        /* background: #017e44; */
        border-radius: 5px;
        -moz-border-radius: 5px;
        color: #ffffff;
        padding-top: 5px;
        padding-bottom: 5px;
      }
      .greenbox {
        background: -webkit-linear-gradient(#33935e, #017e44); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#33935e, #017e44); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#33935e, #017e44); /* For Firefox 3.6 to 15 */
        background: linear-gradient(#33935e, #017e44);
        border-radius: 10px;
        color: #ffffff;
        font-weight: bold;
        margin-right: auto;
        margin-left: auto;
        padding: 20px;
      }
      .message {
        font-size: 12pt;
        text-align: center;
        width: 400px;
        background: -webkit-linear-gradient(#ff6e6e, #ff3030); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#ff6e6e, #ff3030); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#ff6e6e, #ff3030); /* For Firefox 3.6 to 15 */
        background: linear-gradient(#ff6e6e, #ff3030);
        /* background: #017e44; */
        border-radius: 7px;
        -moz-border-radius: 5px;
        color: #ffffff;
        padding-top: 10px;
        padding-bottom: 10px;
        margin-right: auto;
        margin-left: auto;
      }
      .message a {
        color: #ffffff;
        text-decoration: underline;
        /*font-weight: bold;*/
      }
      .message a:hover {
        color: #000000;
        text-decoration: underline;
        /*font-weight: bold;*/
      }
      .hidelink:active, .hidelink:visited, .hidelink:link {
        color: #000000;
        text-decoration: none;
      } 
      .hidelink:hover {
        color: #0000ff;
        text-decoration: underline;
      } 
     .profile-links {
        font-size: 9pt;
      }
      .inline-left {
        display: inline-block;
        float: left;
        clear: left;
        width: 70px;
        text-align: right;
      }
      .inline-right {
        display: inline-block;
        float: left;
      }
      input[type="text"], textarea, select, input[type="checkbox"], input[type="password"] {
        //border: none;
        border: solid 1px #333333;
        background-color: #efefef;
        box-shadow: inset 1px 1px 1px 2px 0 #707070;
        transition: box-shadow 0.3s;
      }
      input[type="text"]:focus, textarea:focus, select:focus, input[type="checkbox"]:focus, input[type="password"] {
        //border: none;
        border: solid 1px #333333;
        background-color: #ffffff;
      }
      select {
        width: 141px;
      }
      input[type="submit"] {
        font-family: Verdana;
        color: #222;
        background-color: #ccc;
        border: solid 1px #999;
        box-shadow 0 3px #27496d;
      }
      input[type="submit"]:hover {
        font-family: Verdana;
        background-color: #bbb;
        border: 1px solid #888;
      }
      input[type="submit"]:active {
        background-color: #666;
        color: #ddd;
        border: solid 1px #888;
      }
      select.time {
        width: 40px;
      }
      .togglebtn {
        width: 100%;
        border-top-width: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-left-width:1px;
        border-style:solid;
        border-color: #424242;
        background: #E5E5E5;
        font-weight: bold;
        cursor: pointer;
        height: 35px;
        line-height: 35px;
      }
      .togglediv {
        display: none;
        width: 100%;
      }
      <?php if ($_GET['sample'] == false) { ?>
      .sample {
        display: none;
      }
      <?php } ?>
    </style>
    <script src="//cdn.ckeditor.com/4.4.6/standard/ckeditor.js"></script>
    <script type="text/javascript" src="./tools/jquery.js"></script>
    <script type="text/javascript">
      // Add a script element as a child of the body
      /*function downloadJSAtOnload() {
        var element = document.createElement("script");
        element.src = "http://code.jquery.com/jquery-latest.min.js";
        document.body.appendChild(element);
      } // Check for browser support of event handling capability
      if (window.addEventListener)
        window.addEventListener("load", downloadJSAtOnload, false);
      else if (window.attachEvent)
        window.attachEvent("onload", downloadJSAtOnload);
      else window.onload = downloadJSAtOnload;
       */
      //https://developers.google.com/speed/docs/insights/BlockingJS

      
      // SLIDE TOGGLE DIVS
      function hidediv(d) {
        var div = '#' + d;
        var divBtn = div + '-btn';
        var divArw = div + '-arw';
        var divDisplay = $(div).css('display');

        if (divDisplay == "none") {
          $('.togglediv').slideUp("slow");
          $('.togglebtn').css('color', '#424242');
          $('.togglebtn').css('background-color', '#E5E5E5');
          $("span[id$='-arw']").html("&#9650; &nbsp;&nbsp;");

          $(div).slideDown("slow");
          $(divArw).html("&#9660; &nbsp;&nbsp;");
          $(divBtn).css('color', '#eeeeee');
          $(divBtn).css('background-color', '#424242');

        } else {
          $('.togglediv').slideUp("slow");
          $('.togglebtn').css('color', '#424242');
          $('.togglebtn').css('background-color', '#E5E5E5');
          $("span[id$='-arw']").html("&#9650; &nbsp;&nbsp;");
        }
      }
      
    </script>
  </head>

<?php    
    
  echo "
  <body>
    <div class='wrapper'>
  ";
           //////////////
          //  HEADER  //
         //////////////

  echo "
      <table class='header' cellspacing='0'> 
        <tr> 
          <td>
            <a href='index.php'><img src='logo.png' /></a>
          </td>
          <td align='center'>
          </td>
          <td align='right' valign='bottom'>
            <h1 style='color: #0D59A8;font-weight: normal;'>Employee Portal</h1> <!----LOGIN/LOGOUT---->
            <span class='profile-links'>
              <table class='nav-icons'>
                <tr>
                  <td align='center'>
                    <a href='?page=article&articleid=93'> 
                      <img src='img/manual.png' />
                      eManual
                    </a>
                  </td>
                  <td align='center'>
                    <a href='https://www.zoho.com/crm/lp/login.html' target='_blank'> 
                      <img src='img/zoho.png' />
                    </a>
                  </td>
          ";
          if (isset($_SESSION['id'])) {
            echo "
                  <td align='center'>
                    <a href='?page=email&type=ticket'> 
                      <img src='img/helpdesk.png' />
                      Helpdesk
                    </a>
                  </td>
                  <td align='center'>
                    <a href='?page=email&type=feedback'> 
                      <img src='img/feedback.png' />
                      Feedback
                    </a>
                  </td>
            ";

                if ( hasPermission('approve') ) {
                  $pendsql = "SELECT * FROM articlesPending WHERE del = 'n'";
                  $result = mysql_query($pendsql);
                  $pend = mysql_num_rows($result);
                  $pendsql = "SELECT * FROM staffPending WHERE del = 'n'";
                  $result = mysql_query($pendsql);
                  $pend += mysql_num_rows($result);
                  if ( $pend > 0 ) {
                    echo " 
                      <td align='center'>
                        <a href=''> 
                          <img src='img/pending-red.png' />
                          Pending
                        </a>
                        </td>
                    ";
                  }
                }
            echo "
                  <td align='center'>
                    <a href='?page=profile&profileid=$_SESSION[id]'> 
                      <img src='$_SESSION[picture]' width=30 height=30 />
                      $_SESSION[user]
                    </a>
                  </td>
                  <td align='center'>
                    <a href='?page=$page&logout=y'> 
                      <img src='img/logout.png' />
                      Logout
                    </a>
                  </td>
            ";
          } else {
            echo "
                  <td align='center'>
                    <a href='?page=login'> 
                      <img src='img/login.png' />
                      Login
                    </a>
                  </td>
            ";
          }
          echo "
                </tr>
              </table>
  ";


  echo "
            </span>
          </td>
        </tr>
      </table>
  ";
             //////////////////
            //  NAVIGATION  //
           //////////////////

      echo "<div class='navlinkbar'>";
        echo "<a href='?page=home' class='navlink'>Home</a>";
        echo "<a href='?page=people' class='navlink'>People</a>";
        echo "<a href='?page=resources' class='navlink'>Resources</a>";
        echo "<a href='?page=expertise' class='navlink'>Expertise</a>";
        echo "<a href='?page=help' class='navlink'>I.T. Support</a>";
        //echo "<a href='?page=safety' class='navlink'>Safety</a>";
        //echo "<a href='?page=hr' class='navlink'>H.R.</a>";
        //echo "<a href='?page=training' class='navlink'>Training</a>";
        if (isset($_SESSION['user']) && $_SESSION['user'] == 'nhare') {
        echo "<a href='?page=offices' class='navlink'>Offices</a>";
        }
        echo "<a href='?page=forum' class='navlink'>Forum</a>";
  
  echo "
            <form method='post' action='?page=search' style='float: right;'>
              <label>Search <input type='text' name='search' size='20' /></label> &nbsp; 
              <input style='margin-top: 6px; margin-left: 0px; margin-right: 3px;' type='image' src='img/search.png' align='right' alt='Search'>&nbsp;
            </form>
      </div>

      <div class='banner'></div>
  ";

         ///////////////
        //  CONTENT  //
       ///////////////

  echo "
      <div class='content'>  
       <!--- <hr color=#f3c60d width=100% /> --->
        <br />
        <table width=100%>
          <tr>
  ";

  if ($page == 'home') { 
    echo "
            <td valign='top'>
              <div class='sidebar'>
    ";


    //////////  CAREER OPPORTUNITIES  //////////

                echo "<h3><a href='?page=careerops' style='color:#ffffff; text-decoration:none;'>Career Opportunities</a></h3>"; 
                  $jobsql = "SELECT * FROM articles WHERE type = 'job' AND del = 'n' ORDER BY date DESC LIMIT 5 ";
                  $result = mysql_query($jobsql);
                  if ($result) {
                    echo "<ul>";
                    while ($job = mysql_fetch_array($result)) {
                      echo "<li><a href='?page=article&articleid=$job[id]'>" . substr($job['title'], 0, 28) . "...</a><br />";
                      echo $job['location'] . '</li>';
                    }
                    if ( hasPermission('job') ) {
                      echo "
                        <br />
                        <form method='post' action='?page=article'>
                          <input type='hidden' name='action' value='add' />
                          <input type='hidden' name='type' value='job' />
                          <input type='submit' value='Add Position' />
                        </form>
                      ";
                    } 
                    echo "</ul>";
                  }  
                  echo "</ul>";


    //////////  NEW HIRES  //////////
          
              echo "
                <h3>New Hires</h3>
                <ul>
              ";

                $before = $dateunits['month'];
                $after = $dateunits['month'];

                fillSidebar($before, $after, 'startday');

              echo "</ul><br />";


    //////////  MOVING ON  //////////
              
              echo "
                <h3>Moving On</h3>
                <ul>
              ";

                $before = $dateunits['month'];
                $after = $dateunits['month'];
                
                fillSidebar($before, $after, 'leaveday');

              echo "</ul><br />";


          echo "      
              </div>
            </td>
          ";
          } 


               ////////////////////
              //  MAIN CONTENT  //
             ////////////////////

          echo "<td valign='top' style='padding: 10px;' style='vertical-align: top;' class='main-content'>";


            if (isset($message)) {echo "<div class='message'>$message</div><br /><br />";} 
          
            include($page . '.php'); 

          
          echo "
              <br />
              <br />
            </td>
          ";

        if ($page == 'home') { 

          echo " 
            <td valign='top'>
              <div class='sidebar'>
          ";



    //////////  EVENTS  //////////

          echo "<h3>Events </h3>";

                  include('mini-event-calendar/calendar.php');
                  $eventsql = "SELECT * FROM articles WHERE type = 'event' AND startdate >= '$todaystart' ORDER BY startdate ASC LIMIT 5";
                  $result = mysql_query($eventsql);
                  if ($result) {
                    echo '<ul>';
                    while ($event = mysql_fetch_array($result)) {
                      echo '<li><a href="?page=article&articleid=' . $event['id'] . '">' . substr($event['title'], 0, 18) . '...</a> ';
                      echo date('(m/d)', $event['startdate']) . '</li>';
                    }
                    if ( hasPermission('event') ) {
                      echo '<br />';
                      echo '<form method="post" action="?page=article">';
                        echo '<input type="hidden" name="action" value="add" />';
                        echo '<input type="hidden" name="type" value="event" />';
                        echo '<input type="submit" value="Add Event" />';
                      echo '</form>';
                    } 
                    echo '</ul>';
                  }  


    //////////  BIRTHDAYS  //////////

          echo "
                <h3>Birthdays</h3>
                <ul>
          ";

                $before = $dateunits['day'] * 15;
                $after = $dateunits['day'] * 15;

                fillSidebar($before, $after, 'birthday');

          echo "
                </ul>
                <br />
          ";


    //////////  ANNIVERSARIES  //////////

          echo "
                <h3>Anniversaries</h3>
                <ul>
          ";

                $before = $dateunits['day'] * 15;
                $after = $dateunits['day'] * 15;

                fillSidebar($before, $after, 'anniversary');

          echo "
                </ul>
              </div>
              </td>  
          ";
        }

        echo "
          </tr>
        </table>
      </div>

      <div class='footer-links'>  <!---FOOTER LINKS--->
        ";
        
        include('footer.php'); 

    echo "</div>";

               //////////////
              //  FOOTER  //
             //////////////

    echo "
      <div class='footer'>
        <br />
        <br />
        &copy; Copyright 2011 ARM Group Inc. | Terms & Conditions<br />
        ARM Enertech | ARM Geophysics
      </div>

    </div> <!----WRAPPER END---->



  </body>
</html>
";
?>
