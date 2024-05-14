<?php
session_start();
require_once 'connect.php';

if (isset($_GET['id'])) {
    $message_id = $_GET['id'];

    // Delete the message from the database
    $delete_query = "DELETE FROM messages WHERE id = $message_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $delete_query . "<br>" . mysqli_error($conn);
    }
}
?>
