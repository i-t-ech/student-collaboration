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
    $content = $_POST['content'];
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $status = $_POST['status'];

    // Get project ID based on the project name
    $project_query = "SELECT id FROM projects WHERE project_name = '$project_name'";
    $project_result = mysqli_query($conn, $project_query);
    $project_data = mysqli_fetch_assoc($project_result);
    $project_id = $project_data['id'];

    // Upload file to server
    $file_path = 'uploads/' . $file_name;
    move_uploaded_file($file_tmp, $file_path);

    // Add content to project
    $add_content_query = "INSERT INTO project_content (project_id, content, file_path, status) VALUES ('$project_id', '$content', '$file_path', '$status')";
    if (mysqli_query($conn, $add_content_query)) {
        $success_message = "Content added successfully.";
    } else {
        $error_message = "Error adding content: " . mysqli_error($conn);
    }
}

// Fetch project names
$projects_query = "SELECT project_name FROM projects";
$projects_result = mysqli_query($conn, $projects_query);
$project_names = mysqli_fetch_all($projects_result, MYSQLI_ASSOC);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Post Project Content</title>
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
input[type="file"],
select,
textarea,
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

.error {
    color: red;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Post Project Content</h1>
        <?php if (!empty($success_message)) { ?>
            <p><?php echo $success_message; ?></p>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="project_name">Project Name:</label>
            <select id="project_name" name="project_name" required>
                <?php foreach ($project_names as $project) { ?>
                    <option value="<?php echo $project['project_name']; ?>"><?php echo $project['project_name']; ?></option>
                <?php } ?>
            </select>
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="4" cols="50" required></textarea>
            <label for="file">Upload File:</label>
            <input type="file" id="file" name="file" required>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="ongoing">Ongoing</option>
                <option value="pending">Pending</option>
            </select>
            <button type="submit">Submit</button>
            <a href="admin_project.php">back</a>
        </form>
    </div>
</body>
</html>
