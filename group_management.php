<?php
session_start();

// Redirect to login page if user is not logged in
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

    $delete_query = "DELETE FROM group_members WHERE group_id = '$group_id' AND user_id = '$user_id_to_remove'";
    if (mysqli_query($conn, $delete_query)) {
        $message = "User removed successfully.";
    } else {
        $message = "Error removing user from group: " . mysqli_error($conn);
    }
}

// Display groups
$groups_query = "SELECT * FROM groups";
$groups_result = mysqli_query($conn, $groups_query);

// Get users who joined each group with user IDs
$group_members_query = "SELECT group_members.group_id, group_members.user_id, groups.group_name, users.username 
                        FROM group_members 
                        INNER JOIN users ON group_members.user_id = users.id
                        INNER JOIN groups ON group_members.group_id = groups.id";
$group_members_result = mysqli_query($conn, $group_members_query);

$group_content_query = "SELECT group_content.id as group_content_id, groups.group_name, group_content.content, group_content.file_path, group_content.sent_at, users.username 
                        FROM group_content 
                        INNER JOIN groups ON group_content.group_id = groups.id 
                        INNER JOIN users ON group_content.sender_id = users.id";

$group_content_result = mysqli_query($conn, $group_content_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Group Management</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 50px auto;
            background-color:aquamarine;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .action-links {
            display: flex;
            justify-content: space-between;
        }

        .action-links a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .action-links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Admin Group Management</h1>

    <!-- Success message -->
    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <!-- Users Who Joined Each Group -->
    <h2>Users Who Joined Each Group</h2>
    <table>
        <tr>
            <th>Group Name</th>
            <th>User ID</th>
            <th>Username</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($group_members_result)) { ?>
            <tr>
                <td><?php echo $row['group_name']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <!-- Content Sent to Each Group -->
    <h2>Content Sent to Each Group</h2>
    <table>
        <tr>
            <th>Group Name</th>
            <th>Content Type</th>
            <th>Content</th>
            <th>File Path</th>
            <th>Sent At</th>
            <th>Sender Username</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($group_content_result)) { ?>
            <tr>
                <td><?php echo $row['group_name']; ?></td>
                <td><?php echo empty($row['file_path']) ? 'Message' : 'File'; ?></td>
                <td><?php echo empty($row['file_path']) ? $row['content'] : 'File Attached'; ?></td>
                <td><?php if (!empty($row['file_path'])) { ?><a href="<?php echo $row['file_path']; ?>" download>Download File</a><?php } ?></td>
                <td><?php echo $row['sent_at']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td>
                    <form method="POST" action="delete_groupcont.php">
                        <input type="hidden" name="group_content_id" value="<?php echo $row['group_content_id']; ?>">
                        <input type="submit" name="delete_groupcont" value="Delete">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Action Links -->
    <div class="action-links">
        <a href="add_new.php">Add New User to Group</a>
        <a href="admin_postcont.php">Post Content to Group</a>
        <a href="add_groups.php">Add New Group</a>
        <a href="remove_usergp.php">Remove from Group</a>
    </div>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
