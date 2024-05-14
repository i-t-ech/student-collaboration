<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'connect.php';

// Query the database to fetch ongoing projects with members and content
$ongoing_projects_query = "SELECT p.*, u.username, pp.file_path FROM projects p 
    LEFT JOIN project_members pm ON p.id = pm.project_id 
    LEFT JOIN users u ON pm.user_id = u.id 
    LEFT JOIN project_content pp ON p.id = pp.project_id 
    WHERE p.status = 'ongoing'";
$ongoing_projects_result = mysqli_query($conn, $ongoing_projects_query);

// Query the database to fetch pending projects with members and content
$pending_projects_query = "SELECT p.*, u.username, pp.file_path FROM projects p 
    LEFT JOIN project_members pm ON p.id = pm.project_id 
    LEFT JOIN users u ON pm.user_id = u.id 
    LEFT JOIN project_content pp ON p.id = pp.project_id 
    WHERE p.status = 'pending'";
$pending_projects_result = mysqli_query($conn, $pending_projects_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage Project</title>
    <style>
        /* Your CSS styles */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
}

.container {
    max-width: 100%;
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

.section {
    margin-bottom: 40px;
}

h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.button {
    display: inline-block;
    padding: 8px 16px;
    margin-right: 10px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    cursor: pointer;
}

.button:hover {
    background-color: #0056b3;
}

a {
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
        <h1>Admin Manage Project</h1>

        <div class="section">
            <h2>Ongoing Projects</h2>
            <table>
                <tr>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Members</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
                <?php while ($project = mysqli_fetch_assoc($ongoing_projects_result)) { ?>
                    <tr>
                        <td><?php echo $project['project_name']; ?></td>
                        <td><?php echo $project['description']; ?></td>
                        <td><?php echo $project['username']; ?></td>
                        <td><a href="<?php echo $project['file_path']; ?>" target="_blank">Download File</a></td>
                        <td>
                            <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="button">Edit</a>
                            <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="button">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <a href="add_user_project.php" class="button">Add New User</a>
            <a href="admin_post_project.php" class="button">Post to Project</a>
            <a href="add_project.php" class="button">Add Project</a>
        </div>

        <div class="section">
            <h2>Pending Projects</h2>
            <table>
                <tr>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Members</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
                <?php while ($project = mysqli_fetch_assoc($pending_projects_result)) { ?>
                    <tr>
                        <td><?php echo $project['project_name']; ?></td>
                        <td><?php echo $project['description']; ?></td>
                        <td><?php echo $project['username']; ?></td>
                        <td><a href="<?php echo $project['file_path']; ?>" target="_blank">Download File</a></td>
                        <td>
                            <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="button">Edit</a>
                            <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="button">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <a href="add_user_project.php" class="button">Add New User</a>
            <a href="admin_post_project.php" class="button">Post to Project</a>
            <a href="add_project.php" class="button">Add Project</a>
        </div>
        <a href="admin_dashboard.php">Back</a>
    </div>
</body>
</html>
