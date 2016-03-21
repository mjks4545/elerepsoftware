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
$status = $_POST['currentstatus'];
$now = new DateTime(null, new DateTimeZone('Europe/London'));
$sql = "UPDATE devices SET statushistory = CONCAT_WS('|', statushistory, '$status'), history = CONCAT_WS('|', history, '".$now->format('H:i d/m/Y')."') WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header( 'Location: repairlist.php' );
} else {
    echo "Error updating record: " . $conn->error;
}
$conn->close();
} else {
    header( 'Location: index.php' );
}
?>
