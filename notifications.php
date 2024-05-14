<?php
function sendNotification($user_id, $message) {
    global $conn;
    $sql = "INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$message')";
    $conn->query($sql);
}

function getNotifications($user_id) {
    global $conn;
    $sql = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $notifications = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
    }
    return $notifications;
}
