<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_groupcont'])) {
    require_once 'connect.php';

    $group_content_id = $_POST['group_content_id'];

    $delete_query = "DELETE FROM group_content WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $group_content_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect back to group management page with success message
        header("Location: group_management.php?message=Content%20deleted%20successfully");
        exit;
    } else {
        // Handle deletion failure
        $error = "Error deleting content: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

// Handle other cases or errors if needed
?>
