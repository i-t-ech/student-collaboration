<script>
    function toggleSidebar() {
        var sidebar = document.querySelector('.sidebar');
        var content = document.querySelector('.content');
        var nav = document.querySelector('.nav');

        if (sidebar.classList.contains('closed')) {
            sidebar.classList.remove('closed');
            content.classList.remove('closed');
            nav.style.left = '250px'; // Move nav with sidebar
        } else {
            sidebar.classList.add('closed');
            content.classList.add('closed');
            nav.style.left = '0'; // Reset nav position
        }
    }

    let lastScrollTop = 0;

    window.addEventListener("scroll", function () {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scroll down
            document.querySelector('.nav').style.top = '-60px'; // Move navigation bar up
            document.querySelector('.footer').style.bottom = '-60px'; // Move footer up
        } else {
            // Scroll up
            document.querySelector('.nav').style.top = '0'; // Move navigation bar down
            document.querySelector('.footer').style.bottom = '0'; // Move footer down
        }

        lastScrollTop = currentScroll;
    });
    // <!-- Update your JavaScript to handle notifications -->

    function displayNotification(message) {
        var notificationBar = document.querySelector('.notification-bar');
        var notificationMessage = notificationBar.querySelector('.notification-message');
        notificationMessage.textContent = message;
        notificationBar.classList.add('show');
        setTimeout(function() {
            notificationBar.classList.remove('show');
        }, 5000); // Hide after 5 seconds
    }

    function closeNotification() {
        document.querySelector('.notification-bar').classList.remove('show');
    }

    // Assume this function is called when a new notification is received
    function onNewNotification(notification) {
        displayNotification(notification.message);
    }
    document.getElementById('message-count').addEventListener('click', function() {
    // Redirect to the messages page when the bell icon is clicked
    window.location.href = 'messages.php';
});
function updateMessageCount() {
    // Query the database or any other method to get the message count
    // For now, let's assume you have a variable messageCount that holds the count
    let messageCount = 5; // Example count, replace with actual count
    document.getElementById('message-count').textContent = messageCount;
}

// Call the updateMessageCount function to update the count initially
updateMessageCount();

    function toggleSidebar() {
        var sidebar = document.querySelector('.sidebar');
        var content = document.querySelector('.content');

        if (sidebar.classList.contains('closed')) {
            sidebar.classList.remove('closed');
            content.style.marginLeft = '250px'; // Show sidebar
        } else {
            sidebar.classList.add('closed');
            content.style.marginLeft = '0'; // Hide sidebar
        }
    }



</script>