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

// Initialize variables
$workshopName = '';
$workshopId = isset($_GET['workshop_id']) ? $_GET['workshop_id'] : '';
$updateSuccess = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workshopName = isset($_POST['name']) ? $_POST['name'] : '';

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE workshops SET name = ? WHERE workshop_id = ?");

    if ($stmt === false) {
        die("SQL prepare error: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("si", $workshopName, $workshopId);

    // Execute the statement
    if ($stmt->execute()) {
        $updateSuccess = true;
    } else {
        $error = "Error updating workshop: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Fetch workshop details for editing
if ($workshopId) {
    $stmt = $conn->prepare("SELECT name FROM workshops WHERE workshop_id = ?");
    if ($stmt === false) {
        die("SQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("i", $workshopId);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();

    // Set workshop name for form
    $workshopName = $name ? $name : '';

    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workshop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* Background Styles */
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #0044cc 50%, #f4f4f4 50%);
        }

        /* Left Side Background Color */
        .left-side {
            background-color: #00aaff; /* Blue color for the left half */
            height: 100%;
            width: 50%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1; /* Ensure the background color stays behind the content */
        }

        /* Animation for form */
        .container {
            animation: fadeIn 1s ease-in-out;
            width: 50%;
            margin-left: 50%;
            padding: 20px;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-primary {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Styles for form div */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Adjust max width if needed */
            margin: 0 auto; /* Center the div */
        }

        .form-control {
            width: 100%;
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
        }

        /* Back button styling */
        .btn-secondary {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="left-side"></div>

<div class="container mt-5">
    <div class="form-container">
        <h2>Edit Workshop</h2>
        <a href="manage_workshops.php" class="btn btn-secondary mb-3">&larr; Back</a>
        <form action="edit_workshop.php?id=<?php echo htmlspecialchars($workshopId); ?>" method="post">
            <div class="form-group">
                <label for="name">Workshop Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($workshopName); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Workshop</button>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Workshop updated successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Show success modal if updateSuccess is true
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($updateSuccess): ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        <?php endif; ?>
    });
</script>

</body>
</html>
