<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "coaching_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $standard = $_POST['standard'];

        $sql = "INSERT INTO standards (standard) VALUES ('$standard')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $standard = $_POST['standard'];

        $sql = "UPDATE standards SET standard='$standard' WHERE id=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM standards WHERE id=$id";
        $conn->query($sql);
    }
}

// Retrieve standards data
$standards_result = $conn->query("SELECT * FROM standards");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standards Management</title>
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

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar img {
            margin-right: 10px;
            width: 20px; /* Adjust size as needed */
            height: 20px; /* Adjust size as needed */
        }

        
        .main-content {
            margin-left: 200px;
            padding: 20px;
            flex: 1;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
            width: 100%;
            max-width: 120px;
            box-sizing: border-box;
        }
        .container button:hover {
            background-color: #45a049;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .container table th,
        .container table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .container table th {
            background-color: #f2f2f2;
        }
        .container table td {
            vertical-align: top;
        }
        .container table tr:hover {
            background-color: #f2f2f2;
        }
        .container form {
            text-align: left;
            margin-bottom: 20px;
        }
        .container form div {
            margin-bottom: 10px;
        }
        .container form label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }
        .container form input[type="text"],
        .container form input[type="number"] {
            width: calc(100% - 130px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .container form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1>Manage Standards</h1>

            <!-- Add Standard Form -->
            <form method="post">
                <div>
                    <label for="standard">Standard:</label>
                    <input type="number" id="standard" name="standard" min="1" max="12" required>
                </div>
                <div>
                    <input type="submit" name="add" value="Add Standard">
                </div>
            </form>

            <!-- Standards List -->
            <table>
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Standard</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sr_no = 1; // Initialize serial number
                    while ($row = $standards_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $sr_no++; ?></td> <!-- Increment serial number -->
                        <td><?php echo $row['standard']; ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="standard" value="<?php echo $row['standard']; ?>">
                                <button type="submit" name="edit" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Edit</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this record?')" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Update Standard Form (only shown when editing) -->
            <?php if (isset($_POST['edit'])): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                <div>
                    <label for="standard">Standard:</label>
                    <input type="number" id="standard" name="standard" value="<?php echo $_POST['standard']; ?>" min="1" max="12" required>
                </div>
                <div>
                    <input type="submit" name="update" value="Update Standard">
                </div>
            </form>
            <?php endif; ?>

            <!-- Error Message -->
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div style="color: red; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
    </div>
</body>
</html>

<!-- CREATE TABLE standards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    standard INT NOT NULL,
    stream VARCHAR(50) NOT NULL
); -->