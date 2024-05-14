<?php
// Delete group
$group_id = $_GET['id'];
$query = "DELETE FROM groups WHERE id = $group_id";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error deleting group: " . mysqli_error($conn);
} else {
    // Redirect to the view groups page
    header("Location: view_groups.php");
    exit;
}
?>
