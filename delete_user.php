<?php
require_once 'connect.php';

// Check if ID is provided and delete the user
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_query = "DELETE FROM users WHERE id=$id";
    if (mysqli_query($conn, $delete_query)) {
        echo "User deleted successfully!";
    } else {
        echo "Error: " . $delete_query . "<br>" . mysqli_error($conn);
    }
} else {
    echo "User ID not provided.";
}
?>
