<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

if (!isset($_GET['project_id'])) {
    header("Location: project_management.php");
    exit;
}

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$project_id = $_GET['project_id'];

// Fetch project details
$project_query = "SELECT * FROM projects WHERE id = $project_id";
$project_result = mysqli_query($conn, $project_query);
$project = mysqli_fetch_assoc($project_result);

// Fetch project members
$members_query = "SELECT users.username, users.email FROM project_members JOIN users ON project_members.user_id = users.id WHERE project_id = $project_id";
$members_result = mysqli_query($conn, $members_query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $message = $_POST['message'];
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];

    if (!empty($message) || !empty($file_name)) {
        if (move_uploaded_file($file_tmp, "uploads/$file_name")) {
            $insert_query = "INSERT INTO project_content (project_id, user_id, message, file_path) VALUES ($project_id, {$_SESSION['user']}, '$message', 'uploads/$file_name')";
        } else {
            $insert_query = "INSERT INTO project_content (project_id, user_id, message) VALUES ($project_id, {$_SESSION['user']}, '$message')";
        }

        if (mysqli_query($conn, $insert_query)) {
            // Reset the result set pointer
            mysqli_data_seek($members_result, 0);

            // Send email notifications to project members
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.elasticemail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'slyvestermuasya71@gmail.com'; // Your Elastic Email SMTP username
                $mail->Password = 'FEC564F5E40339CCE088B64C5EF37024876A'; // Your Elastic Email SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525;

                while ($member = mysqli_fetch_assoc($members_result)) {
                    $to_email = $member['email'];
                    $subject = 'New Activity in ' . $project['project_name'];
                    $message_body = 'Dear ' . $member['username'] . ', New activity has been posted in ' . $project['project_name'] . '. Best regards, student collaboration team';

                    $mail->setFrom('slyvestermuasya71@gmail.com', 'slyvester');
                    $mail->addAddress($to_email, $member['username']);
                    $mail->isHTML(false);
                    $mail->Subject = $subject;
                    $mail->Body = $message_body;

                    $mail->send();
                    echo 'Email sent to: ' . $to_email . "\n"; // Display success message
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
            }

            echo "Posted successfully.";
            // Redirect to avoid resubmission
            header("Location: {$_SERVER['PHP_SELF']}?project_id=$project_id");
            exit;
        } else {
            echo "Error posting message.";
        }
    } else {
        echo "Message or file is required.";
    }
}

// Fetch project content
$content_query = "SELECT project_content.user_id, users.username, project_content.sent_at, project_content.message, project_content.file_path FROM project_content JOIN users ON project_content.user_id = users.id WHERE project_id = $project_id";
$content_result = mysqli_query($conn, $content_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $project['project_name']; ?></title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: aquamarine;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        .post {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .post p {
            margin: 0;
        }

        input[type="text"],
        textarea,
        input[type="submit"] {
            width: 70%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .content {
            margin-top: 20px;
        }

        .content a {
            color: #007bff;
            text-decoration: none;
        }

        .content a:hover {
            text-decoration: underline;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        #video-container {
            display: flex;
            justify-content: space-around;
            align-items: center; /* Center vertically */
            margin-top: 20px;
        }

        video {
            max-width: 45%;
            border: 1px solid #ccc;
        }

        #video-controls {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        #video-controls button {
            margin: 0 10px; /* Add some spacing between buttons */
            padding: 10px 20px; /* Increase button padding */
            background-color: cyan; /* Button background color */
            color: #fff; /* Button text color */
            border: none; /* Remove button border */
            border-radius: 5px; /* Apply border radius */
            cursor: pointer; /* Change cursor to pointer */
            transition: background-color 0.3s; /* Smooth transition for background color */
        }

        #video-controls button:hover {
            background-color: greenyellow; /* Darker background color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to <?php echo $project['project_name']; ?></h1>

        <h2>Members:</h2>
        <ul>
            <?php mysqli_data_seek($members_result, 0); // Reset the result set pointer ?>
            <?php while ($member = mysqli_fetch_assoc($members_result)) : ?>
                <li><?php echo $member['username']; ?></li>
            <?php endwhile; ?>
        </ul>

        <!-- Video call interface -->
        <div id="video-container">
            <video id="local-video" autoplay></video>
            <video id="remote-video" autoplay></video>
        </div>
        <div id="video-controls">
            <button id="start-call">Start Call</button>
            <button id="end-call">End Call</button>
            <button id="start-record">Start Recording</button>
            <button id="stop-record">Stop Recording</button>
        </div>

        <h2>Project Content:</h2>
        <div class="content">
            <?php mysqli_data_seek($content_result, 0); // Reset the result set pointer ?>
            <?php while ($content = mysqli_fetch_assoc($content_result)) : ?>
                <div class="post">
                    <p><strong><?php echo $content['username']; ?>:</strong> <?php echo $content['message']; ?></p>
                    <?php if (!empty($content['file_path'])) : ?>
                        <p><a href="<?php echo $content['file_path']; ?>" target="_blank">Download File</a></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <h2>Add Content:</h2>
        <form method="POST" enctype="multipart/form-data">
            <textarea name="message" placeholder="Enter your message"></textarea>
            <input type="file" name="file">
            <input type="submit" name="submit" value="Post">
        </form>

        <a href="dashboard.php">Go back</a>
    </div>
    <script>
        // JavaScript
        // JavaScript
let localVideo = document.getElementById('local-video');
let remoteVideo = document.getElementById('remote-video');
const startCallButton = document.getElementById('start-call');
const endCallButton = document.getElementById('end-call');
const startRecordButton = document.getElementById('start-record');
const stopRecordButton = document.getElementById('stop-record');
let localStream;
let peerConnection;
let mediaRecorder;
let recordedChunks = [];
let timerInterval; // Variable to hold the interval timer

// Function to update the recording time
function updateRecordingTime() {
    const timerDisplay = document.getElementById('timer');
    let seconds = 0;

    timerInterval = setInterval(() => {
        seconds++;
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }, 1000);
}

startRecordButton.addEventListener('click', () => {
    recordedChunks = [];
    mediaRecorder = new MediaRecorder(localStream, { mimeType: 'video/webm' });

    mediaRecorder.ondataavailable = event => {
        if (event.data.size > 0) {
            recordedChunks.push(event.data);
        }
    };

    mediaRecorder.onstart = () => {
        updateRecordingTime(); // Start the recording timer
    };

    mediaRecorder.onstop = () => {
        clearInterval(timerInterval); // Stop the recording timer
        const recordedBlob = new Blob(recordedChunks, { type: 'video/webm' });
        const recordedUrl = URL.createObjectURL(recordedBlob);

        // Upload the recorded video to the server or store it in the database
        // For simplicity, assume this is handled separately

        const downloadLink = document.createElement('a');
        downloadLink.href = recordedUrl;
        downloadLink.download = 'recorded-video.webm';
        downloadLink.click();
        URL.revokeObjectURL(recordedUrl);
    };

    mediaRecorder.start();
});

stopRecordButton.addEventListener('click', () => {
    if (mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }
});

// Modify the existing code for starting and ending the call to use the mediaRecorder
startCallButton.addEventListener('click', async () => {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;

        const configuration = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] };
        peerConnection = new RTCPeerConnection(configuration);
        peerConnection.addEventListener('icecandidate', handleIceCandidate);
        peerConnection.addEventListener('track', handleTrack);
        localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        // Send offer to the other peer (signaling server)
        // For simplicity, assume signaling is handled separately

    } catch (error) {
        console.error('Error starting call:', error);
    }
});

endCallButton.addEventListener('click', () => {
    localStream.getTracks().forEach(track => track.stop());
    localVideo.srcObject = null;
    remoteVideo.srcObject = null;
    peerConnection.close();

    // Stop the recording timer if it's running
    clearInterval(timerInterval);

    // Stop the camera
    localStream.getTracks().forEach(track => {
        if (track.kind === 'video') {
            track.stop();
        }
    });
});

function handleIceCandidate(event) {
    if (event.candidate) {
        // Send candidate to the other peer (signaling server)
    }
}

function handleTrack(event) {
    remoteVideo.srcObject = event.streams[0];
}

    </script>
</body>
</html>
