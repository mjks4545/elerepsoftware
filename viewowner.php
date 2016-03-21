<?php
session_start();
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<?php
include('config.php');
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$id = $_REQUEST['owner'];
$sql = "SELECT * FROM `customers` WHERE id='$id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
<table class="table table-hover table-striped">
  <thead>
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Address</th>
      <th>City/Town</th>
      <th>Post Code</th>
      <th>Contact Number</th>
      <th>Devices</th>
    </tr>
  </thead>
  <tbody>
<?php
  while($row = $result->fetch_assoc()) {
?>
<tr>
  <td><?=$row['firstname'];?></td>
  <td><?=$row['lastname'];?></td>
  <td><?=$row['address'];?></td>
  <td><?=$row['citytown'];?></td>
  <td><?=$row['postcode'];?></td>
  <td><?=$row['contactnumber'];?></td>
  <td><button type="button" class="btn btn-primary btn-xs" onclick="window.open('customerdevices.php?owner=<?=htmlspecialchars($row['id']);?>', '_blank', 'location=no,width=900,height=500,scrollbars=no,status=no');">Devices</button></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php
} else {
    echo "<a href='index.php'>No customers in database</a>";
}
$conn->close();
} else {
    header( 'Location: index.php' );
}
?>
