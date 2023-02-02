<?php

$servername = "localhost";
$username = "user_shortener";
$password = "pass_shortener";
$dbname = "url_shortener";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

?>
