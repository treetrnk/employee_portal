<?php

  //LINK TO ENGINE - PHP PROCESSING
  include('engine.php');

  $banners = glob("img/banner*");

?>
<!doctype html>
<html>
  <head>
    <title>The ARMory</title>
    <!---DATEPICKER FROM: http://xdsoft.net/jqplugins/datetimepicker/ --->
    <link rel="stylesheet" type="text/css" href="datetimepicker-master/jquery.datetimepicker.css"/ >
    <link rel="stylesheet" type="text/css" href="mini-event-calendar/cal-style.css"/ >
    <link rel="stylesheet" type="text/css" href="css/main.css"/ >
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/datetimepicker.js"></script>
    <script type="text/javascript" src="./tools/jquery.js"></script>
    <script src="datetimepicker/jquery.js"></script>
    <script src="datetimepicker/jquery.datetimepicker.js"></script>
    <script src="//cdn.ckeditor.com/4.4.6/standard/ckeditor.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script>
      $(function() {
        $( '*[id^="datepicker"]' ).datepicker();
      });
    </script>
    <script type="text/javascript">

      // DATE TIME PICKER
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

      // HOME PAGE BANNER
      var $slide = 1;

      function slideSwitch() {

        var $next = $slide + 1;

        if ($slide == <?php echo count($banners) ?>) {
          $next = 1;
        }

        $('#banner' + $next).fadeIn("slow");
        $('#banner' + $next).removeClass('hide');

        $('#banner' + $slide).fadeOut("fast");
        $('#banner' + $slide).addClass('hide');

        $slide = $next;
      }

      $(function() {
        setInterval( "slideSwitch()", 7500 );
      });
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
          <td align='right'>
            <div class='logo-crop'>
              <a href='http://armory/'>
                <img src='logo.png?" . rand(1, 9999) . "' style='float: left;' />
              </a>
            </div>
            <span class='profile-links'>
              <table class='nav-icons'>
                <tr>
                  <td align='center'>
                    <a href='http://accounting1.armgroup.lcl/ajera/' target='_blank'> 
                      <img src='img/ajera.png' width=30 height=30 />
                      Ajera
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
                </tr>
                <tr>
                  <td align='center'>
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
                        <a href='?page=pending'> 
                          <img src='img/pending-red.png' />
                          Pending
                        </a>
                    ";
                  }
                }
            echo "
                  </td>
                  <td align='center'>
                    <a href='?page=article&articleid=112'> 
                      <img src='img/manual.png' />
                      eManual
                    </a>
                  </td>
                  <td align='center'>
                    <a href='?page=profile&profileid=$_SESSION[id]'> 
                      <img src='$_SESSION[picture]?" . rand(1, 9999) . "' width=30 height=30 />
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

      echo "
        <div class='navlinkbar'>
          <table cellpadding=0 cellspacing=0 width=100% style='margin:0px; padding:0px;'>
            <tr>
              <td>
                <a href='?page=home' class='navlink'>Home</a><a href='?page=people' class='navlink'>People</a><a href='?page=resources' class='navlink'>Resources</a><a href='?page=expertise' class='navlink'>Expertise</a><a href='?page=help' class='navlink'>I.T. Support</a>";
                //echo "<a href='?page=safety' class='navlink'>Safety</a>";
                //echo "<a href='?page=hr' class='navlink'>H.R.</a>";
                //echo "<a href='?page=training' class='navlink'>Training</a>";
                if (isset($_SESSION['user']) && $_SESSION['user'] == 'nhare') {
                  echo "<a href='?page=offices' class='navlink'>Offices</a>";
                }
                  echo "<a href='?page=forum' class='navlink'>Forum</a>";

      echo "
              </td>
              <td align='right'>

                <form method='post' action='?page=search'>
                  <input type='text' name='search' size='15' placeholder='Search' /> &nbsp; 
              </td>
              <td align='right'>
                  <input style='margin-top: 6px; margin-left: 0px; margin-right: 3px;' type='image' src='img/search.png' align='right' alt='Search'>&nbsp;
                </form>
              </td>
            </tr>
          </table>
        </div>
  ";
  if ($page == 'home') {
    echo "
      <div class='banner' id='banner'>
  ";

  // $banners is set at line 6

    shuffle($banners);

    $count = 1;
    foreach ($banners as $banner) {
      echo "<img src='$banner' id='banner$count' width=855 height=150 ' ";
      if ($count != 1) { echo "class='hide'"; }
      echo " />";
      $count++;
    }

  echo "
    </div>
  ";
  }
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


    //////////  WORK SHARE  //////////

                echo "<h3><a href='?page=workshare' style='color:#ffffff; text-decoration:none;'>Work Share</a></h3>"; 
                  $wssql = "SELECT * FROM articles WHERE type = 'workshare' AND del = 'n' ORDER BY date DESC LIMIT 3 ";
                  $result = mysql_query($wssql);
                  if ($result) {
                    echo "<ul>";
                    while ($workshare = mysql_fetch_array($result)) {
                      echo "<li><a href='?page=article&articleid=$workshare[id]'>" . substr($workshare['title'], 0, 20) . "...</a><br />";
                    }
                    if ( hasPermission('workshare') ) {
                      echo "
                        <br />
                        <form method='post' action='?page=article'>
                          <input type='hidden' name='action' value='add' />
                          <input type='hidden' name='type' value='workshare' />
                          <input type='submit' value='Add Job' />
                        </form>
                      ";
                    } 
                    echo "</ul>";
                  }  
                  echo "</ul>";


    //////////  CAREER OPPORTUNITIES  //////////

                echo "<h3><a href='?page=careerops' style='color:#ffffff; text-decoration:none;'>Career Opportunities</a></h3>"; 
                  $jobsql = "SELECT * FROM articles WHERE type = 'job' AND del = 'n' ORDER BY date DESC LIMIT 3 ";
                  $result = mysql_query($jobsql);
                  if ($result) {
                    echo "<ul>";
                    while ($job = mysql_fetch_array($result)) {
                      echo "<li><a href='?page=article&articleid=$job[id]'>" . substr($job['title'], 0, 20) . "...</a><br />";
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
                  $eventsql = "SELECT * FROM articles WHERE type = 'event' AND startdate >= '$todaystart' and del='n' ORDER BY startdate ASC LIMIT 5";
                  $result = mysql_query($eventsql);
                  if ($result) {
                    echo '<ul>';
                    while ($event = mysql_fetch_array($result)) {
                      echo '<li><a href="?page=article&articleid=' . $event['id'] . '">' . substr($event['title'], 0, 15) . '...</a> ';
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
                <h3>Employment Anniversaries</h3>
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
      <br />
        ";
        
        include('footer.php'); 

    echo "</div>";

               //////////////
              //  FOOTER  //
             //////////////

    echo "

    </div> <!----WRAPPER END---->

  </body>
</html>
";
?>
