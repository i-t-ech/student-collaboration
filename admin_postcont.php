<?php
session_start();

require_once 'connect.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['admin']) && isset($_SESSION['admin']['id'])) {
        $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];

        // Validate file type and size
        $allowed_extensions = array('pdf', 'doc', 'docx');
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        if (in_array($file_extension, $allowed_extensions)) {
            $file_path = 'uploads/' . $file_name;
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Get the admin's ID from the session
                $currentUserId = $_SESSION['admin']['id'];

                // Check if the sender_id is valid
                $check_user_query = "SELECT id FROM users WHERE id = '$currentUserId'";
                $user_result = mysqli_query($conn, $check_user_query);
                if (mysqli_num_rows($user_result) > 0) {
                    $add_content_query = "INSERT INTO group_content (group_id, content, file_path, sender_id) VALUES ('$group_id', '$content', '$file_path', '$currentUserId')";
                    if (mysqli_query($conn, $add_content_query)) {
                        $success_message = "Content added successfully.";
                    } else {
                        $error_message = "Error adding content: " . mysqli_error($conn);
                    }
                } else {
                    $error_message = "Invalid sender ID.";
                }
            } else {
                $error_message = "Error uploading file.";
            }
        } else {
            $error_message = "Invalid file type. Allowed types: pdf, doc, docx.";
        }
    } else {
        $error_message = "You must be logged in as an admin to post content.";
    }
}

// Fetch relevant group names
$groups_query = "SELECT * FROM groups WHERE status = 'active'";
$groups_result = mysqli_query($conn, $groups_query);
$group_names = mysqli_fetch_all($groups_result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Post Group Content</title>
    <style>
        /* Your CSS styles here */
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

h1 {
    margin-top: 0;
}

label {
    display: block;
    margin-bottom: 5px;
}

select, textarea, input[type="file"], input[type="submit"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #4caf50;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.success {
    color: #4caf50;
    margin-top: 10px;
}

.error {
    color: #f44336;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Post Group Content</h1>
        <?php if (!empty($success_message)) { ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php } ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="group_id">Select Group:</label>
            <select name="group_id" id="group_id" required>
                <?php foreach ($group_names as $group) { ?>
                    <option value="<?php echo $group['id']; ?>"><?php echo $group['group_name']; ?></option>
                <?php } ?>
            </select>
            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="4"></textarea>
            <label for="file">Upload File (PDF or DOC):</label>
            <input type="file" name="file" id="file" accept=".pdf,.doc,.docx">
            <input type="submit" name="submit" value="Post Content">
        </form>
    </div>
</body>
</html>
