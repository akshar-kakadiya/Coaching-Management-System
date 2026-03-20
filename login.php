<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "coaching_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    $sql = "SELECT * FROM admin WHERE admin_id='$admin_id' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login success
        $_SESSION['admin_id'] = $admin_id;
        header("Location: dashboard.php"); // redirect to dashboard
        exit();
    } else { 
        // Login failed
        $_SESSION['error'] = "Invalid Admin ID or Password";
        header("Location: index.php"); // redirect back to login page
        exit();
    }
}

$conn->close();
?>
<!-- CREATE TABLE admin (
    admin_id VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
); -->