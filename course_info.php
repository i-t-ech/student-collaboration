<?php
require_once 'connect.php';

session_start();

$message = '';

if (!isset($_SESSION['course_user'])) {
    header("Location: course_login.php");
    exit;
}

$username = $_SESSION['course_user'];

// Retrieve course information for the logged-in user
$course_query = "SELECT c.course_name, c.detailed_info, c.additional_resources
                 FROM enrollments e
                 JOIN courses c ON e.course_id = c.id
                 WHERE e.username = '$username'";
$course_result = mysqli_query($conn, $course_query);

if ($course_result && mysqli_num_rows($course_result) > 0) {
    $row = mysqli_fetch_assoc($course_result);
    $course_name = $row['course_name'];
    $detailed_info = $row['detailed_info'];
    $additional_resources = $row['additional_resources'];
} else {
    $message = "Error: Course information not found.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Information</title>
    <style>
        /* Your CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 20px;
        }

        h4 {
            margin-top: 10px;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php else : ?>
            <h2>Course Registered: <?php echo $course_name; ?></h2>

            <h3>Detailed Information:</h3>
            <?php
            // Split the detailed information into paragraphs based on the delimiter "\n"
            $paragraphs = explode("\n", $detailed_info);

            // Loop through each paragraph and display as sub-heading and paragraph
            foreach ($paragraphs as $paragraph) {
                // Check if the paragraph contains a sub-heading
                if (strpos($paragraph, ":") !== false) {
                    // Split the paragraph into heading and content
                    list($heading, $content) = explode(":", $paragraph, 2);
                    echo "<h4>{$heading}:</h4>";
                    echo "<p>{$content}</p>";
                } else {
                    // If the paragraph doesn't contain a sub-heading, display it as a regular paragraph
                    echo "<p>{$paragraph}</p>";
                }
            }
            ?>

            <h3>Additional Resources:</h3>
            <?php
            // Split the additional resources into links based on the delimiter "\n"
            $links = explode("\n", $additional_resources);

            // Loop through each link and display as a clickable link
            foreach ($links as $link) {
                // Check if the link is not empty
                if (!empty(trim($link))) {
                    // Remove trailing ')' from URL
                    $url = rtrim($link, ')');
                    echo "<a href='{$url}' target='_blank'>{$link}</a><br>";
                }
            }
            ?>

            <a href="dashboard.php">Back to Dashboard</a>
        <?php endif; ?>
    </div>
</body>

</html>
