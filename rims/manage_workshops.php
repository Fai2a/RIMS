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

// Delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM workshops WHERE workshop_id = ?";
    
    // Prepare and execute delete query
    $stmt = $conn->prepare($delete_sql);
    if ($stmt === false) {
        die("SQL prepare error: " . $conn->error);
    }
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Workshop deleted successfully'); window.location.href='manage_workshops.php';</script>";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Query to fetch workshops
$sql = "SELECT * FROM workshops";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Workshops</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            text-align: center;
        }
        .back-button {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <div class="back-button">
            <a href="admin_panel.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
        <h2 class="my-4">Manage Workshops</h2>
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['workshop_id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>
                                        <a href='edit_workshop.php?workshop_id={$row['workshop_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='manage_workshops.php?delete_id={$row['workshop_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this workshop?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No workshops found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap Icons for Back Arrow -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
