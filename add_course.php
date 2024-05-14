<?php
require_once 'connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $detailed_info = mysqli_real_escape_string($conn, $_POST['detailed_info']);
    $additional_resources = mysqli_real_escape_string($conn, $_POST['additional_resources']);

    // Insert the new course into the database
    $insert_query = "INSERT INTO courses (course_name, description, detailed_info, additional_resources) VALUES ('$course_name', '$description', '$detailed_info', '$additional_resources')";

    if (mysqli_query($conn, $insert_query)) {
        $message = "Course added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 60%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 70px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label, input, textarea, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Course</h2>
        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="detailed_info">Detailed Information:</label>
            <textarea id="detailed_info" name="detailed_info" required></textarea>

            <label for="additional_resources">Additional Resources:</label>
            <textarea id="additional_resources" name="additional_resources" rows="4" required></textarea>

            <button type="submit">Add Course</button>
            <a href="course_management.php" class="back-link" style="display: inline;">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
