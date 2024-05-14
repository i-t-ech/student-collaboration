<?php
session_start();
require_once 'connect.php';

// Redirect to signup page if user is already logged in
if(isset($_SESSION['user'])){
    header("Location: signup.php");
    exit; // Make sure to exit after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    // Sanitize and validate user inputs
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $region = mysqli_real_escape_string($conn, $_POST["region"]);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the data into the database
    $sql = "INSERT INTO users (username, email, password, phone, region) VALUES ('$username', '$email', '$hashed_password', '$phone', '$region')";
    $result = mysqli_query($conn, $sql);
    
    if($result === TRUE){
        // Redirect to login page after successful registration
        header('Location: login.php');
        exit; // Make sure to exit after redirection
    } else {
        // Handle registration failure
        echo "<script>alert('Failed to register. Please try again.')</script>";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login and Sign up Form</title>
<link rel="stylesheet" href="stlyle3.css">
<script src="script.js"></script>


</head>
<body>

<div class="container">
    <form id="signUPForm" class="form-container" action="signup.php" method="POST">
        <h2 class="login">Sign up</h2>
        <div class="input-box">
            <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
            <input type="text" name="username" required>
            <label>Username</label>
        </div>
        <div class="input-box">
            <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
            <input type="email" name="email" required>
            <label>Email</label>
        </div>
        <div class="input-box">
            <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
            <input type="password" name="password" required>
            <label>Password</label>
        </div>
        <div class="input-box">
            <span class="icon"><ion-icon name="call-outline"></ion-icon></span>
            <input type="text" name="phone" required>
            <label>Phone Number</label>
        </div>
        <div class="input-box">
            <span class="icon"><ion-icon name="globe-outline"></ion-icon></span>
            <input type="text" name="region" required>
            <label>Region</label>
        </div>
        <div class="remember-forgot">
            <label><input type="checkbox" name="agree" required>
            I agree to the terms and conditions</label>
        </div>
        <button type="submit" class="btn" id="sign up Btn" name="sign up">Sign up</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>
<script src="script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

