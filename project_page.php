<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';
$user_id = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['project_status'])) {
        $project_status = $_POST['project_status'];
        $username = $_SESSION['username'];
        header("Location: project_details.php?project_status=$project_status&username=$username");
        exit;
    } else {
        // Handle the case when 'project_status' is not set
        // You can redirect to an error page or display an error message
        echo "Error: Project status not provided.";
    }
}

$ongoing_projects_query = "SELECT * FROM projects WHERE status = 'ongoing'";
$ongoing_projects_result = mysqli_query($conn, $ongoing_projects_query);
$ongoing_projects = mysqli_fetch_all($ongoing_projects_result, MYSQLI_ASSOC);

$pending_projects_query = "SELECT * FROM projects WHERE status = 'pending'";
$pending_projects_result = mysqli_query($conn, $pending_projects_query);
$pending_projects = mysqli_fetch_all($pending_projects_result, MYSQLI_ASSOC);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Projects</title>
    <style>
        /* Your existing CSS styles */
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f5f5f5;
    color: #333;
}

h1, h2 {
    margin-top: 0;
}

/* Project List Styles */
.project-list {
    margin-bottom: 40px;
}

.project-list h2 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #555;
}

.project {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.project h3 {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 10px;
    color: #333;
}

.project p {
    margin-bottom: 15px;
    line-height: 1.5;
    color: #666;
}

/* Join Button Styles */
.join-btn {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.join-btn:hover {
    background-color: #0056b3;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .project {
        padding: 15px;
    }

    .project h3 {
        font-size: 16px;
    }

    .join-btn {
        font-size: 14px;
        padding: 6px 12px;
    }
}
    </style>
</head>
<body>
    <h1>Available Projects</h1>
    <div class="project-list">
        <h2>Ongoing Projects</h2>
        <?php foreach ($ongoing_projects as $project) { ?>
            <div class="project">
                <h3><?php echo $project['project_name']; ?></h3>
                <p><?php echo $project['description']; ?></p>
                <a href="join_project.php?project_name=<?php echo urlencode($project['project_name']); ?>" class="join-btn">Join Project</a>
            </div>
        <?php } ?>
    </div>
    <div class="project-list">
        <h2>Pending Projects</h2>
        <?php foreach ($pending_projects as $project) { ?>
            <div class="project">
                <h3><?php echo $project['project_name']; ?></h3>
                <p><?php echo $project['description']; ?></p>
                <a href="join_project.php?project_name=<?php echo urlencode($project['project_name']); ?>" class="join-btn">Join Project</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>