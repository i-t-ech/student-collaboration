<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

// Query to fetch groups from the database
$groups_query = "SELECT * FROM groups";
$groups_result = mysqli_query($conn, $groups_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Landpage</title>
    
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: aquamarine;
}

.container {
    max-width: 100%;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

.group {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
}

.group h3 {
    margin-top: 0;
    margin-bottom: 10px;
}

.group p {
    margin-top: 0;
    color: #666;
}

.join-btn {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 3px;
}

.join-btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<div class="container">
    <h1>Available Groups</h1>
    <?php while ($row = mysqli_fetch_assoc($groups_result)) { ?>
        <div class="group">
            <h3><?php echo $row['group_name']; ?></h3>
            <p><?php echo $row['group_description']; ?></p>
            <a href="group_join.php?group_id=<?php echo $row['id']; ?>" class="join-btn">Join Group</a>
        </div>
    <?php } ?>
</div>

    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
