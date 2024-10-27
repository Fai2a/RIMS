<?php
 require_once "database.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Query to fetch user details for the provided user ID
    $query = "SELECT firstname, lastname, email FROM user WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Error preparing the SQL statement: ' . $conn->error);
    }

    // Bind parameters and execute the query
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user is found
    if ($user) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-4">
                <h2>Edit User</h2>
                
                <!-- Back Button -->
                <a href="manage_users.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <form action="update_user.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="firstname" value="<?php echo $user['firstname']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="lastname" value="<?php echo $user['lastname']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "User not found!";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No user ID provided!";
}

// Close the connection
$conn->close();
?>
