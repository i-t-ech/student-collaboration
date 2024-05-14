<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];

// Query to fetch available groups
$groups_query = "SELECT * FROM groups";
$groups_result = mysqli_query($conn, $groups_query);

if (!$groups_result) {
    echo "Error fetching groups: " . mysqli_error($conn);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $group_id = $_POST['group_id'];

    // Insert the user into the group_members table
    $insert_query = "INSERT INTO group_members (user_id, group_id) VALUES ('$user_id', '$group_id')";
    if (mysqli_query($conn, $insert_query)) {
        // Redirect to the group page with the group_id as a URL parameter
        header("Location: group_page.php?id=$group_id");
        exit;
    } else {
        echo "Error joining group: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join a Group</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: aquamarine;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
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

        select, input[type="text"], input[type="submit"] {
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
        <h2>Join a Group</h2>
        <form method="POST">
            <label for="username">Your Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>">
            <label for="group">Select a group:</label>
            <select name="group_id" id="group">
                <?php while ($row = mysqli_fetch_assoc($groups_result)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['group_name']; ?></option>
                <?php } ?>
            </select>
            <input type="submit" value="Join Group">
        </form>
    </div>
</body>
</html>
