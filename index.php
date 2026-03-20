<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #ff5f6d, #ffc371);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        .login-container img {
            width: 80px;
            height: auto;
            margin-bottom: 20px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .login-container .error-message {
            color: #ff0033;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .login-container label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
            color: #555;
            font-size: 16px;
        }
        .login-container input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            color: #333;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #ff5f6d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #ff4757;
        }
        .sidebar a {
            color: blue;
            padding: 20px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="179310.png" alt="Coaching Class Logo">
        <h2>Admin Login</h2>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="login.php" method="post">
            <div>
                <label for="username">Admin Id:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <div class="sidebar">
                <a href="user_login.php">Student Login</a>
            </div>
        </form>
    </div>
</body>
</html>
