<?php
// Retrieve group details
$id = $_GET['id'];
$query = "SELECT * FROM groups WHERE id = $id";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error retrieving group: " . mysqli_error($conn);
} else {
    $group = mysqli_fetch_assoc($result);

    // Form for editing the group
    echo "<form method='POST' action='edit_group.php'>";
    echo "<input type='hidden' name='id' value='" . $group['id'] . "'>";
    echo "<label>Group Name:</label><br>";
    echo "<input type='text' name='group_name' value='" . $group['group_name'] . "'><br>";
    echo "<label>Description:</label><br>";
    echo "<textarea name='description'>" . $group['description'] . "</textarea><br>";
    echo "<input type='submit' value='Save'>";
    echo "</form>";
}
?>
