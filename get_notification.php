<?php
require_once 'connect.php';
require_once 'notifications.php';

session_start();
$user_id = $_SESSION['user_id'];

$notifications = getNotifications($user_id);
foreach ($notifications as $notification) {
    echo $notification['message'] . "<br>";
}
