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

// Function to count total students
function getTotalStudents($conn) {
    $sql = "SELECT COUNT(*) AS total_students FROM students";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_students'];
    } else {
        return 0;
    }
}

// Function to count total teachers
function getTotalTeachers($conn) {
    $sql = "SELECT COUNT(*) AS total_teachers FROM teachers";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_teachers'];
    } else {
        return 0;
    }
}

// Function to count total standards (courses)
function getTotalStandards($conn) {
    $sql = "SELECT COUNT(*) AS total_standards FROM standards";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_standards'];
    } else {
        return 0;
    }
}

// Function to calculate total fees collected
function getTotalFeesCollected($conn) {
    $sql = "SELECT SUM(amount) AS total_fees FROM fees";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_fees'];
    } else {
        return 0;
    }
}

// Hardcoded upcoming events

// Fetch total counts
$total_students = getTotalStudents($conn);
$total_teachers = getTotalTeachers($conn);
$total_standards = getTotalStandards($conn);

// Fetch total fees collected
$total_fees_collected = getTotalFeesCollected($conn);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>College Management Dashboard</title>
<!-- Link external CSS file using style attribute -->
<style>
    /* Custom styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
        }

        .dashboard-header {
            background-color: #343a40;
            color: #ffffff;
            padding: 5px;
            padding-left: 10%;
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 2.5rem;
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
        }

        .dashboard-section {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .dashboard-section h2 {
            margin-top: 0;
        }

        .widget-icon {
            font-size: 2rem;
            margin-right: 10px;
        }

        .widget-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .widget-content {
            font-size: 1.2rem;
        }

        .list-group-item {
            font-size: 1.2rem;
        }

        .footer {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 1px;
            position: relative;
            bottom: 0;
            width: 100%;
        }

</style>
</head>
<body>

<div class="dashboard-header">
    <h1>Coaching Management System</h1>
</div>


<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <a href="dashboard.php"><img src="home.png"> Dashboard</a>
    <a href="standards.php"><img src="home2.png"> Standards</a>
    <a href="teachers.php"><img src="teachers.png"> Teachers</a>
    <a href="students.php"><img src="students.png"> Students</a>
    <a href="fees.php"><img src="fees.png"></i> Fees</a>
    <a href="salary.php"><img src="salary.png"></i> Salary</a>
    <a href="fees_catalogue.php"><img src="fees_catalogue.png"></i> Fees Information</a>
    <a href="attendance.php"><img src="fees_catalogue.png"></i> Attendance</a>
    <a href="index.php"><img src="logout.png"> Logout</a>
</div>

<div class="main-content">
    <div class="dashboard-section">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title widget-title"><i class="fas fa-user-graduate"></i> Total Students</h5>
                        <p class="card-text widget-content"><?php echo $total_students; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title widget-title"><i class="fas fa-chalkboard-teacher"></i> Total Teachers</h5>
                        <p class="card-text widget-content"><?php echo $total_teachers; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title widget-title"><i class="fas fa-book"></i> Total Standards</h5>
                        <p class="card-text widget-content"><?php echo $total_standards; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events Section -->
    

    <!-- Total Fees Collected Section -->
    <div class="dashboard-section">
        <h2>Total Fees Collected</h2>
        <p>Total amount collected from fees: <strong><?php echo $total_fees_collected; ?></strong></p>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 College Management System. All rights reserved.</p>
</div>

</body>
</html>