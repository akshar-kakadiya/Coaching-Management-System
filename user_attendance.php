<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Module - Coming Soon</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin-left: 30%;
            
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
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 20px;
        }
        h1 {
            color: #ff5f6d;
            font-size: 36px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #ff5f6d;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #ff4757;
        }
    </style>
</head>
<body>
<div class="sidebar">
        <a href="user_dashboard.php"><img src="dashboard.png"> Dashboard</a>
        <a href="user_info.php"><img src="dashboard.png"> Your Information</a>
        <a href="user_attendance.php"><img src="dashboard.png"> Attendance</a>
        <a href="all_user.php"><img src="dashboard.png"> Students</a>
        <a href="user_fee_catalogue.php"><img src="fees_catalogue.png"> Fees Catalogue</a>
        <a href="user_login.php"><img src="logout.png"> Logout</a>
    </div>
    <div class="container">
        <h1>Attendance Module - Coming Soon</h1>
        <p>We are currently working on the Attendance module. Please check back later for updates.</p>
        <a href="user_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
