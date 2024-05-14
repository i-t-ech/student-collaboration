<?php
require_once 'connect.php';

$message = '';

// Check if course_id is provided in the URL
if (isset($_GET['id'])) {
    $course_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Retrieve course data from the database
    $course_query = "SELECT * FROM courses WHERE id = '$course_id'";
    $course_result = mysqli_query($conn, $course_query);

    if (mysqli_num_rows($course_result) > 0) {
        $course_data = mysqli_fetch_assoc($course_result);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $detailed_info = mysqli_real_escape_string($conn, $_POST['detailed_info']);
            $additional_resources = mysqli_real_escape_string($conn, $_POST['additional_resources']);

            // Update the course in the database
            $update_query = "UPDATE courses SET course_name = '$course_name', description = '$description', detailed_info = '$detailed_info', additional_resources = '$additional_resources' WHERE id = '$course_id'";

            if (mysqli_query($conn, $update_query)) {
                $message = "Course updated successfully!";
            } else {
                $message = "Error updating course: " . mysqli_error($conn);
            }
        }
    } else {
        $message = "Course not found. Course ID: " . $course_id;
    }
} else {
    $message = "Course ID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <style>
        /* Your existing CSS styles */

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 70px;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Course</h2>
        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" value="<?php echo $course_data['course_name'] ?? ''; ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $course_data['description'] ?? ''; ?></textarea>

            <label for="detailed_info">Detailed Information:</label>
            <textarea id="detailed_info" name="detailed_info"><?php echo $course_data['detailed_info'] ?? ''; ?></textarea>

            <label for="additional_resources">Additional Resources:</label>
            <textarea id="additional_resources" name="additional_resources"><?php echo $course_data['additional_resources'] ?? ''; ?></textarea>

            <input type="submit" value="Update">
            <a href="course_management.php" class="back-link" style="display: inline;">Back to Dashboard</a>
        </form>
    </div>
</body>

</html>
