<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $project_id = $_POST['project_id'];
    $status = $_POST['status'];

    // Validate the username against the database
    $validate_query = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $validate_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $validate_result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($validate_result);

    if (!$user) {
        echo "Username not found.";
        exit;
    }

    $user_id = $user['id'];

    $insert_query = "INSERT INTO project_members (user_id, project_id, status) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $project_id, $status);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: project_details.php?project_id=$project_id");
        exit;
    } else {
        echo "Error joining project: " . mysqli_error($conn);
    }
}

$project_query = "SELECT id, project_name FROM projects";
$result = mysqli_query($conn, $project_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: aquamarine;
            margin-top: 100px;

        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            margin-top: 30px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Join a Project</h1>
        <form method="POST" action="join_project.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="project_id">Select Project:</label>
            <select name="project_id" id="project_id">
                <?php while ($project = mysqli_fetch_assoc($result)): ?>
                    <option value="<?php echo $project['id']; ?>"><?php echo $project['project_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="ongoing">Ongoing</option>
                <option value="pending">Pending</option>
            </select>
            <br>
            <input type="submit" value="Join Project">
        </form>
    </div>
</body>
</html>
