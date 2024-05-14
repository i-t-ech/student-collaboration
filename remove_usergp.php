<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

// Initialize message variable
$message = "";

// Handle removing users from groups
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_user'])) {
    $group_id = $_POST['group_id'];
    $user_id_to_remove = $_POST['user_id_to_remove'];

    $delete_query = "DELETE FROM group_members WHERE id = '$group_id' AND user_id = '$user_id_to_remove'";
    if (mysqli_query($conn, $delete_query)) {
        $message = "User removed successfully.";
    } else {
        $message = "Error removing user from group: " . mysqli_error($conn);
    }
}

// Display groups
$groups_query = "SELECT * FROM groups";
$groups_result = mysqli_query($conn, $groups_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Group Management</title>
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

        h1, h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
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

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Group Management</h1>

        <!-- Display message -->
        <?php if (!empty($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>

        <!-- Remove User from Group -->
        <h2>Remove User from Group</h2>
        <form method="POST">
            <label for="group_id">Group:</label>
            <select name="group_id" id="group_id">
                <?php while ($row = mysqli_fetch_assoc($groups_result)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['group_name']; ?></option>
                <?php } ?>
            </select>
            <label for="user_id_to_remove">User ID to Remove:</label>
            <input type="text" name="user_id_to_remove" id="user_id_to_remove" required>
            <input type="submit" name="remove_user" value="Remove User">
            <a href="group_management.php">back</a>
        </form>
    </div>
</body>
</html>
