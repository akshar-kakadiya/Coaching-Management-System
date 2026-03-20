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
        .form-group label {
            font-weight: bold;
            color: #495057;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }
        .btn {
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            color: #fff;
            background-color: #007bff;
            border: none;
            margin-top: 10px;
        }
        .btn-success {
            background-color: #28a745;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            color: #495057;
        }
        .table td {
            color: #495057;
        }
        .action-buttons form {
            display: inline-block;
        }
        .action-buttons button {
            margin-right: 5px;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
        }
    </style>
</head>
<body>
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
    <h2>Fees Information</h2>
    <!-- Form for inserting and updating fee records -->
    <form method="post" class="mb-4">
        <?php if (isset($edit_data)): ?>
            <input type="hidden" name="update">
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
        <?php else: ?>
            <input type="hidden" name="insert">
        <?php endif; ?>
        <div class="form-group">
            <label for="standard_id">Standard:</label>
            <select name="standard_id" id="standard_id" class="form-control" onchange="toggleStreamDropdown()" required>
                <?php while ($standard = $standards_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($standard['id']); ?>" <?php if (isset($edit_data) && $edit_standard_id == $standard['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($standard['standard']); ?>
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
        <div class="form-group">
            <label for="stream">Stream:</label>
            <input type="text" name="stream" id="stream" class="form-control" value="<?php if (isset($edit_data)) echo htmlspecialchars($edit_stream); ?>" required>
        </div>
        <div class="form-group">
            <label for="fee">Fee:</label>
            <input type="number" name="fee" id="fee" class="form-control" value="<?php if (isset($edit_data)) echo htmlspecialchars($edit_fee); ?>" step="0.01" required>
        </div>
        <?php if (isset($edit_data)): ?>
            <button type="submit" class="btn btn-success">Update Fee</button>
        <?php else: ?>
            <button type="submit" class="btn btn-primary">Add</button>
        <?php endif; ?>
    </form>
    <!-- JavaScript to toggle stream dropdown -->
    <script>
        function toggleStreamDropdown() {
            var standardId = document.getElementById('standard_id').value;
            var streamDropdown = document.getElementById('stream_dropdown'); 
            // Check if the selected standard is 11 or 12
            if (standardId === '11' || standardId === '12') {
                streamDropdown.style.display = 'block'; // Show stream dropdown
            } else {
                streamDropdown.style.display = 'none'; // Hide stream dropdown
            }
        }
        // Call on page load
        toggleStreamDropdown();
    </script>
    <!-- Table displaying existing fee records -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Standard</th>
                <th>Stream</th>
                <th>Fee</th>
                <th>Actions</th>
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
                    <td class="action-buttons">
                        <!-- Edit Form -->
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="edit" class="btn btn-primary btn-sm">Edit</button>
                        </form>           
                        <!-- Delete Form -->
                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this record?');">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
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

<!-- CREATE TABLE fees_catalogue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    standard_id INT NOT NULL,
    stream VARCHAR(50),
    fee DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (standard_id) REFERENCES standards(id)
); -->
