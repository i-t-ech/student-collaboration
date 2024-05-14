<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'connect.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = $_POST['project_name'];
    $username = $_POST['username'];
    $status = $_POST['status'];

    // Get project ID based on the project name
    $project_query = "SELECT id FROM projects WHERE project_name = '$project_name'";
    $project_result = mysqli_query($conn, $project_query);
    $project_data = mysqli_fetch_assoc($project_result);
    $project_id = $project_data['id'];

    // Get user ID based on the username
    $user_query = "SELECT id FROM users WHERE username = '$username'";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data['id'];

    // Check if user ID exists
    if (!$user_id) {
        $error_message = "User with username '$username' not found.";
    } else {
        // Add user to project
        $add_user_query = "INSERT INTO project_members (user_id, project_id, status) VALUES ('$user_id', '$project_id', '$status')";
        if (mysqli_query($conn, $add_user_query)) {
            $success_message = "User added successfully.";
        } else {
            $error_message = "Error adding user: " . mysqli_error($conn);
        }
    }
}

// Fetch project names
$projects_query = "SELECT project_name FROM projects";
$projects_result = mysqli_query($conn, $projects_query);
$project_names = mysqli_fetch_all($projects_result, MYSQLI_ASSOC);

// Fetch usernames
$users_query = "SELECT username FROM users";
$users_result = mysqli_query($conn, $users_query);
$usernames = mysqli_fetch_all($users_result, MYSQLI_ASSOC);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User to Project</title>
    <style>
        /* Your CSS styles here */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
select,
button {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

p {
    color: green;
}

.error {
    color: red;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Add User to Project</h1>
        <?php if (!empty($success_message)) { ?>
            <p><?php echo $success_message; ?></p>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="project_name">Project Name:</label>
            <select id="project_name" name="project_name" required>
                <?php foreach ($project_names as $project) { ?>
                    <option value="<?php echo $project['project_name']; ?>"><?php echo $project['project_name']; ?></option>
                <?php } ?>
            </select>
            <label for="username">Username:</label>
            <select id="username" name="username" required>
                <?php foreach ($usernames as $user) { ?>
                    <option value="<?php echo $user['username']; ?>"><?php echo $user['username']; ?></option>
                <?php } ?>
            </select>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="ongoing">Ongoing</option>
                <option value="pending">Pending</option>
            </select>
            <button type="submit">Add User</button>
        </form>
    </div>
</body>
</html>
