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

// Initialize variables
$student_id = "";
$fees_amount = "";
$date = "";
$error_message = "";

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Fetch students who do not have existing fee records
$sql_students = "SELECT * FROM students WHERE NOT EXISTS (SELECT 1 FROM fees WHERE fees.student_id = students.id)";
$result_students = $conn->query($sql_students);

// Fetch student details based on selected ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = sanitize_input($_POST['student_id']);
    
    // Fetch student details from database
    $sql_student_details = "SELECT * FROM students WHERE id = $student_id";
    $result_student_details = $conn->query($sql_student_details);

    if ($result_student_details->num_rows == 1) {
        $row_student = $result_student_details->fetch_assoc();
        $student_name = $row_student['name'];
        $class = $row_student['class'];
        $stream = $row_student['stream'];
    } else {
        $error_message = "Student not found.";
    }
}

// Insert fees record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $student_id = sanitize_input($_POST["student_id"]);
    $fees_amount = sanitize_input($_POST["fees_amount"]);
    $date = sanitize_input($_POST["date"]);

    // Insert into fees table
    $sql_insert_fees = "INSERT INTO fees (student_id, amount, date) VALUES ('$student_id', '$fees_amount', '$date')";

    if ($conn->query($sql_insert_fees) === TRUE) {
        echo '<script>alert("Fees added successfully."); window.location.href="fees.php";</script>';
    } else {
        $error_message = "Error adding fees: " . $conn->error;
    }
}

// Update fees record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $fees_id = sanitize_input($_POST["fees_id"]);
    $fees_amount = sanitize_input($_POST["fees_amount"]);
    $date = sanitize_input($_POST["date"]);

    // Update fees in the database
    $sql_update_fees = "UPDATE fees SET amount = '$fees_amount', date = '$date' WHERE id = $fees_id";

    if ($conn->query($sql_update_fees) === TRUE) {
        echo '<script>alert("Fees updated successfully."); window.location.href="fees.php";</script>';
    } else {
        $error_message = "Error updating fees: " . $conn->error;
    }
}

// Delete fees record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $fees_id = sanitize_input($_POST["fees_id"]);

    // Delete fees from the database
    $sql_delete_fees = "DELETE FROM fees WHERE id = $fees_id";

    if ($conn->query($sql_delete_fees) === TRUE) {
        echo '<script>alert("Fees deleted successfully."); window.location.href="fees.php";</script>';
    } else {
        $error_message = "Error deleting fees: " . $conn->error;
    }
}

// Fetch all fees records with student details
$sql_fees = "SELECT fees.*, students.name AS student_name, students.class, students.stream FROM fees INNER JOIN students ON fees.student_id = students.id";
$result_fees = $conn->query($sql_fees);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Fees Management</title>
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
        <h1>Manage Student Fees</h1>

        <!-- Error Message -->
        <?php if (!empty($error_message)): ?>
            <p class="alert"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Select Student Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="student_id">Select Student:</label>
                <select name="student_id" id="student_id" required>
                    <option value="">Select a student</option>
                    <?php while ($row_students = $result_students->fetch_assoc()): ?>
                        <option value="<?php echo $row_students['id']; ?>" <?php echo ($student_id == $row_students['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_students['name'] . ' - Class ' . $row_students['class'] . ($row_students['stream'] ? ' (' . $row_students['stream'] . ')' : '')); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Show Details</button>
            </div>
        </form>

        <!-- Display Student Details -->
        <?php if (isset($student_name)): ?>
            <h2>Student Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
            <p><strong>Class:</strong> <?php echo htmlspecialchars($class); ?></p>
            <?php if ($stream): ?>
                <p><strong>Stream:</strong> <?php echo htmlspecialchars($stream); ?></p>
            <?php endif; ?>

            <!-- Add Fees Form -->
            <h2>Add Fees</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <div class="form-group">
                    <label for="fees_amount">Fees Amount:</label>
                    <input type="number" id="fees_amount" name="fees_amount" value="<?php echo $fees_amount; ?>" required>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $date; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit">Add Fees</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Fees Table -->
        <h2>Student Fees Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Stream</th>
                    <th>Fees Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sr_no = 1;
                while ($row_fees = $result_fees->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $sr_no++; ?></td>
                        <td><?php echo htmlspecialchars($row_fees['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($row_fees['class']); ?></td>
                        <td><?php echo htmlspecialchars($row_fees['stream']); ?></td>
                        <td><?php echo htmlspecialchars($row_fees['amount']); ?></td>
                        <td><?php echo htmlspecialchars($row_fees['date']); ?></td>
                        <td>
                            <!-- Update Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline-block;">
                                <input type="hidden" name="fees_id" value="<?php echo $row_fees['id']; ?>">
                                <input type="hidden" name="student_id" value="<?php echo $row_fees['student_id']; ?>">
                                <input type="hidden" name="fees_amount" value="<?php echo $row_fees['amount']; ?>">
                                <input type="hidden" name="date" value="<?php echo $row_fees['date']; ?>">
                                <button type="submit" name="edit" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Edit</button>
                            </form>
                            
                            <!-- Delete Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline-block;">
                                <input type="hidden" name="fees_id" value="<?php echo $row_fees['id']; ?>">
                                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this record?')" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<!-- CREATE TABLE fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id)
); -->