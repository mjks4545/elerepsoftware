<?php
session_start();
include 'include/header.php';
include 'leftsidebar.php';
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
?>
<?php
include('config.php');
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM `customers`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
.btn
{
	margin-top:20px;
}
</style>
</head>
</html>
<div class="col-md-12 btn">
<div class="col-md-11"></div>
<div class="col-md-1"><a href="index.php?logout" class="btn btn-danger">Logout</a></div>
</div>
<div class="container col-md-12">
<div class="col-md-2"></div>
<div class="col-md-10">
<div class="form-group pull-right">
    <input type="text" class="search form-control" placeholder="What you looking for?">
</div>
<span class="counter pull-right"></span>
<table class="table table-hover table-striped results">
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
    <tr class="warning no-result">
      <td colspan="4"><i class="fa fa-warning"></i> No result</td>
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
</div>
</div>
<?php
} else {
    echo "<a href='index.php'>No customers in database</a>";
}
$conn->close();
} else {
    header( 'Location: index.php' );
}
?>
<?php include 'include/footer.php';?>
