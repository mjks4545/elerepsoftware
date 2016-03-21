<?php
session_start();
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() == true) {
include('config.php');
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$id = $_POST['id'];
$i = $_POST['i'];
$owner = $_REQUEST['owner'];
$sql = "SELECT * FROM `invoice` WHERE id='$id'";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      global $summary, $cost;
      $summary = explode('|', $row['summary']);
      $cost = explode('|', $row['cost']);
      unset($summary[$i]);
      unset($cost[$i]);
      $summary = implode("|", $summary);
      $cost = implode("|", $cost);
    }
  }
  $sql = "UPDATE `invoice` SET summary = '$summary', cost = '$cost' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header( "Location: updateinvoice.php?id=".$id."&owner=".$owner );
} else {
    echo "Error updating record: " . $conn->error;
}
$conn->close();
} else {
    header( 'Location: index.php' );
}
?>
