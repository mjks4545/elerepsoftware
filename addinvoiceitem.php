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
$owner = $_REQUEST['owner'];
$sql = "UPDATE `invoice` SET summary = CONCAT_WS('|', summary, '".trim($conn->real_escape_string($_POST['item']))."'), cost = CONCAT_WS('|', cost, '".trim($conn->real_escape_string($_POST['cost']))."') WHERE id='$id'";
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
