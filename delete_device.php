<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get device ID from URL and delete the device
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_sql = "DELETE FROM devices WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: crud.php");
    } else {
        echo "Error deleting device: " . $conn->error;
    }
} else {
    echo "Device ID not specified!";
    exit();
}
?>
