<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'connect.php';

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // Query the database to get the project details
    $get_project_query = "SELECT * FROM projects WHERE id = '$project_id'";
    $result = mysqli_query($conn, $get_project_query);
    $project = mysqli_fetch_assoc($result);

    if (!$project) {
        // Project not found, redirect back to admin project management page
        header("Location: admin_project.php");
        exit;
    }
} else {
    // Redirect if project ID is not provided
    header("Location: admin_project.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];

    // Update the project
    $update_project_query = "UPDATE projects SET project_name = '$project_name', description = '$description' WHERE id = '$project_id'";
    mysqli_query($conn, $update_project_query);

    // Redirect back to the admin project management page
    header("Location: admin_project.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <!-- Add your CSS styles here -->
    <style>
      body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Edit Project</h1>
    <form method="POST">
        <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
        <label for="project_name">Project Name:</label>
        <input type="text" id="project_name" name="project_name" value="<?php echo $project['project_name']; ?>" required><br><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" cols="50" required><?php echo $project['description']; ?></textarea><br><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
