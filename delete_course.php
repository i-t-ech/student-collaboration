<?php
require_once 'connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Delete associated records in the enrollments table first
    $delete_enrollments_query = "DELETE FROM enrollments WHERE course_id = $id";
    mysqli_query($conn, $delete_enrollments_query);

    // Then delete the course
    $delete_course_query = "DELETE FROM courses WHERE id = $id";
    mysqli_query($conn, $delete_course_query);

    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Invalid course ID.";
}
?>
