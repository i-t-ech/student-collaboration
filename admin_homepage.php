<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Collaboration Platform</title>
<!-- Add FontAwesome library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  background-color: #f0f8ff; /* Background color for the page */
  overflow-x: hidden; /* Prevent horizontal scroll */
  background-image: url('adminhome image.jpg');
  background-size: cover; /* Cover the entire background */
  background-position: center; /* Center the background image */
  background-repeat: no-repeat; 
  height: 150vh; /* Set the height of the background to fit the viewport */
  margin-bottom: 70px;
}

/* Background color for the moving statement */
.moving-statement {
  background-color:#004080; /* Background color for the moving statement */
  padding: 20px;
  text-align: center;
  color: white;
  padding: 50px;
}

/* Style for the moving statement */
.moving-statement p {
  margin: 0;
  padding: 10px;
  font-size: 30px;
  position: absolute;
  left: 0;
  right: 0;
  animation: moveText 6s infinite alternate;
}

/* Animation for moving text */
@keyframes moveText {
  0% { transform: translateX(-100%); }
  50% { transform: translateX(0); }
  100% { transform: translateX(100%); }
}

/* Welcome message */
.welcome {
  padding: 20px;
  color: blue; /* Text color for the welcome message */
  margin-top: 120px;
  text-align:left; /* Center the text */
  font-size: 30px;
  margin-left: 85px;
  font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  
  
}

/* Sign in buttons */
.buttons {
  text-align: center;
  margin-top: 20px; /* Adjust the margin as needed */
  margin-top: 110px;

}

.buttons h2 {
  margin-bottom: 10px;
  color: green ;
  font-size: 30px;
  
}

.buttons button {
  padding: 10px 30px;
  margin: 0 5px;
  border-radius: 5px;
}
</style>
</head>
<body>

<div class="moving-statement">
  <p>Welcome to the Student Collaboration Platform</p>
</div>

<div class="welcome">
  
  <p>Join our community of administrators <br>and unlock the power of collaboration. <br>
  Create your admin account now <br>
  or sign in to access the platform.</p>
</div>

<div class="buttons">
<h2>Admin Sign In</h2>
  <button onclick="window.location.href = 'admin_register.php';">Create Admin Account</button>
  <button onclick="window.location.href = 'admin_login.php';">Admin Login</button>
</div>

</body>
</html>
