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

// Fetch all students
$sql_students = "SELECT * FROM students";
$result_students = $conn->query($sql_students);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
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
        
        .main-content table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .main-content table th,
        .main-content table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
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
        <h1>All Student </h1>

        <!-- Button to Show Add Student Form -->
        

        <!-- Form for Adding/Editing Students -->
        

        <!-- Students Table -->
        <table>
            <thead>
                <tr>
                    <th>SR No</th> <!-- Added SR No column -->
                    <th>Name</th>
                    <th>Class</th>
                    <th>Stream</th>
                    <th>Contact No</th>
                    <th>Email ID</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Display student records
            $serial_no = 1; // Initialize serial number
            while ($row_stud = $result_students->fetch_assoc()): ?>
            <tr>
                <td><?php echo $serial_no++; ?></td> <!-- Display serial number -->
                <td><?php echo htmlspecialchars($row_stud['name']); ?></td>
                <td><?php echo htmlspecialchars($row_stud['class']); ?></td>
                <td><?php echo htmlspecialchars($row_stud['stream']); ?></td>
                <td><?php echo htmlspecialchars($row_stud['contact']); ?></td>
                <td><?php echo htmlspecialchars($row_stud['email']); ?></td>
                <td><?php echo htmlspecialchars($row_stud['address']); ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>

<!-- CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    class VARCHAR(50) NOT NULL,
    stream VARCHAR(50) DEFAULT NULL,
    contact VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL
); -->