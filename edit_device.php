<?php
session_start();
include 'db.php';

// Check if the user is logged in, else redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle device edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_device'])) {
    // Get the form data
    $device_id = $_POST['id'];
    $device_name = $_POST['device_name'];
    $ip_url = $_POST['ip_url'];
    $device_username = $_POST['device_username'];
    $device_password = $_POST['device_password'];
    $mobile_number = $_POST['mobile_number']; // Get mobile number
    $status = $_POST['status'];

    // Update device in the database
    $sql = "UPDATE devices SET device_name='$device_name', ip_url='$ip_url', username='$device_username', password='$device_password', mobile_number = '$mobile_number', status='$status' WHERE id='$device_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: crud.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
