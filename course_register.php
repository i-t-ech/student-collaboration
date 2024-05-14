<?php
require_once 'connect.php';

session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user'];
    $coursename = $_POST['course_name'];
    $region = $_POST['region'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the submitted course name exists in the 'courses' table
    $course_query = "SELECT id FROM courses WHERE course_name = '$coursename'";
    $course_result = mysqli_query($conn, $course_query);

    if ($course_result && mysqli_num_rows($course_result) > 0) {
        // Fetch the course ID
        $row = mysqli_fetch_assoc($course_result);
        $course_id = $row['id'];

        // Insert enrollment into the 'enrollments' table
        $insert_query = "INSERT INTO enrollments (user_id, course_id, region, username, password) VALUES ('$user_id', '$course_id', '$region', '$username', '$password')";

        if (mysqli_query($conn, $insert_query)) {
            // Set session variable for the user
            $_SESSION['course_user'] = $username;

            // Registration successful, redirect to login page
            header("Location: course_login.php");
            exit;
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Error: Selected course does not exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        label,
        input {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>

<body>
    <?php if (!empty($message)) : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="course_register.php" method="POST">
        <h2>Register for Course</h2>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="course_name">Course Name:</label>
        <select id="course_name" name="course_name" required>
            <option value="">Select Course</option>
            <?php
            $courses_query = "SELECT * FROM courses";
            $courses_result = mysqli_query($conn, $courses_query);
            while ($row = mysqli_fetch_assoc($courses_result)) : ?>
                <option value="<?php echo $row['course_name']; ?>"><?php echo $row['course_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="region">Region:</label>
        <input type="text" id="region" name="region" required>

        <input type="submit" value="Register">
        <p>Have an account? <a href="course_login.php"></a></p>
        <button type="submit" class="btn" id="loginBtn">Login here</button>
       
    </form>
</body>

</html>

