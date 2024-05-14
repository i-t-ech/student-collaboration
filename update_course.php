<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $new_course_name = mysqli_real_escape_string($conn, $_POST['course_name']);

    $update_query = "UPDATE courses SET course_name = '$new_course_name' WHERE id = '$course_id'";
    $update_result = mysqli_query($conn, $update_query);

    if (!$update_result) {
        echo json_encode(array('success' => false, 'message' => 'Course not updated'));
        exit;
    } else {
        // Fetch the updated course name
        $fetch_query = "SELECT course_name FROM courses WHERE id = '$course_id'";
        $fetch_result = mysqli_query($conn, $fetch_query);

        if (!$fetch_result) {
            echo json_encode(array('success' => false, 'message' => 'Error fetching course name: ' . mysqli_error($conn)));
            exit;
        }

        $course_data = mysqli_fetch_assoc($fetch_result);
        $course_name = $course_data ? $course_data['course_name'] : 'Course name not found';

        echo json_encode(array('success' => true, 'message' => 'Course updated successfully', 'course_name' => $course_name));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
          
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: green;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Course</h2>
        <form id="updateCourseForm" action="update_course.php" method="post">
            <label for="course_name">New Course Name:</label>
            <input type="text" id="course_name" name="course_name" required>
            <input type="hidden" name="course_id" value="<?php echo isset($_GET['course_id']) ? htmlspecialchars($_GET['course_id']) : ''; ?>">
            <button type="submit">Update</button>
        </form>
        <div id="message"></div>
    </div>

    <script>
        document.getElementById('updateCourseForm').addEventListener('submit', function(event) {
            event.preventDefault();

            fetch('update_course.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                if (data.success) {
                    messageDiv.textContent = 'Course updated successfully';
                } else {
                    messageDiv.textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
