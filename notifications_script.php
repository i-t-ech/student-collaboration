<script>
  function getNotifications() {
    fetch('get_notifications.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('notification_area').innerHTML = data;
        });
}

setInterval(getNotifications, 5000); // Check for new notifications every 5 seconds

</script>