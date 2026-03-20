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
// Initialize variables
$id = "";
$name = "";
$class = "";
$stream = "";
$contact = "";
$email = "";
$address = "";

// Function to sanitize input data
function sanitize_input($data, $conn) {
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Fetch all standards for dropdown
$sql_standards = "SELECT * FROM standards";
$result_standards = $conn->query($sql_standards);

// Fetch student details for edit
if (isset($_GET['edit'])) {
    $id = sanitize_input($_GET['edit'], $conn);
    
    // Fetch student details from database
    $sql = "SELECT * FROM students WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $class = $row['class'];
        $stream = $row['stream'];
        $contact = $row['contact'];
        $email = $row['email'];
        $address = $row['address'];
    } else {
        echo '<script>alert("Student not found."); window.location.href="students.php";</script>';
        exit;
    }
}

// Update student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = sanitize_input($_POST["id"], $conn);
    $name = sanitize_input($_POST["name"], $conn);
    $class = sanitize_input($_POST["class"], $conn);
    $contact = sanitize_input($_POST["contact"], $conn);
    $email = sanitize_input($_POST["email"], $conn);
    $address = sanitize_input($_POST["address"], $conn);

    // Only set stream if class is 11 or 12
    if ($class == '11' || $class == '12') {
        $stream = sanitize_input($_POST["stream"], $conn);
        $sql = "UPDATE students SET name='$name', class='$class', stream='$stream',
                contact='$contact', email='$email', address='$address' WHERE id=$id";
    } else {
        $sql = "UPDATE students SET name='$name', class='$class',
                contact='$contact', email='$email', address='$address' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Student updated successfully."); window.location.href="students.php";</script>';
    } else {
        echo '<script>alert("Error updating student: ' . $conn->error . '");</script>';
    }
}

// Insert new student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = sanitize_input($_POST["name"], $conn);
    $class = sanitize_input($_POST["class"], $conn);
    $contact = sanitize_input($_POST["contact"], $conn);
    $email = sanitize_input($_POST["email"], $conn);
    $address = sanitize_input($_POST["address"], $conn);

    // Only insert stream if class is 11 or 12
    if ($class == '11' || $class == '12') {
        $stream = sanitize_input($_POST["stream"], $conn);
        $sql = "INSERT INTO students (name, class, stream, contact, email, address) 
                VALUES ('$name', '$class', '$stream', '$contact', '$email', '$address')";
    } else {
        $sql = "INSERT INTO students (name, class, contact, email, address) 
                VALUES ('$name', '$class', '$contact', '$email', '$address')";
    }

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Student added successfully."); window.location.href="students.php";</script>';
    } else {
        echo '<script>alert("Error adding student: ' . $conn->error . '");</script>';
    }
}

// Delete student
if (isset($_GET['delete'])) {
    $id = sanitize_input($_GET['delete'], $conn);

    $sql = "DELETE FROM students WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Student deleted successfully."); window.location.href="students.php";</script>';
    } else {
        echo '<script>alert("Error deleting student: ' . $conn->error . '");</script>';
    }
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
        .main-content button {
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
            max-width: 150px;
            box-sizing: border-box;
        }
        .main-content button:hover {
            background-color: #45a049;
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
        .main-content table th {
            background-color: #f2f2f2;
        }
        .main-content table td {
            vertical-align: top;
        }
        .main-content table tr:hover {
            background-color: #f2f2f2;
        }
        .main-content form {
            text-align: left;
            margin-bottom: 20px;
        }
        .main-content form div {
            margin-bottom: 10px;
        }
        .main-content form label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }
        .main-content form input[type="text"],
        .main-content form input[type="email"],
        .main-content form input[type="number"] {
            width: calc(100% - 130px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .main-content form select {
            width: calc(100% - 130px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .main-content form input[type="submit"] {
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
        .main-content form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .main-content .alert {
            color: red;
            margin-bottom: 10px;
        }
        #addStudentForm {
            display: none;
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
        <h1>Student Management</h1>

        <!-- Button to Show Add Student Form -->
        <button id="showAddFormButton">Add Student</button>

        <!-- Form for Adding/Editing Students -->
        <form id="addStudentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div>
                <label for="class">Class:</label>
                <select id="class" name="class" required>
                    <option value="">Select Class</option>
                    <option value="5" <?php if ($class == '5') echo 'selected'; ?>>5</option>
                    <option value="6" <?php if ($class == '6') echo 'selected'; ?>>6</option>
                    <option value="7" <?php if ($class == '7') echo 'selected'; ?>>7</option>
                    <option value="8" <?php if ($class == '8') echo 'selected'; ?>>8</option>
                    <option value="9" <?php if ($class == '9') echo 'selected'; ?>>9</option>
                    <option value="10" <?php if ($class == '10') echo 'selected'; ?>>10</option>
                    <option value="11" <?php if ($class == '11') echo 'selected'; ?>>11</option>
                    <option value="12" <?php if ($class == '12') echo 'selected'; ?>>12</option>
                </select>
            </div>
            <div id="stream_div" style="<?php echo ($class == '11' || $class == '12') ? 'display:block;' : 'display:none;'; ?>">
                <label for="stream">Stream:</label>
                <select id="stream" name="stream">
                    <option value="">Select Stream</option>
                    <option value="Science" <?php if ($stream == 'Science') echo 'selected'; ?>>Science</option>
                    <option value="Commerce" <?php if ($stream == 'Commerce') echo 'selected'; ?>>Commerce</option>
                </select>
            </div>
            <div>
                <label for="contact">Contact No:</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
            </div>
            <div>
                <label for="email">Email ID:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div>
                <?php if ($id): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="submit" name="edit" value="Update Student">
                <?php else: ?>
                    <input type="submit" name="add" value="Add Student">
                <?php endif; ?>
            </div>
        </form>

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
                    <th>Actions</th>
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
                <td>
                    <form method="get" action="students.php">
                        <input type="hidden" name="edit" value="<?php echo $row_stud['id']; ?>">
                        <button type="submit" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Edit</button>
                    </form>
                    <form method="get" action="students.php" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        <input type="hidden" name="delete" value="<?php echo $row_stud['id']; ?>">
                        <button type="submit" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Show/hide stream dropdown based on class selection
        document.getElementById('class').addEventListener('change', function() {
            var streamDiv = document.getElementById('stream_div');
            if (this.value == '11' || this.value == '12') {
                streamDiv.style.display = 'block';
            } else {
                streamDiv.style.display = 'none';
            }
        });

        // Show/hide add student form
        document.getElementById('showAddFormButton').addEventListener('click', function() {
            var form = document.getElementById('addStudentForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        });
    </script>
</body>
</html>

<!-- CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    class INT NOT NULL,
    stream VARCHAR(50),
    contact VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL
); -->