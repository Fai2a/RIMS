<?php
// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get workshop ID from POST request
    $workshop_id = $_POST['workshop_id'];

    // Database connection details
    $servername = "localhost";
    $username = "root";  // Your database username
    $password = "";      // Your database password
    $dbname = "rims";    // Your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch services and time slots for the selected workshop
    $query = "
        SELECT 
            w.workshop_id, 
            GROUP_CONCAT(DISTINCT s.service_type SEPARATOR ', ') AS services, 
            GROUP_CONCAT(DISTINCT t.time_slots SEPARATOR ', ') AS time_slots
        FROM workshops w
        LEFT JOIN service s ON w.workshop_id = s.workshop_id
        LEFT JOIN time_slots t ON w.workshop_id = t.workshop_id
        WHERE w.workshop_id = $workshop_id
        GROUP BY w.workshop_id
    ";

    // Execute the query
    $result = $conn->query($query);

    // Check if data is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Prepare the response with services and time slots
        $response = [
            'services' => $row['services'],
            'time_slots' => $row['time_slots']
        ];

        // Return the response as JSON
        echo json_encode($response);
    } else {
        // If no data is found, return an error
        echo json_encode(['error' => 'No data found']);
    }

    // Close the database connection
    $conn->close();
}
?>
