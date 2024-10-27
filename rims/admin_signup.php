<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .auth-form {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
            width: 30%;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .auth-form .form-control {
            margin-bottom: 15px;
        }

        .auth-form button {
            background-color: #007bff;
            color: white;
            width: 100%;
        }

        .auth-form button:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<?php
// Establishing Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rims";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

// Signup Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $adminUsername = $_POST['admin_username'];
    $adminEmail = $_POST['admin_email'];
    $adminPassword = $_POST['admin_password'];

    // Check for empty fields
    if (empty($adminUsername) || empty($adminEmail) || empty($adminPassword)) {
        $error = "All fields are required!";
    } 
    // Validate email
    elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } 
    // Process signup
    else {
        // Hash the password
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

        // Prepare and bind the SQL query
        $stmt = $conn->prepare("INSERT INTO admin_signup (admin_username, admin_email, admin_password) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $error = "SQL prepare error: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $adminUsername, $adminEmail, $hashedPassword);

            // Execute the statement
            if ($stmt->execute()) {
                $success = "Signup successful! Redirecting to login page...";

                // Redirect to login.php after 2 seconds
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'admin_login.php';
                        }, 2000);
                      </script>";
            } else {
                $error = "Error executing statement: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>

<div class="auth-form">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <!-- Signup Form -->
    <h2>Admin Signup</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="admin_username">Username</label>
            <input type="text" name="admin_username" class="form-control" placeholder="Enter username" required>
        </div>
        <div class="form-group">
            <label for="admin_email">Email</label>
            <input type="email" name="admin_email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="password" name="admin_password" class="form-control" placeholder="Enter password" required>
        </div>
        <div class="form-group text-center">
            <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
        </div>
        <div>
            <p>Have an Account? <a href="admin_login.php">Login</a></p>
        </div>
    </form>
</div>

</body>
</html>
