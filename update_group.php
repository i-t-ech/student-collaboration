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

// Query the database to fetch joined groups for the user
$groups_query = "SELECT groups.group_name FROM groups 
INNER JOIN group_members ON groups.id = group_members.group_id 
WHERE group_members.user_id = '$user_id'";
$groups_result = mysqli_query($conn, $groups_query);

if (!$groups_result) {
    echo "Error fetching joined groups: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Group</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            
        }

        h1 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            background-color: green;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #groupList {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #groupList h3 {
            margin-top: 0;
        }

        #groupList ul {
            list-style-type: none;
            padding: 0;
        }

        #groupList li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Update Group</h1>
    <form id="updateGroupForm" method="POST">
        <label for="group">Select Group:</label>
        <select name="group" id="group">
            <?php
            // Query to fetch all groups
            $all_groups_query = "SELECT * FROM groups";
            $all_groups_result = mysqli_query($conn, $all_groups_query);

            if ($all_groups_result && mysqli_num_rows($all_groups_result) > 0) {
                while ($row = mysqli_fetch_assoc($all_groups_result)) {
                    echo "<option value='" . $row['group_name'] . "'>" . $row['group_name'] . "</option>";
                }
            } else {
                echo "<option value=''>No groups found</option>";
            }
            ?>
        </select>
        <button type="submit" name="update_group">Update Group</button>
    </form>

    <script>
        document.getElementById('updateGroupForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_group.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var groupList = document.getElementById("groupList");
                    var groups = JSON.parse(xhr.responseText);

                    // Update the side block with the updated group list
                    groupList.innerHTML = "<h3>Groups</h3><ul>";
                    groups.forEach(function (group) {
                        groupList.innerHTML += "<li>" + group + "</li>";
                    });
                    groupList.innerHTML += "</ul>";
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>
