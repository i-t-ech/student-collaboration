<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];

// Query to fetch messages from group_content table for groups the user is in
$group_messages_query = "SELECT gc.id, gc.message, gc.file_path, gc.sent_at, u.username AS sender_username
                         FROM group_content gc
                         JOIN group_members gm ON gc.group_id = gm.group_id
                         JOIN users u ON gc.sender_id = u.id
                         WHERE gm.user_id = '$user_id'";
$group_messages_result = mysqli_query($conn, $group_messages_query);

// Query to fetch messages from project_content table for projects the user is in
$project_messages_query = "SELECT pc.id, pc.message, pc.file_path, pc.sent_at, u.username AS sender_username
                           FROM project_content pc
                           JOIN project_members pm ON pc.project_id = pm.project_id
                           JOIN users u ON pc.sender_id = u.id
                           WHERE pm.user_id = '$user_id'";
$project_messages_result = mysqli_query($conn, $project_messages_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <!-- Add your CSS and JavaScript files here -->
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1, h2 {
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
            border: 1px solid #333;
            padding: 5px 10px;
            border-radius: 5px;
            width: 100px;
            margin: 0 auto;
        }

        a:hover {
            background-color: #333;
            color: white;
        }

    </style>
</head>
<body>
    <h1>Messages</h1>

    <h2>Group Messages</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($group_messages_result)) { ?>
            <li>
                <?php echo $row['message']; ?> - <?php echo $row['sender_username']; ?> - <?php echo $row['sent_at']; ?>
                <?php if (!empty($row['file_path'])) { ?>
                    <a href="<?php echo $row['file_path']; ?>" download>Download</a>
                <?php } ?>
                <a href="user_deletemsg.php?type=group&id=<?php echo $row['id']; ?>">Delete</a>
            </li>
        <?php } ?>
    </ul>

    <h2>Project Messages</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($project_messages_result)) { ?>
            <li>
                <?php echo $row['message']; ?> - <?php echo $row['sender_username']; ?> - <?php echo $row['sent_at']; ?>
                <?php if (!empty($row['file_path'])) { ?>
                    <a href="<?php echo $row['file_path']; ?>" download>Download</a>
                <?php } ?>
                <a href="user_deletemsg.php?type=project&id=<?php echo $row['id']; ?>">Delete</a>
            </li>
        <?php } ?>
    </ul>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
