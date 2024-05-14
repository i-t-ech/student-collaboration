<?php
require_once 'connect.php';
session_start();

// Redirect to dashboard if user is already logged in
if(isset($_SESSION['user'])){
    header("Location: dashboard.php");
    exit; // Make sure to exit after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['id'];
            header("Location: dashboard.php");
            exit; // Make sure to exit after redirection
        } else {
            echo "<div class='alert alert-danger'>Invalid password</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email does not exist</div>";
    }
}
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
    <form id="loginForm" class="form-container active" action="login.php" method="POST">
        <h2 class="login">Login</h2>
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
        <button type="submit" class="btn" id="loginBtn">Login</button>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        <p>Forgot password? <a href="reset_password.php">reset</a></p>
    </form>
</div>
<script src="script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
