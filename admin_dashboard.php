<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            z-index: 1;
        }

        main {
            display: flex;
            flex: 1;
            transition: margin-left 0.3s;
        }

        nav {
            background-color: #007bff;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 200px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        nav a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    margin-top: 10px;
    margin-bottom: 30px; /* Add margin-bottom here */
    display: flex;
    align-items: center;
}


        nav a:hover {
            color: #f8f9fa;
            background-color: #0056b3;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        nav ul li {
            margin-bottom: 10px;
            top
        }

        .fas {
            margin-right: 10px;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-top: auto;
        }

        .menu-btn {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 2;
            cursor: pointer;
        }

        .menu-btn i {
            font-size: 1.5em;
            color: #fff;
            margin-left: 150px;
            margin-top: 50px;
            position: absolute;
        }
        h{
            font-size: 30px;
            font-weight: 10px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            nav {
                margin-left: -200px;
            }

            main {
                margin-left: 0;
            }

            footer {
                margin-left: 0;
            }

            .menu-btn.open + nav {
                margin-left: 0;
            }

            .menu-btn.open i {
                color: #333;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>ADMIN PANEL</h1>
        <span class="menu-btn" onclick="toggleMenu()"><i class="fas fa-bars"></i></span>
        
    </header>
    <nav id="sidebar">
        <ul>
            <h>Dashboard</h>
            <span class="menu-btn" onclick="toggleMenu()"><i class="fas fa-bars"></i></span>
            
        <li>
            
            <a href="user_management.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="course_management.php"><i class="fas fa-book"></i> Courses</a></li>
            <li><a href="admin_project.php"><i class="fas fa-project-diagram"></i> Projects</a></li>
            <li><a href="group_management.php"><i class="fas fa-users"></i> Groups</a></li>
            <li><a href="admin_login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
    <main>
        <!-- Main content here -->
    </main>
    <footer>
    <p>&copy; 2024 Student Collaboration Platform</p>
    </footer>
    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.marginLeft = sidebar.style.marginLeft === '-200px' ? '0' : '-200px';
        }
    </script>
</body>
</html>
