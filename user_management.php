<?php
// Include database connection
require_once 'connect.php';

// Fetch users from the database
$users_query = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: aqua;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 60px;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .logout {
            text-align: center;
        }

        .logout a {
            text-decoration: none;
            color: #333;
            background-color: #f2f2f2;
            padding: 5px 10px;
            border-radius: 5px;
          
        }

        .logout a:hover {
            background-color: #ddd;
        }

        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        
        <!-- User Management Section -->
        <div class="section">
            <h2>List of Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($users_result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <a href="add_user.php">Add New User</a>
        </div>
        <div class="logout">
    <form action="admin_dashboard.php" method="post" style="display: inline;">
        <input type="submit" value="Back to Dashboard">
    </form>
    <form action="logout.php" method="post" style="display: inline;">
        <input type="submit" value="Logout">
   

    </div>
</body>
</html>
