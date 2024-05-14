<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 20px;
            j
        }

        h2 {
            margin-top: 10px;
            text-align: center;
        }

        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Settings</h1>
    <h2>Profile Settings</h2>
    <form action="update_profile.php" method="post">
        <!-- Form fields for profile settings -->
        <button type="submit">Update Profile</button>
    </form>

    <h2>Notification Settings</h2>
    <form action="update_notification.php" method="post">
        <!-- Form fields for notification settings -->
        <button type="submit">Update Notification Settings</button>
    </form>

    <h2>Privacy Settings</h2>
    <form action="update_privacy.php" method="post">
        <!-- Form fields for privacy settings -->
        <button type="submit">Update Privacy Settings</button>
    </form>

    <h2>Account Settings</h2>
    <form action="update_account.php" method="post">
        <!-- Form fields for account settings -->
        <button type="submit">Update Account Settings</button>
    </form>

    <h2>Theme Settings</h2>
    <form action="update_theme.php" method="post">
        <!-- Form fields for theme settings -->
        <button type="submit">Update Theme Settings</button>
    </form>
</body>
</html>
