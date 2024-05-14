<?php
// Form for sending a message to the group
echo "<form method='POST' action='send_message.php'>";
echo "<input type='hidden' name='id' value='" . $id . "'>";
echo "<label>Message:</label><br>";
echo "<textarea name='message'></textarea><br>";
echo "<input type='submit' value='Send Message'>";
echo "</form>";
?>
