<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'connect.php';

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $group_id = $_POST['group_id'];

    // Check if the user exists
    $user_query = "SELECT id FROM users WHERE username = '$username'";
    $user_result = mysqli_query($conn, $user_query);

    if (mysqli_num_rows($user_result) == 0) {
        echo "User '$username' not found.";
    } else {
        $user_row = mysqli_fetch_assoc($user_result);
        $user_id = $user_row['id'];

        // Add user to group
        $insert_query = "INSERT INTO group_members (user_id, group_id) VALUES ('$user_id', '$group_id')";
        if (!mysqli_query($conn, $insert_query)) {
            echo "Error adding user to group: " . mysqli_error($conn);
        } else {
            echo "User '$username' added to group successfully.";
        }
    }
}

// Fetch groups from the database
$groups_query = "SELECT * FROM groups";
$groups_result = mysqli_query($conn, $groups_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member to Group</title>
    <style>
        /* Add your CSS styles here */
        /* CSS styles omitted for brevity */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23333333"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>') no-repeat right 10px center;
            background-size: 24px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Member to Group</h2>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="group_id">Group:</label>
            <select name="group_id" id="group_id" required>
                <?php while ($row = mysqli_fetch_assoc($groups_result)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['group_name']; ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="submit" value="Add Member">
        </form>
        <a href="group_management.php">Back to Group Management</a>
    </div>
</body>
</html>
