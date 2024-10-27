<?php
 require_once "database.php";

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];

    // Update query to save the new details in the database
    $query = "UPDATE user SET firstname = ?, lastname = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Error preparing the SQL statement: ' . $conn->error);
    }

    // Bind the parameters and execute the update query
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);

    if ($stmt->execute()) {
        echo "User updated successfully!";
        // Redirect back to the manage users page or show a success message
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No user data provided!";
}

// Close the connection
$conn->close();
?>
