<?php
// Include the database connection file
require_once 'connect.php';
// Include PHPMailer library
require_once 'PHPMailer-master/PHPMailerAutoload.php';
// Process form submission and send email notification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_id = $_POST["recipient_id"];
    $action = $_POST["action"];
    $message_content = $_POST["message_content"];

    // Assume the message is from the admin
    $sender_id = "admin";

    // Insert the message into the database
    $sql = "INSERT INTO messages (sender_id, recipient_id, action, message_content) VALUES ('$sender_id', '$recipient_id', '$action', '$message_content')";
    if ($conn->query($sql) === TRUE) {
        // Email notification to the recipient
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.yourmailserver.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com';
        $mail->Password = 'your-email-password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your-email@example.com', 'Your Name');
        $mail->addAddress('recipient-email@example.com');

        $mail->isHTML(true);
        $mail->Subject = 'Notification from Admin';
        $mail->Body    = 'You have received a new message from the admin: ' . $message_content;

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label, select, textarea {
            display: block;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Add Message</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="recipient_id">Recipient ID:</label>
        <select id="recipient_id" name="recipient_id" required>
            <option value="">Select Recipient ID</option>
            <?php
            // Populate recipient ID dropdown
            $sql = "SELECT id, username FROM users";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["username"] . "</option>";
                }
            }
            ?>
        </select>

        <label for="action">Action:</label>
        <select id="action" name="action" required>
            <option value="">Select Action</option>
            <option value="add_new_course">Addition of New Course</option>
            <!-- Add more options as needed -->
        </select>

        <label for="message_content">Message:</label>
        <textarea id="message_content" name="message_content" required></textarea>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
