<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
  //database connection
     $conn = new PDO("mysql:host=$servername;dbname=tuition_centre", $username, $password);
     // set the PDO error mode to exception
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
catch(PDOException $conn)
    {
    echo "Connection failed: " . $conn->getMessage();
    }

?>