<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coaching_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// Fetch user information based on the logged-in username
$username = $_SESSION['username'];
$sql = "SELECT * FROM students WHERE name = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "<p>No information found for this student.</p>";
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            background-color: #343a40;
            color: #ffffff;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
            overflow-y: auto;
        }
        .sidebar a {
            color: #ffffff;
            padding: 20px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .sidebar img {
            margin-right: 10px;
            width: 20px; /* Adjust size as needed */
            height: 20px; /* Adjust size as needed */
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            overflow-y: auto;
            background-color: #fff;
        }
        .main-content h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .info-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .info-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-container table th,
        .info-container table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .info-container table th {
            background-color: #f4f4f9;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="user_dashboard.php"><img src="dashboard.png"> Dashboard</a>
        <a href="user_info.php"><img src="dashboard.png"> Your Information</a>
        <a href="user_attendance.php"><img src="dashboard.png"> Attendance</a>
        <a href="all_user.php"><img src="dashboard.png"> Students</a>
        <a href="user_fee_catalogue.php"><img src="fees_catalogue.png"> Fees Catalogue</a>
        <a href="user_login.php"><img src="logout.png"> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Your Information</h1>

        <!-- User Information Table -->
        <div class="info-container">
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Class</td>
                        <td><?php echo htmlspecialchars($student['class']); ?></td>
                    </tr>
                    <tr>
                        <td>Stream</td>
                        <td><?php echo htmlspecialchars($student['stream']); ?></td>
                    </tr>
                    <tr>
                        <td>Contact No</td>
                        <td><?php echo htmlspecialchars($student['contact']); ?></td>
                    </tr>
                    <tr>
                        <td>Email ID</td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><?php echo htmlspecialchars($student['address']); ?></td>
                    </tr>
                </tbody>
            </table>    
        </div>
    </div>
</body>
</html>
