<?php
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

// Fetch total students
$students_result = $conn->query("SELECT COUNT(*) AS total_students FROM students");
$total_students = $students_result->fetch_assoc()['total_students'];

// Fetch total teachers
$teachers_result = $conn->query("SELECT COUNT(*) AS total_teachers FROM teachers");
$total_teachers = $teachers_result->fetch_assoc()['total_teachers'];

// Fetch total standards
$standards_result = $conn->query("SELECT COUNT(*) AS total_standards FROM standards");
$total_standards = $standards_result->fetch_assoc()['total_standards'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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
        .container {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h3 {
            margin: 0;
            color: #007bff;
        }
        .card p {
            margin: 5px 0;
            color: #495057;
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

    <!-- Dashboard Content -->
    <div class="container">
        <h2>User Dashboard</h2>
        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo htmlspecialchars($total_students); ?></p>
        </div>
        <div class="card">
            <h3>Total Teachers</h3>
            <p><?php echo htmlspecialchars($total_teachers); ?></p>
        </div>
        <div class="card">
            <h3>Total Standards</h3>
            <p><?php echo htmlspecialchars($total_standards); ?></p>
        </div>
    </div>
</body>
</html>
