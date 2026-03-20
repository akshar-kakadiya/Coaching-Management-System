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
$id = "";
$name = "";
$standard = "";
$stream = "";
$subject = "";
$contact = "";
$email = "";
$address = "";

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Fetch teacher details for edit
if (isset($_GET['edit'])) {
    $id = sanitize_input($_GET['edit']);
    
    // Fetch teacher details from database
    $sql = "SELECT * FROM teachers WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $standard = $row['standard'];
        $stream = $row['stream'];
        $subject = $row['subject'];
        $contact = $row['contact'];
        $email = $row['email'];
        $address = $row['address'];
    } else {
        echo '<script>alert("Teacher not found."); window.location.href="teachers.php";</script>';
        exit;
    }
}

// Update teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = sanitize_input($_POST["id"]);
    $name = sanitize_input($_POST["name"]);
    $standard = sanitize_input($_POST["standard"]);
    $stream = sanitize_input($_POST["stream"]);
    $subject = sanitize_input($_POST["subject"]);
    $contact = sanitize_input($_POST["contact"]);
    $email = sanitize_input($_POST["email"]);
    $address = sanitize_input($_POST["address"]);

    $sql = "UPDATE teachers SET name='$name', standard='$standard', stream='$stream', subject='$subject', 
            contact='$contact', email='$email', address='$address' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Teacher updated successfully."); window.location.href="teachers.php";</script>';
    } else {
        echo '<script>alert("Error updating teacher: ' . $conn->error . '");</script>';
    }
}

// Add teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = sanitize_input($_POST["name"]);
    $standard = sanitize_input($_POST["standard"]);
    $stream = sanitize_input($_POST["stream"]);
    $subject = sanitize_input($_POST["subject"]);
    $contact = sanitize_input($_POST["contact"]);
    $email = sanitize_input($_POST["email"]);
    $address = sanitize_input($_POST["address"]);

    $sql = "INSERT INTO teachers (name, standard, stream, subject, contact, email, address) VALUES 
            ('$name', '$standard', '$stream', '$subject', '$contact', '$email', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Teacher added successfully."); window.location.href="teachers.php";</script>';
    } else {
        echo '<script>alert("Error adding teacher: ' . $conn->error . '");</script>';
    }
}

// Delete teacher
if (isset($_GET['delete'])) {
    $id = sanitize_input($_GET['delete']);

    // Delete teacher from database
    $sql = "DELETE FROM teachers WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Teacher deleted successfully."); window.location.href="teachers.php";</script>';
    } else {
        echo '<script>alert("Error deleting teacher: ' . $conn->error . '"); window.location.href="teachers.php";</script>';
    }
}

// Fetch all standards
$standards_sql = "SELECT standard FROM standards";
$standards_result = $conn->query($standards_sql);

// Fetch all teachers
$sql = "SELECT * FROM teachers";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management System</title>
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
            width: 800px;
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
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
        .nav {
            width: 100%;
            background-color: #333;
            overflow: hidden;
            position: fixed;
            top: 0;
            z-index: 1000; /* Ensure the navbar stays on top */
        }
        .nav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .nav a:hover {
            background-color: #ddd;
            color: black;
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
    <h1>Manage Teachers</h1>

    <!-- Add Teacher Button -->
    <button onclick="showAddForm()">Add Teacher</button>

    <!-- Add or Update Teacher Form -->
    <div id="formContainer" style="<?php echo $id ? 'display: block;' : 'display: none;'; ?>">
        <h2><?php echo $id ? 'Update' : 'Add New'; ?> Teacher</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div>
                <label for="standard">Standard:</label>
                <select id="standard" name="standard" onchange="showStreamField()" required>
                <option value="">Select Standard</option>
                    <?php while($standard_row = $standards_result->fetch_assoc()): ?>
                <option value="<?php echo $standard_row['standard']; ?>" <?php if ($standard == $standard_row['standard']) echo 'selected'; ?>>
                    <?php echo $standard_row['standard']; ?>
                </option>
            <?php endwhile; ?>
                </select>
            </div>

            
            <div id="streamField" style="<?php echo ($standard == '11' || $standard == '12') ? 'display: block;' : 'display: none;'; ?>">
                <label for="stream">Stream:</label>
                <select id="stream" name="stream">
                    <option value="">Select Stream</option>
                    <option value="Science" <?php if ($standard >= '11' && $standard <= '12' && $stream == 'Science') echo 'selected'; ?>>Science</option>
                    <option value="Commerce" <?php if ($standard >= '11' && $standard <= '12' && $stream == 'Commerce') echo 'selected'; ?>>Commerce</option>
                </select>
            </div>
            <div>
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" required>
            </div>
            <div>
                <label for="contact">Contact No:</label>
                <input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" required>
            </div>
            <div>
                <label for="email">Email ID:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>" required>
            </div>
            <div>
                <?php if ($id != ""): ?>
                    <button type="submit" name="edit">Update Teacher</button>
                <?php else: ?>
                    <button type="submit" name="add">Add Teacher</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Teachers Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Standard</th>
                <th>Stream</th>
                <th>Subject</th>
                <th>Contact No</th>
                <th>Email ID</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['standard']); ?></td>
        <td><?php echo htmlspecialchars($row['stream']); ?></td>
        <td><?php echo htmlspecialchars($row['subject']); ?></td>
        <td><?php echo htmlspecialchars($row['contact']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['address']); ?></td>
        <td>
            <form method="get" action="teachers.php" >
                <input type="hidden" name="edit" value="<?php echo $row['id']; ?>">
                <button type="submit" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Edit</button>
            </form>
            <form method="get" action="teachers.php"  onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                <button type="submit" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
            </form>
        </td>
    </tr>
<?php endwhile; ?>

        </tbody>
    </table>
</div>

<script>
    function showAddForm() {
        document.getElementById("formContainer").style.display = "block";
    }

    function showStreamField() {
        var standardSelect = document.getElementById("standard");
        var streamField = document.getElementById("streamField");

        if (standardSelect.value == '11' || standardSelect.value == '12') {
            streamField.style.display = "block";
        } else {
            streamField.style.display = "none";
        }
    }
</script>
</body>
</html>

<!-- CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    standard VARCHAR(10) NOT NULL,
    stream VARCHAR(50),
    subject VARCHAR(255) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    address VARCHAR(200)
); -->
