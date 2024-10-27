<?php
// Database connection
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

// Delete user if delete_id is set
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM user WHERE id = ?";
    
    $stmt = $conn->prepare($delete_sql);
    if ($stmt === false) {
        die("SQL prepare error: " . $conn->error);
    }
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showPopup();
            });
        </script>";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Query to fetch users with role = 'user'
$sql = "SELECT * FROM user WHERE role = 'user'";
$result = $conn->query($sql);

// Check if query execution was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- For icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #0044cc 30%, #f4f4f4 30%);
        }
        .left-side {
            background-color: #00aaff; /* Blue color for the left half */
            height: 100%;
            width: 20%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1; /* Ensure the background color stays behind the content */
        }
        .container {
            padding: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 70%;
            margin-bottom: 20px;
        }
        th, td {
            text-align: center;
        }
        .back-button {
            margin: 20px 0;
        }
        .popup {
            visibility: hidden;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            background-color: #0044cc;
            color: white;
            text-align: center;
            border-radius: 5px;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            transition: visibility 0s, opacity 0.5s linear;
            opacity: 0;
        }

        .popup.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="left-side"></div> <!-- Left side background color -->
    
    <!-- Popup for User Deleted -->
    <div id="popup" class="popup">User deleted successfully</div>

    <div class="container">
        <!-- Back Button -->
        <div class="back-button">
            <a href="admin_panel.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
        <h2 class="my-4">Manage Users</h2>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['firstname']}</td> 
                                    <td>{$row['lastname']}</td> 
                                    <td>{$row['email']}</td>
                                    <td>
                                        <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='manage_users.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showPopup() {
            var popup = document.getElementById('popup');
            popup.classList.add('show');
            setTimeout(function() {
                popup.classList.remove('show');
            }, 3000); // Popup will disappear after 3 seconds
        }
    </script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
