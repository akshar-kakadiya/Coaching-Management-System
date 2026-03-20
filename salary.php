<?php
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

// Function to sanitize input data
function sanitize_input($data, $conn) {
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Initialize variables
$id = "";
$teacher_id = "";
$salary = "";
$payment_date = "";

// Fetch teachers for drop-down
$teachers_sql = "SELECT id, name FROM teachers";
$teachers_result = $conn->query($teachers_sql);

// Fetch salary details for edit
if (isset($_POST['edit_salary'])) {
    $id = sanitize_input($_POST['id'], $conn);

    // Fetch salary details from database
    $sql = "SELECT * FROM salary_history WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $teacher_id = $row['teacher_id'];
        $salary = $row['salary'];
        $payment_date = $row['payment_date'];
    }
}

// Insert or update salary
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_salary'])) {
        $teacher_id = sanitize_input($_POST["teacher_id"], $conn);
        $salary = sanitize_input($_POST["salary"], $conn);
        $payment_date = sanitize_input($_POST["payment_date"], $conn);

        $sql = "INSERT INTO salary_history (teacher_id, salary, payment_date) 
                VALUES ('$teacher_id', '$salary', '$payment_date')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Salary added successfully.");</script>';
        } else {
            echo '<script>alert("Error adding salary: ' . $conn->error . '");</script>';
        }
    } elseif (isset($_POST['update_salary'])) {
        $id = sanitize_input($_POST["id"], $conn);
        $teacher_id = sanitize_input($_POST["teacher_id"], $conn);
        $salary = sanitize_input($_POST["salary"], $conn);
        $payment_date = sanitize_input($_POST["payment_date"], $conn);

        $sql = "UPDATE salary_history SET teacher_id='$teacher_id', salary='$salary', payment_date='$payment_date' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Salary updated successfully.");</script>';
        } else {
            echo '<script>alert("Error updating salary: ' . $conn->error . '");</script>';
        }
    } elseif (isset($_POST['delete_salary'])) {
        $id = sanitize_input($_POST["id"], $conn);

        $sql = "DELETE FROM salary_history WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Salary deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error deleting salary: ' . $conn->error . '");</script>';
        }
    }
}

// Fetch all salary entries
$salary_sql = "SELECT sh.id, t.name AS teacher_name, sh.salary, sh.payment_date 
               FROM salary_history sh 
               JOIN teachers t ON sh.teacher_id = t.id";
$salary_result = $conn->query($salary_sql);

// Fetch teachers for drop-down excluding those who already have salary entries
$teachers_sql = "SELECT id, name FROM teachers 
                WHERE id NOT IN (SELECT DISTINCT teacher_id FROM salary_history)";
$teachers_result = $conn->query($teachers_sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Salary Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            margin-left: 30%;
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
            
        .sidebar img {
                margin-right: 10px;
                width: 20px; /* Adjust size as needed */
                height: 20px; /* Adjust size as needed */
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
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }
        .container input, .container select {
            width: calc(100% - 22px);
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container button {
            width: 30%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px 1%;
        }
        .container button:hover {
            background-color: #45a049;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .container table, .container th, .container td {
            border: 1px solid #ccc;
        }
        .container th, .container td {
            padding: 10px;
            text-align: left;
        }
        .alert {
            color: red;
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
<div class="container">
    <h1>Manage Teacher Salaries</h1>

    <!-- Add or Update Salary Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div>
            <label for="teacher_id">Teacher Name:</label>
            <select id="teacher_id" name="teacher_id" required>
                <option value="">Select Teacher</option>
                <?php while ($row = $teachers_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($teacher_id == $row['id']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label for="salary">Salary Amount:</label>
            <input type="number" id="salary" name="salary" step="0.01" value="<?php echo $salary; ?>" required>
        </div>
        <div>
            <label for="payment_date">Payment Date:</label>
            <input type="date" id="payment_date" name="payment_date" value="<?php echo $payment_date; ?>" required>
        </div>
        <div>
            <?php if ($id): ?>
                <button type="submit" name="update_salary" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Update</button>
            <?php else: ?>
                <button type="submit" name="add_salary" style="background-color: #4CAF50; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Add Salary</button>
            <?php endif; ?>
            <button type="reset">Reset</button>
        </div>
    </form>

    <!-- Salary Table -->
    <table>
        <thead>
            <tr>
                <th>Teacher Name</th>
                <th>Salary Amount</th>
                <th>Payment Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $salary_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['salary']); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="edit_salary" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Edit</button>
                        </form>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_salary" onclick="return confirm('Are you sure you want to delete this salary record?');" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; margin-left: 5px;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<!-- CREATE TABLE salary_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
); -->