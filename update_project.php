<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];

// Query the database to fetch user information
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    echo "Error fetching user data: " . mysqli_error($conn);
}

$user_data = mysqli_fetch_assoc($user_result);

// Query the database to fetch joined projects for the user
$projects_query = "SELECT projects.project_name FROM projects 
INNER JOIN project_members ON projects.id = project_members.project_id 
WHERE project_members.user_id = '$user_id'";
$projects_result = mysqli_query($conn, $projects_query);

if (!$projects_result) {
    echo "Error fetching joined projects: " . mysqli_error($conn);
}

if (isset($_POST['update_project'])) {
    $project_name = $_POST['project'];
    // Update project logic here

    // Query the database to fetch updated project list
    $updated_projects_query = "SELECT projects.project_name FROM projects 
    INNER JOIN project_members ON projects.id = project_members.project_id 
    WHERE project_members.user_id = '$user_id'";
    $updated_projects_result = mysqli_query($conn, $updated_projects_query);

    if (!$updated_projects_result) {
        echo "Error fetching updated project list: " . mysqli_error($conn);
    }

    $updated_projects = [];
    while ($row = mysqli_fetch_assoc($updated_projects_result)) {
        $updated_projects[] = $row['project_name'];
    }

    // Return the updated project list in JSON format
    echo json_encode($updated_projects);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            
        }

        h1 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            background-color:green ;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #projectList {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #projectList h3 {
            margin-top: 0;
        }

        #projectList ul {
            list-style-type: none;
            padding: 0;
        }

        #projectList li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Update Project</h1>
    <form id="updateProjectForm" method="POST">
        <label for="project">Select Project:</label>
        <select name="project" id="project">
            <?php while ($row = mysqli_fetch_assoc($projects_result)) { ?>
                <option value="<?php echo $row['project_name']; ?>"><?php echo $row['project_name']; ?></option>
            <?php } ?>
        </select>
        <button type="submit" name="update_project">Update Project</button>
    </form>

   

    <script>
        document.getElementById('updateProjectForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_project.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var projectList = document.getElementById("projectList");
                    var projects = JSON.parse(xhr.responseText);

                    // Update the side block with the updated project list
                    projectList.innerHTML = "<h3>Projects</h3><ul>";
                    projects.forEach(function (project) {
                        projectList.innerHTML += "<li>" + project + "</li>";
                    });
                    projectList.innerHTML += "</ul>";
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>
