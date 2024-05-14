<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];

// Query the database to fetch user information
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    echo "Error fetching user data: " . mysqli_error($conn);
}

$user_data = mysqli_fetch_assoc($user_result);

// Query the database to fetch enrolled courses for the user
$courses_query = "SELECT courses.course_name FROM courses 
INNER JOIN enrollments ON courses.id = enrollments.course_id 
WHERE enrollments.user_id = '$user_id'";
$courses_result = mysqli_query($conn, $courses_query);

if (!$courses_result) {
    echo "Error fetching enrolled courses: " . mysqli_error($conn);
}

// Query the database to fetch user's groups
$groups_query = "SELECT * FROM groups WHERE id IN (SELECT group_id FROM group_members WHERE user_id = '$user_id')";
$groups_result = mysqli_query($conn, $groups_query);

// Query the database to fetch user's projects
$projects_query = "SELECT * FROM projects WHERE id IN (SELECT project_id FROM project_members WHERE user_id = '$user_id')";
$projects_result = mysqli_query($conn, $projects_query);


function sendEmailNotification($userEmail, $notificationMessage) {
    // Use PHP's mail function to send an email
    $subject = 'Notification';
    $headers = 'From: your-email@example.com';
    mail($userEmail, $subject, $notificationMessage, $headers);
}

// Assume this function is called when a new notification is received
function onNewNotification($notification){
    displayNotification($notification.message);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="notification_script.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: cornflowerblue;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 10px 20px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .sidebar {
            background-color: #333;
            color: white;
            width: 250px;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: transform 0.1s ease; /* Add transition effect */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        .sidebar.closed {
            transform: translateX(-190px); /* Move sidebar off-screen */
        }

        .sidebar h2 {
            padding: 10px 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid white;
            position: relative;
            margin-top: 70px;
        }

        .menu-btn {
            position: absolute;
            top: 0;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 10px;
            cursor: pointer;
        }

        .content {
            margin-left: 250px; /* Adjust margin to leave space from side blocks */
            padding: 20px;
            transition: margin-left 0.3s ease;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            max-height: calc(100vh - 60px);
            overflow-y: scroll;
        }

        .side-block {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            margin-right: 10px;
            margin-bottom: 20px;
            padding: 10px;
            flex: 1 1 auto;
            max-width: calc(33.333% - 20px);
            box-sizing: border-box;
            border-radius: 15px;
            transition: margin-top 2s ease;
            margin-top: 180px;
        }

        .welcome {
        text-align: center;
        position: absolute;
        top: 15%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
        padding: 20px;
        transition: top 0.3s;
        
    }

        .nav {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: right;
            border-radius: 0;
            position: fixed;
            top: 0;
            right: 0;
            z-index: 1000;
            left: 0px;
            height: 60px;
            transition: top 0.3s ease;
            max-width: 100%;
            overflow-y: auto;
        }

        .nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .nav ul li {
            display: inline-block;
            margin-right: 20px;
            align-content: center;
            padding: 25px;
        }

        .nav ul li a {
            text-decoration: none;
            color: white;
            position: relative;
            top: 20px;
            border-radius: 30px;
        }

        .nav ul li a:hover::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            border-bottom: 2px solid white;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: green;
            min-width: 160px;
            z-index: 1;
        }

        .dropdown-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            background-color: transparent;
            border: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-content button:hover {
            background-color: greenyellow;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .notification-icon {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 12px;
        }

        .profile-icon {
            font-size: 24px;
            cursor: pointer;
            position: relative;
            margin-right: 70px;
            bottom: 0px;
        }

        .profile-dropdown {
            position: absolute;
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
            top: 60px;
            right: 0;
            display: none;
        }

        .profile-icon:hover + .profile-dropdown {
            display: block;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 999;
            transition: bottom 0.1s ease;
            overflow-y: auto;
        }

        .footer p {
            margin: 0;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        /* Custom CSS provided by user */
        .button-container button {
            background-color:green;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .button-container button:hover {
            background-color: #555;
        }

        .button-container {
            text-align: center;
            
        }

        .side-block h3 {
            margin-top: 0;
        }

        @media screen and (max-width: 960px) {
            .side-block {
                max-width: calc(50% - 20px);
            }
        }

        @media screen and (max-width: 640px) {
            .side-block {
                max-width: 80%;
            }
        }

        .sidebar li:last-child {
            margin-top: auto;
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 30px;
        }

        .nav ul li a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <div class="content">
        <div class="sidebar">
            <h2>Dashboard <div class="menu-btn" onclick="toggleSidebar()">&#9776;</div></h2>
            <ul>
                <li><a href="#profile"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="course_page.php"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="group_landpage.php"><i class="fas fa-users"></i> Groups</a></li>
                <li><a href="project_page.php"><i class="fas fa-project-diagram"></i> Projects</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="faqs.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
                <li><a href="game.php"><i class="fas fa-gamepad"></i> Game</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="nav">
            <ul>
                <li>
                    <?php
                    $messageCount = 2; // Example count, replace with actual count
                    if ($messageCount > 0) {
                        echo '<div class="notification-icon">';
                        echo '<a href="messages.php"><i class="fas fa-bell"></i></a>';
                        echo '<span class="notification-badge">' . $messageCount . '</span>';
                        echo '</div>';
                    } else {
                        echo '<a href="messages.php"><i class="fas fa-bell"></i></a>';
                    }
                    ?>
                </li>
                <li>
                    <div class="dropdown">
                        <i class="fas fa-user profile-icon"></i>
                        <div class="profile-dropdown">
                            <button>Update Profile</button>
                            <button>Logout</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="welcome">
            <h2>Welcome, <?php echo isset($user_data['username']) ? $user_data['username'] : 'User'; ?>!</h2>
        </div>
        <div class="side-block" id="profile">
            <h3>Profile</h3>
            <?php if ($user_data && isset($user_data['username'])) { ?>
                <p>Name: <?php echo $user_data['username']; ?></p>
            <?php } ?>
            <?php if ($user_data && isset($user_data['email'])) { ?>
                <p>Email: <?php echo $user_data['email']; ?></p>
            <?php } ?>
            <?php if ($user_data && isset($user_data['phone'])) { ?>
                <p>Phone: <?php echo $user_data['phone']; ?></p>
            <?php } ?>
            <?php if ($user_data && isset($user_data['region'])) { ?>
                <p>Region: <?php echo $user_data['region']; ?></p>
            <?php } ?>
            <div class="button-container">
                <button onclick="loadForm('update_profile.php', 'profileForm')">Update</button>
            </div>
            <div id="profileForm"></div>
        </div>

        <div class="side-block" id="courses">
    <h3>Courses</h3>
    <ul>
        <?php
        // Fetch the updated course list from the database
        $courses_query = "SELECT courses.course_name FROM courses 
                          INNER JOIN enrollments ON courses.id = enrollments.course_id 
                          WHERE enrollments.user_id = '$user_id'";
        $courses_result = mysqli_query($conn, $courses_query);

        if (!$courses_result) {
            echo "Error fetching enrolled courses: " . mysqli_error($conn);
        }

        while ($row = mysqli_fetch_assoc($courses_result)) {
            echo "<li>{$row['course_name']}</li>";
        }
        ?>
    </ul>
   
    <div id="coursesForm"></div>
</div>


        <div class="side-block" id="groups">
            <h3>Groups</h3>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($groups_result)) { ?>
                    <li><?php echo $row['group_name']; ?> </li>
                <?php } ?>
            </ul>
            
            <div id="groupsForm"></div>
        </div>

        <div class="side-block" id="projects">
            <h3>Projects</h3>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($projects_result)) { ?>
                    <li><?php echo $row['project_name']; ?> </li>
                <?php } ?>
            </ul>
           
            <div id="projectsForm"></div>
        </div>

        <script>
            function toggleSidebar() {
                document.querySelector('.sidebar').classList.toggle('closed');
                document.querySelector('.content').classList.toggle('expanded');
            }

            function loadForm(url, targetId) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", url, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById(targetId).innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            }
        </script>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> student Collaboration. All rights reserved.</p>
    </div>
    <script>
   
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
    var welcome = document.querySelector('.welcome');
    var lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scroll down
            welcome.style.top = '0';
        } else {
            // Scroll up
            welcome.style.top = '50%';
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Safari
    });
    function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    var content = document.querySelector('.content');

    if (sidebar.classList.contains('closed')) {
        sidebar.classList.remove('closed');
        content.style.marginLeft = '250px'; // Show sidebar
    } else {
        sidebar.classList.add('closed');
        content.style.marginLeft = '0'; // Hide sidebar
        // Move side blocks back to their original position
        var sideBlocks = document.querySelectorAll('.side-block');
        sideBlocks.forEach(function (block) {
            block.style.marginTop = '180px';
        });
    }
}


</script>
</body>
</html>
