<?php
$servername = "localhost"; // localhost for local MySQL server
$username = "the_username"; // default username for MySQL in XAMPP is 'root'
$password = "the_pass"; // no password by default for 'root' in XAMPP
$dbname = "the_db_name"; // your database name


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
