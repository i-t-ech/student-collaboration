<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];
$update_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['theme'])) {
        $theme = $_POST['theme'];
        // Update the user's theme preference in the database (replace with your own logic)
        // Example query:
        // $update_query = "UPDATE users SET theme = '$theme' WHERE id = '$user_id'";
        // $update_result = mysqli_query($conn, $update_query);
        
        // Simulate update success for demonstration
        $update_success = true;
    } else {
        // Handle the case where the 'theme' key is not present in the $_POST array
        $update_success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Theme</title>
    <style>
        /* Add your CSS styles here */
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

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Update Theme</h1>

    <?php if ($update_success) : ?>
        <p class="success">Theme updated successfully!</p>
    <?php endif; ?>

    <form action="update_theme.php" method="post">
        <label for="theme">Select Theme:</label>
        <select name="theme" id="theme">
            <option value="light">Light</option>
            <option value="dark">Dark</option>
        </select><br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>