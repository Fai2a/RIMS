<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 350px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="email"], input[type="password"], select {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 95%;
            font-size: 18px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="container">
    <?php
      // Start the session
      session_start();
    if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = $_POST["role"]; // Added role retrieval
        require_once "database.php";

        // Session lifetime increase logic (1 hour = 3600 seconds)
        ini_set('session.gc_maxlifetime', 3600); // 1 hour server-side session life
        ini_set('session.cookie_lifetime', 3600); // 1 hour client-side cookie life
        
      

        // SQL to retrieve user based on email
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind parameters and execute query
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            
            // Password verification
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on user role
                    if ($user['role'] === 'User') {
                        header("Location: user_panel.php");
                    } elseif ($user['role'] === 'Mechanic') {
                        $_SESSION['mechanic_id'] = $user['id'];
                        header("Location: mechanic_panel.php");
                    }
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Incorrect password</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Database query error</div>";
        }
    }
    ?>
        <form action="login.php" method="post" id="loginForm">
            <h2>Login</h2>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" required>
                    <option value="User">User</option>
                    <option value="Mechanic">Mechanic</option>
                    <!-- Add other roles if needed -->
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn-primary">Login</button>
            </div>
        </form>
        <div>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>
</body>
</html>
