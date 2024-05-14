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
  background-color: white; /* Background color for the page */
  overflow-x: hidden; /* Prevent horizontal scroll */
  background-image: url('homepage imag.jpg');
  background-size: cover; /* Cover the entire background */
  background-position: center; /* Center the background image */
  background-repeat: no-repeat; 
  height: 100vh; /* Set the height of the background to fit the viewport */
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
  /* Animation for moving text */
@keyframes moveText {
  0% { transform: translateX(-100%); }
  50% { transform: translateX(0); }
  100% { transform: translateX(100%); }
}


/* Navigation menu styles */
.nav {
  display: flex;
  justify-content: flex-end;
  background-color: #333;
  padding: 10px 0;
  margin-top: 0px;

}

.nav a {
  color: white;
  text-decoration: none;
  padding: 10px 20px;
  margin-right: 10px;
  position: relative;
}

/* Bold line animation on hover */
.nav a:hover::after {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  bottom: -2px;
  height: 2px;
  background-color: white;
}

/* Welcome message */
.welcome {
  padding: 20px;
  color: white; /* Text color for the welcome message */
  margin-top: 200px;
  margin-left: 50px;
  font-size: 30px;
  
}

/* Sign in buttons */
.buttons {
  text-align: center;
  margin-top: 20px;
  display: flex;
  padding: 40px;
  justify-content: center;

}

.buttons h2 {
  margin-bottom: 10px;
  color: black;

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

<div class="nav">
  <a href="home.php"><i class="fas fa-home"></i> Home</a>
  <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
  <a href="contacts.php"><i class="fas fa-address-book"></i> Contacts</a>
  
</div>

<div class="welcome">
  <p>Welcome to the Student Collaboration Platform.<br> Collaborate with your peers, <br>join courses, <br>work on projects, and manage your groups.</p>
</div>

<div class="buttons">
  <div>
    <h2>Student Sign In</h2>
    <button onclick="window.location.href = 'signup.php';">Create Account</button>
    <button onclick="window.location.href = 'login.php';">Login</button>
  </div>
  
</div>


</body>
</html>
