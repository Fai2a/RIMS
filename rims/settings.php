<?php
session_start();
require_once "database.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if (isset($_POST['update_profile'])) {
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Validate the inputs
    $errors = array();
    if (empty($firstName) || empty($lastName) || empty($email)) {
        array_push($errors, "Sab fields bharna zaroori hai");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email format ghalat hai");
    }
    if (strlen($newPassword) > 0 && strlen($newPassword) < 8) {
        array_push($errors, "Password kam se kam 8 characters ka hona chahiye");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Prepare SQL query
        if (!empty($newPassword)) {
            // Update query with password change
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error in SQL query: " . $conn->error);
            }
            $stmt->bind_param("ssssi", $firstName, $lastName, $email, $hashedPassword, $user_id);
        } else {
            // Update query without password change
            $sql = "UPDATE user SET firstname = ?, lastname = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error in SQL query: " . $conn->error);
            }
            $stmt->bind_param("sssi", $firstName, $lastName, $email, $user_id);
        }

        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var popup = document.createElement('div');
                        popup.className = 'popup';
                        popup.style.position = 'fixed';
                        popup.style.top = '20px';
                        popup.style.right = '700px';
                        popup.style.backgroundColor = '#28a745';
                        popup.style.color = 'white';
                        popup.style.padding = '25px';
                        popup.style.borderRadius = '5px';
                        popup.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';
                        popup.style.opacity = '1';
                        popup.style.transition = 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out';
                        popup.style.transform = 'scale(1.1)';
                        popup.innerHTML = '<div class=\"popup-content\">Profile updated successfully!</div>';
                        document.body.appendChild(popup);
                        setTimeout(function() { 
                            popup.style.opacity = '0'; 
                            setTimeout(function() { 
                                popup.remove(); 
                                window.location.href = 'user_panel.php'; 
                            }, 500); 
                        }, 2000);
                    });
                  </script>";
            exit();
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
    }
}

// Fetch existing profile details
$sql = "SELECT firstname, lastname, email FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error in SQL query: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Additional Styles */
        .back-button {
            margin-bottom: 20px;
            font-size: 16px;
            text-decoration: none;
            color: white;
            background-color: black;
            padding: 8px 12px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            width: 120px; /* Adjusted width */
        }
        .back-button i {
            margin-right: 8px;
        }
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #28a745;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
            z-index: 1000; /* Ensure it appears on top */
        }
        .popup-content {
            text-align: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .form-container {
            animation: fadeIn 1s ease-in-out;
        }
        .btn-primary{
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="user_panel.php" class="back-button"><i class="bi bi-arrow-left"></i>Back</a>
        <h2>Profile Settings</h2>
        <div class="form-container">
            <form action="settings.php" method="post">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password (Leave blank if not changing)</label>
                    <input type="password" name="new_password" class="form-control">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
