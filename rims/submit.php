<?php
// Database connection details
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "rims";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars(trim($_POST['address']));
    $package = htmlspecialchars(trim($_POST['package']));
    $car_make = htmlspecialchars(trim($_POST['car_make']));
    $car_model = htmlspecialchars(trim($_POST['car_model']));

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO submit_form (name, email, address, package, car_make, car_model, date) VALUES (?, ?, ?, ?, ?, ?, NOW())");

    // Check if prepare() failed
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("ssssss", $name, $email, $address, $package, $car_make, $car_model);

    // Execute the statement
    if ($stmt->execute()) {
        // echo "New record created successfully";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to form page if accessed directly
    header("Location: form_page.html");
    exit();
}
?>
