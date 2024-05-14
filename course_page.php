<?php
require_once 'connect.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: course_register.php");
    exit;
}

// Fetch available courses from the 'courses' table
$courses_query = "SELECT * FROM courses";
$courses_result = mysqli_query($conn, $courses_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses</title>
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
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .course {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .course h2 {
            margin-top: 0;
        }

        .course p {
            margin-bottom: 10px;
        }

        .register-button {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }

        .register-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Available Courses</h1>
        <?php while ($row = mysqli_fetch_assoc($courses_result)) : ?>
            <div class="course">
                <h2><?php echo $row['course_name']; ?></h2>
                <p><?php echo $row['description']; ?></p>
                <a href="course_register.php?course_id=<?php echo $row['id']; ?>" class="register-button">Register</a>
            </div>
        <?php endwhile; ?>
    </div>
    <!-- Add this JavaScript code to your course_page.php file -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('register-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            // Perform any form validation here if needed

            // Redirect to course_register.php after successful registration
            window.location.href = 'course_info.php';
        });
    });
</script>

</body>

</html>
