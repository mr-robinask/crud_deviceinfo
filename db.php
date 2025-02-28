<?php
$servername = "localhost"; // localhost for local MySQL server
$username = "takerhat_todo"; // default username for MySQL in XAMPP is 'root'
$password = "AdmiNPass##143"; // no password by default for 'root' in XAMPP
$dbname = "takerhat_device_management_db"; // your database name


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
