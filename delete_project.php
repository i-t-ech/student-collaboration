<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the project content first
    $delete_content_query = "DELETE FROM project_content WHERE project_id = $id";
    $delete_content_result = mysqli_query($conn, $delete_content_query);

    // Delete the project members
    $delete_members_query = "DELETE FROM project_members WHERE project_id = $id";
    $delete_members_result = mysqli_query($conn, $delete_members_query);

    // Delete the project
    $delete_project_query = "DELETE FROM projects WHERE id = $id";
    $delete_project_result = mysqli_query($conn, $delete_project_query);

    if ($delete_content_result && $delete_members_result && $delete_project_result) {
        // Redirect back to the admin project management page
        header("Location: admin_project.php");
        exit;
    } else {
        echo "Error deleting project.";
    }
} else {
    echo "Project ID not specified.";
}
?>
