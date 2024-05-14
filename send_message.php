<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_username = $_POST['receiver'];
    $message = $_POST['message'];

    // Get receiver's ID
    $receiver_query = "SELECT id FROM admins WHERE username = '$receiver_username'";
    $receiver_result = mysqli_query($conn, $receiver_query);
    $receiver_row = mysqli_fetch_assoc($receiver_result);
    $receiver_id = $receiver_row['id'];

    // Get sender's ID
    $sender_id = $_SESSION['admin_id'];

    // Insert the message into the database
    $insert_query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ($sender_id, $receiver_id, '$message')";
    if (mysqli_query($conn, $insert_query)) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
}
?>
