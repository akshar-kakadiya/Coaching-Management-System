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
// Handle insert, update, and delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insert'])) {
        $standard_id = $_POST['standard_id'];
        $stream = isset($_POST['stream']) ? $_POST['stream'] : '';
        $fee = $_POST['fee'];
        $sql = "INSERT INTO fees_catalogue (standard_id, stream, fee) VALUES ('$standard_id', '$stream', '$fee')";
        if ($conn->query($sql) === TRUE) {
            // Success
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $standard_id = $_POST['standard_id'];
        $stream = isset($_POST['stream']) ? $_POST['stream'] : '';
        $fee = $_POST['fee'];
        $sql = "UPDATE fees_catalogue SET standard_id='$standard_id', stream='$stream', fee='$fee' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            // Success
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM fees_catalogue WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            // Success
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } elseif (isset($_POST['edit'])) {
        // Handle edit action: Fetch the record to edit
        $edit_id = $_POST['id'];
        $edit_query = "SELECT * FROM fees_catalogue WHERE id = $edit_id";
        $edit_result = $conn->query($edit_query);
        if ($edit_result && $edit_result->num_rows > 0) {
            $edit_data = $edit_result->fetch_assoc();
            $edit_standard_id = $edit_data['standard_id'];
            $edit_stream = $edit_data['stream'];
            $edit_fee = $edit_data['fee'];
        }
    }
}
// Fetch all standards
$standards_result = $conn->query("SELECT * FROM standards");
// Fetch all fees records with standards and streams
$fees_result = $conn->query("SELECT fees_catalogue.id, standards.standard, fees_catalogue.stream, fees_catalogue.fee 
                             FROM fees_catalogue 
                             JOIN standards ON fees_catalogue.standard_id = standards.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fees Information</title>  
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
        }body {
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
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: calc(100% - 20px);
            padding: 8px 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .alert {
            color: red;
            margin-bottom: 10px;
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
<div class="container">
    <h2>Fees Information</h2>
    <!-- Form for inserting and updating fee records -->
    
    <!-- Table displaying existing fee records -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Standard</th>
                <th>Stream</th>
                <th>Fee</th>
            </tr>
        </thead>
        <tbody>
            <?php $sr_no = 1; ?>
            <?php while ($row = $fees_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $sr_no++; ?></td>
                    <td><?php echo htmlspecialchars($row['standard']); ?></td>
                    <td><?php echo htmlspecialchars($row['stream']); ?></td>
                    <td><?php echo htmlspecialchars($row['fee']); ?></td>
                    
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
// Close connection
$conn->close();
?>