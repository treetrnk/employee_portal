<?php 
  $location = array ();
  $location_negative = array ('hershey', 'columbia', 'state college', 'canonsburg', 'wilkesbarre');
  if (isset($_GET['limit'])) { $limit=$_GET['limit']; } else { $limit = 10; }
  if (isset($_GET['offset'])) { $offset=$_GET['offset']; } else { $offset = 0; }

  if (isset($_GET['post'])) { $_POST = unserialize(base64_decode($_GET['post'])); }
  if (isset($_POST['hershey'])) { array_push($location, $_POST['hershey']); }
  if (isset($_POST['columbia'])) { array_push($location, $_POST['columbia']); }
  if (isset($_POST['statecollege'])) { array_push($location, $_POST['statecollege']); }
  if (isset($_POST['canonsburg'])) { array_push($location, $_POST['canonsburg']); }
  if (isset($_POST['wilkesbarre'])) { array_push($location, $_POST['wilkesbarre']); }

  foreach ($location_negative as $i) {
    if (in_array($i, $location)) {
      $key = array_search($i, $location_negative);
      unset($location_negative[$key]);
    }
  }

  $sql = "SELECT * FROM staff WHERE leaveday IS NULL";        // START OF QUERY
  if (isset($_POST['search'])) {           // SEARCH BAR KEYWORDS
    $search = $_POST['search'];
    $sql = $sql . " AND fname LIKE '%" . $search . "%' OR lname LIKE '%" . $search . "%' OR title LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%' OR about LIKE '%" . $search . "%'";
  } else {
    $search = "";
  }
  if (count($location) == 1) {               // LOCATION CHECK BOXES
    $sql = $sql . " AND location LIKE '%" . $location[0] . "%'";
  } elseif (count($location) > 1 ) {
    foreach ($location_negative as $i) {
      $sql = $sql . " AND location NOT LIKE '%" . $i . "%'";
    }
  }
  $total_rows = mysql_num_rows(mysql_query($sql));
  $sql = $sql . " ORDER BY lname LIMIT " . $limit . " OFFSET " . $offset;         // END OF QUERY
  $result = mysql_query($sql);


?>

<h2>People</h2>

<!--- ###################### SEARCH BAR ##################### --->

<div class="greenbox" style="text-align: center;" >
  <form method="post" action="?page=people">
    <input type="text" name="search" value="<?php echo $search; ?>" size=75% autofocus /><input type="submit" value="Search" /><br /><br />
    <label>Canonsburg, PA <input type="checkbox" name="canonsburg" value="canonsburg" onchange="this.form.submit()" <?php if (in_array("canonsburg", $location)) { echo 'checked'; } ?> /></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label>Columbia, MD <input type="checkbox" name="columbia" value="columbia" onchange="this.form.submit()" <?php if (in_array("columbia", $location)) { echo 'checked'; } ?> /></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label>Hershey, PA <input type="checkbox" name="hershey" value="hershey" onchange="this.form.submit()" <?php if (in_array("hershey", $location, TRUE)) { echo 'checked'; } ?> /></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label>State College, PA <input type="checkbox" name="statecollege" value="state college" onchange="this.form.submit()"<?php if (in_array("state college", $location)) { echo 'checked'; } ?> /></label>&nbsp;&nbsp;&nbsp;&nbsp;
    <label>Wilkes-Barre, PA <input type="checkbox" name="wilkesbarre" value="wilkes-barre" onchange="this.form.submit()" <?php if (in_array("wilkes-barre", $location)) { echo 'checked'; } ?> /></label>
  </form> 
</div>

<br />
<br />

<?php 

  multipageNav($limit, $total_rows);

  if ($result) {
    $count = 0;
    while ($row = mysql_fetch_array($result)) {
      $count ++;
?>

<div class="people-list" style="height: 80px;<?php if ($count % 2 == 0) { echo 'background: #dedede;'; } ?>">
<a href="?page=profile&profileid=<?php echo $row['id']; ?>">
  <img src="<?php if ($row['picture']) { echo $row['picture']; } else { ?>img/no_pic1.png<?php } ?>" width="75" />
</a> 
<table width=50% class="people-info">
  <tr>
    <td width=50% align="right">
      <b>Phone:</b>
    </td>
    <td width=50%>
      <?php echo $row['phone']; ?>
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Extenstion:</b>
    </th>
    <td>
      <?php echo $row['ext']; ?>
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Email:</b>
    </td>
    <td>
      <a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a>
    </td>
  </tr>
</table>
<a href="?page=profile&profileid=<?php echo $row['id']; ?>"><b><?php echo $row['fname'] . " " . $row['lname']; ?></b></a>
<p><?php echo $row['title']; ?><br />
<?php echo $row['location']; ?></p>
</div>

<?php 
    }
  } else {
    echo mysql_error();
  }
  echo "<br />";
  multipageNav($limit, $total_rows);
?>

<!----

<div class="people-list">
<a href="?page=profile&profileid=3"><img src="img/no_pic1.png" width="75" /></a> 
<table width=50% class="people-info">
  <tr>
    <td width=50% align="right">
      <b>Phone:</b>
    </td>
    <td width=50%>
      717-508-0563
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Extenstion:</b>
    </th>
    <td>
      x1159
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Email:</b>
    </td>
    <td>
      <a href="mailto:jkreider@armgroup.net">jkreider@armgroup.net</a>
    </td>
  </tr>
</table>
<a href="?page=profile&profileid=3"><b>Josh Kreider</b></a>
<p>IT Administrator<br />
Hershey, PA</p>
</div>
<div class="people-list" style="background: #dedede;">
<a href="?page=profile&profileid=1"><img src="img/nate.jpg" width="75" /></a> 
<table width=50% class="people-info">
  <tr>
    <td width=50% align="right">
      <b>Phone:</b>
    </td>
    <td width=50%>
      717-781-0268
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Extenstion:</b>
    </th>
    <td>
      x1162
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Email:</b>
    </td>
    <td>
      <a href="mailto:nhare@armgroup.net">nhare@armgroup.net</a>
    </td>
  </tr>
</table>
<a href="?page=profile&profileid=nhare"><b>Nathan Hare</b></a>
<p>Help Desk Analyst<br />
Hershey, PA</p>
</div>
<div class="people-list">
<a href="?page=profile&profileid=jkreider"><img src="img/no_pic1.png" width="75" /></a> 
<table width=50% class="people-info">
  <tr>
    <td width=50% align="right">
      <b>Phone:</b>
    </td>
    <td width=50%>
      717-508-0563
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Extenstion:</b>
    </th>
    <td>
      x1159
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Email:</b>
    </td>
    <td>
      <a href="mailto:jkreider@armgroup.net">jkreider@armgroup.net</a>
    </td>
  </tr>
</table>
<a href="?page=profile&profileid=3"><b>Josh Kreider</b></a>
<p>IT Administrator<br />
Hershey, PA</p>
</div>
<div class="people-list" style="background: #dedede;">
<a href="?page=profile&profileid=1"><img src="img/nate.jpg" width="75" /></a> 
<table width=50% class="people-info">
  <tr>
    <td width=50% align="right">
      <b>Phone:</b>
    </td>
    <td width=50%>
      717-781-0268
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Extenstion:</b>
    </th>
    <td>
      x1162
    </td>
  </tr>
  <tr>
    <td align="right">
      <b>Email:</b>
    </td>
    <td>
      <a href="mailto:nhare@armgroup.net">nhare@armgroup.net</a>
    </td>
  </tr>
</table>
<a href="?page=profile&profileid=1"><b>Nathan Hare</b></a>
<p>Help Desk Analyst<br />
Hershey, PA</p>
</div>

---->
