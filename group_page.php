<?php
// Include the necessary libraries
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Include the database connection
require_once 'connect.php';

// Fetch group IDs that the user has joined
$joined_groups_query = "SELECT group_id FROM group_members WHERE user_id = {$_SESSION['user']}";
$joined_groups_result = mysqli_query($conn, $joined_groups_query);

if (!$joined_groups_result) {
    echo "Error fetching joined groups.";
    exit;
}

// Fetch the latest group that the user has joined
$latest_group_id_row = mysqli_fetch_assoc($joined_groups_result);
$latest_group_id = $latest_group_id_row['group_id'];

// Fetch group details for the latest group joined by the user
$group_query = "SELECT * FROM groups WHERE id = $latest_group_id";
$group_result = mysqli_query($conn, $group_query);

if (!$group_result) {
    echo "Error fetching group details.";
    exit;
}

$group = mysqli_fetch_assoc($group_result);

// Fetch group members
$members_query = "SELECT users.username, users.email FROM group_members JOIN users ON group_members.user_id = users.id WHERE group_id = $latest_group_id";
$members_result = mysqli_query($conn, $members_query);

if (!$members_result) {
    echo "Error fetching group members.";
    exit;
}

// Fetch group content
$content_query = "SELECT group_content.id, users.username, group_content.sent_at, group_content.message, group_content.file_path FROM group_content JOIN users ON group_content.sender_id = users.id WHERE group_id = $latest_group_id";
$content_result = mysqli_query($conn, $content_query);

if (!$content_result) {
    echo "Error fetching group content.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Handle form submission
    $message = $_POST['message'];
    $file_path = '';

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_path = 'uploads/' . $file_name;

        move_uploaded_file($file_tmp_name, $file_path);
    }

    $sender_id = $_SESSION['user'];
    $sent_at = date('Y-m-d H:i:s');

    $insert_content_query = "INSERT INTO group_content (group_id, sender_id, sent_at, message, file_path) VALUES ($latest_group_id, $sender_id, '$sent_at', '$message', '$file_path')";
    $insert_content_result = mysqli_query($conn, $insert_content_query);

    if (!$insert_content_result) {
        echo "Error inserting content.";
        exit;
    }

    // Send email notifications to group members
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
            $subject = 'New Activity in ' . $group['group_name'];
            $message_body = 'Dear ' . $member['username'] . ', New activity has been posted in ' . $group['group_name'] . '. Best regards, student collaboration team';

            $mail->setFrom('slyvestermuasya71@gmail.com', 'slyvester');
            $mail->addAddress($to_email, $member['username']);
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message_body;

            $mail->send();
            echo 'Email sent to: ' . $to_email . "\n";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $group['group_name']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: aquamarine;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
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

        #video-container {
            display: flex;
            justify-content: space-around;
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
    <h1>Welcome to <?php echo $group['group_name']; ?></h1>
    <?php if (!empty($group['group_description'])) { ?>
        <h2>Description:</h2>
        <p><?php echo $group['group_description']; ?></p>
    <?php } else { ?>
        <p>Description not found for this group.</p>
    <?php } ?>

    <h2>Members:</h2>
    <ul>
        <?php mysqli_data_seek($members_result, 0); ?>
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
        <span id="timer">00:00</span>
    </div>

    <h2>Group Content:</h2>
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
    // JavaScript for video calling using WebRTC
    const localVideo = document.getElementById('local-video');
    const remoteVideo = document.getElementById('remote-video');
    const startCallButton = document.getElementById('start-call');
    const endCallButton = document.getElementById('end-call');
    const startRecordButton = document.getElementById('start-record');
    const stopRecordButton = document.getElementById('stop-record');
    const timerDisplay = document.getElementById('timer');
    let localStream;
    let peerConnection;
    let mediaRecorder;
    let recordedChunks = [];
    let timerInterval;

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
        mediaRecorder.stop();
    });

    function handleIceCandidate(event) {
        if (event.candidate) {
            // Send the candidate to the other peer (signaling server)
            // For simplicity, assume signaling is handled separately
        }
    }

    function handleTrack(event) {
        remoteVideo.srcObject = event.streams[0];
    }

    function updateRecordingTime() {
        let seconds = 0;
        timerInterval = setInterval(() => {
            seconds++;
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            timerDisplay.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }, 1000);
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        fetchGroupContent(<?php echo $latest_group_id; ?>);
    });

    function fetchGroupContent(groupId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var content = JSON.parse(xhr.responseText);
                    displayGroupContent(content);
                } else {
                    console.error('Failed to fetch group content.');
                }
            }
        };
        xhr.open('POST', 'get_group_content.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('group_id=' + groupId);
    }

    function displayGroupContent(content) {
        var contentContainer = document.querySelector('.content');
        contentContainer.innerHTML = '';
        content.forEach(function(item) {
            var post = document.createElement('div');
            post.classList.add('post');
            post.innerHTML = '<p><strong>' + item.username + ':</strong> ' + item.message + '</p>';
            if (item.file_path) {
                post.innerHTML += '<p><a href="' + item.file_path + '" target="_blank">Download File</a></p>';
            }
            contentContainer.appendChild(post);
        });
    }


</script>
</body>
</html>
