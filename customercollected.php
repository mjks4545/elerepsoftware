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
$sql = "SELECT * FROM devices";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
<html>
<head>
<style>
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
      <th>Reference</th>
      <th>Model</th>
      <th>Manufacturer</th>
      <th>Identifier</th>
      <th>Status</th>
      <th>Assigned Engineer</th>
      <th>Received Date</th>
      <th>Completed Date</th>
      <th>Additional Notes</th>
      <th>Print Label</th>
    </tr>
    <tr class="warning no-result">
      <td colspan="10"><i class="fa fa-warning"></i> No result</td>
    </tr>
  </thead>
<tbody>
      <tr><td colspan="10"><h3>Customer Collected</h3></td></tr>
<?php
mysqli_data_seek($result, 0);
while($row = $result->fetch_assoc()) {
  $history = explode('|', $row['history']);
  $status = explode('|', $row['statushistory']);
  $owner = $row['owner'];
  if(end($status)=='Customer Collected') {
?>
<tr class="success">
  <td><a href="track/<?=$row["id"];?>"><?=$row["id"];?></a></td>
  <td><?=$row["model"];?></td>
  <td><?=$row["manufacturer"];?></td>
  <td><?=$row["identifier"];?></td>
  <td><?=end($status);?></td>
  <td><?=$row["assignedengineer"];?></td>
  <td><?=$history[0];?></td>
  <td><?=end($history);?></td>
  <td>
    <form class="form center-block" role="form" method="post" action="../updatenotes.php">
      <div class="form-group">
        <label class="sr-only" for="notes">Notes:</label>
        <textarea class="form-control" rows="3" name="notes" placeholder="<?=$row["notes"];?>"><?=$row["notes"];?></textarea>
      </div>
      <input type="hidden" name="id" value="<?=$row["id"];?>">
      <input type="submit" class="btn btn-primary btn-block" name="submit" value="Save Notes">
    </form>
  </td>
  <td><button type="button" class="btn btn-primary btn-xs" onclick="window.open('customerreceipt.php?id=<?=$row['id'];?>&amp;date=<?=htmlspecialchars(substr($history[0],6));?>&amp;owner=<?=htmlspecialchars($owner);?>', '_blank', 'location=no,width=240,scrollbars=no,status=no');">Print Receipt</button></td>
</tr>
<?php } } ?>
</tbody>
</table>
</div>
</div>
<?php
} else { echo "<a href='index.php'>No devices in repair list</a>"; }
$conn->close();
} else { header( 'Location: index.php' ); }
?>
<?php include 'include/footer.php';?>
