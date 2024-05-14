<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the database
    $check_query = "SELECT * FROM admins WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $username;
            $_SESSION['admin_id'] = $row['id']; // Set the admin_id in session
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: aquamarine;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        
    }

    .container {
        width: 400px;
        padding: 20px;
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        height: 400px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 10px;
        border-radius: 5px;
        padding: 5px;
    }

    input[type="text"],
    input[type="password"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #333;
        color: #fff;
        border: none;
        cursor: pointer;
        top: 5px;
        width: 50%;
    }

    input[type="submit"]:hover {
        background-color: #555;
    }

    p {
        text-align: center;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="admin_login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" pattern="[a-zA-Z]+\s[a-zA-Z]+\s?[a-zA-Z]*" title="Enter at least two and at most three names" value="<?php if(isset($_SESSION['admin'])) { echo $_SESSION['admin']; } ?>" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="admin_register.php">Register here</a></p>
    </div>
</body>
</html>
