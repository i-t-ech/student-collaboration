<?php
require_once 'connect.php'; // Include the file to establish the database connection

$courses_query = "SELECT * FROM courses";
$courses_result = mysqli_query($conn, $courses_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 70px;
        }

        h1 {
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

        a.button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            margin-top: 20px;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Course Management</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Detailed Information</th>
                <th>Additional Resources</th>
                <th>Action</th>
            </tr>
            
            <?php while ($row = mysqli_fetch_assoc($courses_result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo isset($row['detailed_info']) ? $row['detailed_info'] : ''; ?></td>
                    <td>
                        <!-- Display links for additional resources -->
                        <?php
                            // Assuming additional_resources contains links separated by a newline character
                            $resources = explode("\n", $row['additional_resources']);
                            foreach ($resources as $resource) {
                                echo '<a href="' . $resource . '" target="_blank">' . $resource . '</a><br>';
                            }
                        ?>
                    </td>
                    <td>
                        <a href="edit_course.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                        <a href="delete_course.php?id=<?php echo $row['id']; ?>" class="button">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href="add_course.php" class="button">Add New Course</a>
        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
