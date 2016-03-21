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
$notes = trim($conn->real_escape_string($_POST['notes']));
$sql = "UPDATE devices SET notes = '$notes' WHERE id='$id'";
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
