<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'connect.php';

$user_id = $_SESSION['user'];

// Fetch user data from the database
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    echo "Error fetching user data: " . mysqli_error($conn);
}

$user_data = mysqli_fetch_assoc($user_result);

// Update user data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $region = $_POST['region'];

    $update_query = "UPDATE users SET username='$username', email='$email', phone='$phone', region='$region' WHERE id='$user_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Return a success message
        echo "Profile updated successfully";
        exit;
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .profile-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: green;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="tel"],
        .profile-form input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .profile-form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .profile-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-form">
        <h2>Update Profile</h2>
        <form id="update-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $user_data['phone']; ?>" required>
            <label for="region">Region:</label>
            <input type="text" id="region" name="region" value="<?php echo $user_data['region']; ?>" required>
            <input type="submit" value="Update">
        </form>
    </div>

    <script>
        document.getElementById('update-form').addEventListener('submit', function(event) {
            event.preventDefault();
            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => {
                if (response.ok) {
                    // Update success message
                    document.querySelector('.success-message').innerText = 'Profile updated successfully!';
                } else {
                    // Handle error response
                    console.error('Error updating profile:', response.statusText);
                }
            })
            .catch(error => console.error('Error updating profile:', error));
        });
    </script>
</body>
</html>
