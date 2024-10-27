<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rims";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data and session
if (isset($data['service_name'], $data['timeslots']['start_time'], $data['timeslots']['end_time']) && 
    isset($_SESSION['user_id'])) {

    // Get user ID from session
    $userId = $_SESSION['user_id'];

    // Fetch user's firstname and lastname based on role
    $userDetails = getUserDetailsByRole($conn, $userId);

    if ($userDetails) {
        $firstname = $userDetails['firstname'];
        $lastname = $userDetails['lastname'];

        // Get service name and timeslots from input
        $serviceName = $data['service_name'];
        $start_time = $data['timeslots']['start_time'];
        $end_time = $data['timeslots']['end_time'];

        // Insert booking details
        if (insertBooking($conn, $firstname, $lastname, $role, $serviceName, $start_time, $end_time)) {
            echo json_encode(['success' => true, 'message' => 'Booking successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User role not found.']);
    }
} else {
    // Debugging missing fields
    $missingFields = [];
    if (!isset($data['service_name'])) $missingFields[] = 'service_name';
    if (!isset($data['timeslots']['start_time'])) $missingFields[] = 'start_time';
    if (!isset($data['timeslots']['end_time'])) $missingFields[] = 'end_time';
    if (!isset($_SESSION['user_id'])) $missingFields[] = 'user_id';
    
    echo json_encode([
        'success' => false, 
        'message' => ': ' 
    ]);
}

$conn->close();

function getUserDetailsByRole($conn, $userId) {
    // Fetch user details (firstname, lastname) based on the role 'mechanic'
    $query = "SELECT firstname, lastname FROM users WHERE id = ? AND role = 'mechanic'";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        return false;
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // Return user's firstname and lastname
    } else {
        return false; // No user found with the mechanic role
    }
}

function insertBooking($conn, $firstname, $lastname, $role, $serviceName, $start_time, $end_time) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO appointments (firstname, lastname, role, service_name, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        return false; // Prepare failed
    }

    // Bind parameters
    $stmt->bind_param("ssssss", $firstname, $lastname, $role, $serviceName, $start_time, $end_time);
    
    // Execute the statement and return the result
    if ($stmt->execute()) {
        return true;
    } else {
        echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
        return false; // Execution failed
    }
}
?>
