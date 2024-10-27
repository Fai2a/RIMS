<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

// Login Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $adminEmail = $_POST['admin_email'];
    $adminPassword = $_POST['admin_password'];

    // Check for empty fields
    if (empty($adminEmail) || empty($adminPassword)) {
        $error = "Email and password are required!";
    } 
    // Validate email format
    elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } 
    // Process login
    else {
        // Prepare SQL query to check if the admin exists in the database
        $stmt = $conn->prepare("SELECT admin_password FROM admin_signup WHERE admin_email = ?");
        if ($stmt === false) {
            $error = "SQL prepare error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $adminEmail);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();

                // Verify password
                if (password_verify($adminPassword, $hashedPassword)) {
                    // Password is correct, redirect to admin panel
                    echo "<script>
                            window.location.href = 'admin_panel.php';
                          </script>";
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "No admin found with this email!";
            }

            $stmt->close();
        }
    }
}

// Close the connection
$conn->close();
?>

<div class="auth-form">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Login Form -->
    <h2>Admin Login</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="admin_email">Email</label>
            <input type="email" name="admin_email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="password" name="admin_password" class="form-control" placeholder="Enter password" required>
        </div>
        <div class="form-group text-center">
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>

</body>
</html>
