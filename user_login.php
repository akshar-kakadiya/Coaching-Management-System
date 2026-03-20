<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coaching_management_system";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
 // Ensure you have a separate file for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Password is the same for all users
    $default_password = "1234";

    // Corrected SQL query to use $username
    $query = "SELECT * FROM students WHERE name = '$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn)); // Display the actual error
    }

    if (mysqli_num_rows($result) == 1 && $password === $default_password) {
        $_SESSION['username'] = $username; // Start the session
        header('Location: user_dashboard.php'); // Redirect to the user dashboard
    } else {
        echo "<script>alert('Invalid Username or Password!');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #ff5f6d, #ffc371);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        .login-container img {
            width: 80px;
            height: auto;
            margin-bottom: 20px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .login-container .error-message {
            color: #ff0033;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .login-container label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
            color: #555;
            font-size: 16px;
        }
        .login-container input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            color: #333;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #ff5f6d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #ff4757;
        }
        .sidebar a {
            color: blue;
            padding: 20px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
    </style>
</head>

    <div class="login-container">
        <h2>User Login</h2>
        <form  method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit" value="Login">Login</button>
            </div>
        <div class="sidebar">
            <a href="index.php">Admin Login</a>
        </div>
        </form>
    </div>
    
</body>
</html>
