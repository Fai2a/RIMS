<?php
$servername = "localhost";
$username = "root";
$password = "";  // replace with your actual password
$dbname = "rims";  // replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

