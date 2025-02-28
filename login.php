<?php
session_start();

// If the user is already logged in, redirect to the dashboard (crud.php)
if (isset($_SESSION['username'])) {
    header("Location: crud.php");
    exit();
}

$login_error = false;
$login_success = false;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php'; // Include the database connection file

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if username and password match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
    $result = $conn->query($sql);
    
    // If credentials are correct, start a session
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $login_success = true;
        header("Location: crud.php");
        exit();
    } else {
        $login_error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>
        body {
            background: linear-gradient(to right, #56ccf2, #2f80ed); /* Gradient background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form {
            animation: slideIn 0.5s ease-out;
            background: rgba(255, 255, 255, 0.9); /* Transparent card with white background */
            border-radius: 20px;
            padding: 40px;
            width: 400px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px); /* Blur effect */
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
            font-size: 16px;
            transition: opacity 0.5s ease-in-out;
        }

        .notification-error {
            background-color: #dc3545;
        }

        .login-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .card h4 {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 25px;
            padding: 15px;
            border: 1px solid #ddd;
        }

        .btn-login {
            border-radius: 25px;
            padding: 12px;
            font-size: 16px;
            background-color: #2f80ed;
            color: white;
            border: none;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-login i {
            margin-right: 10px; /* Add space between icon and text */
        }

        .btn-login:hover {
            background-color: #1d60a0;
        }

        .alert-danger {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-form">
            
            <!-- Logo Above the Login Form -->
            <div class="login-header">
                <img src="https://takerhatwifi.com/assets/images/white-logo.png" alt="Logo" class="login-logo"> <!-- Replace with your logo -->
                <h4>Enter Login Info</h4>
            </div>

            <!-- Show error notification if login fails -->
            <?php if ($login_error): ?>
                <div class="alert alert-danger">Invalid username or password!</div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>

    <!-- Success Notification -->
    <?php if ($login_success): ?>
        <div id="successNotification" class="notification">Login Successful!</div>
    <?php endif; ?>

    <!-- Error Notification -->
    <?php if ($login_error): ?>
        <div id="errorNotification" class="notification notification-error">Invalid login credentials!</div>
    <?php endif; ?>

    <script>
        // Display the notification after login
        window.onload = function() {
            const successNotification = document.getElementById('successNotification');
            const errorNotification = document.getElementById('errorNotification');

            // Show success notification if login is successful
            if (successNotification) {
                successNotification.style.display = 'block';
                setTimeout(function() {
                    successNotification.style.display = 'none';
                }, 3000);
            }

            // Show error notification if login fails
            if (errorNotification) {
                errorNotification.style.display = 'block';
                setTimeout(function() {
                    errorNotification.style.display = 'none';
                }, 3000);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
