<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

// Query to fetch messages from group_content table for groups the user is in
$user_id = $_SESSION['user'];
$group_messages_query = "SELECT gc.id, gc.content, gc.sent_at, u.username AS sender_username
                         FROM group_content gc
                         JOIN group_members gm ON gc.group_id = gm.group_id
                         JOIN users u ON gc.sender_id = u.id
                         WHERE gm.user_id = '$user_id'";
$group_messages_result = mysqli_query($conn, $group_messages_query);

// Check if the request is a POST request and the required parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type']) && isset($_POST['id'])) {
    $type = $_POST['type'];
    $id = $_POST['id'];

    // Check if the type is 'group' or 'project'
    if ($type === 'group') {
        $query = "DELETE FROM group_content WHERE id = '$id'";
    } elseif ($type === 'project') {
        $query = "DELETE FROM project_content WHERE id = '$id'";
    }

    // Execute the delete query
    if (mysqli_query($conn, $query)) {
        // Return a success message
        echo json_encode(array("success" => true));
        exit; // Stop further execution
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to delete message"));
        exit; // Stop further execution
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your HTML head content here -->
</head>
<body>
    <!-- Your HTML body content here -->

    <ul>
        <?php while ($row = mysqli_fetch_assoc($group_messages_result)) { ?>
            <li>
                <?php echo $row['content']; ?> - <?php echo $row['sender_username']; ?> - <?php echo $row['sent_at']; ?>
                <button onclick="deleteMessage('group', <?php echo $row['id']; ?>)">Delete</button>
            </li>
        <?php } ?>
    </ul>

    <script>
    function deleteMessage(type, id) {
        if (confirm("Are you sure you want to delete this message?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "user_deletemsg.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Remove the message from the DOM
                        var listItem = document.querySelector(`li button[data-id="${id}"]`).parentNode;
                        listItem.parentNode.removeChild(listItem);
                    } else {
                        alert("Failed to delete message: " + response.message);
                    }
                }
            };
            xhr.send("type=" + type + "&id=" + id);
        }
    }
    </script>
</body>
</html>
