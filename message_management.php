<?php
// Include database connection
require_once 'connect.php';

// Fetch messages from the database
$messages_query = "SELECT * FROM messages";
$messages_result = mysqli_query($conn, $messages_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
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

        .links {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Message Management</h1>
        
        <!-- Message Management Section -->
        <div class="section">
            <h2>List of Messages</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($messages_result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td>
                            <a href="delete_message.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        
        <!-- Message Management Links -->
        <div class="links">
            <a href="add_message.php">Add Message</a>
            <a href="delete_message.php" style="color: red;">Delete Message</a>
        </div>

        <!-- Logout Button -->
        <div class="logout">
            <form action="admin_dashboard.php" method="post" style="display: inline;">
                <input type="submit" value="Back to messages">
            </form>
            <form action="logout.php" method="post" style="display: inline;">
                <input type="submit" value="Logout">
            </form>
        </div>
    </div>
</body>
</html>
