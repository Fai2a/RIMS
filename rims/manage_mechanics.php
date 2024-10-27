<?php
require_once "database.php"; // Include your database connection

// Query to fetch mechanic names, services, and time slots
$sql = "SELECT 
            u.id,
            u.firstname, 
            u.lastname, 
            GROUP_CONCAT(DISTINCT s.service_name SEPARATOR ', ') AS services,
            GROUP_CONCAT(DISTINCT CONCAT(t.start_time, ' to ', t.end_time) SEPARATOR ', ') AS time_slots
        FROM 
            user u 
        JOIN 
            service s ON u.id = s.mechanic_id 
        JOIN 
            timeslots t ON u.id = t.mechanic_id 
        WHERE 
            u.role = 'Mechanic' 
        GROUP BY 
            u.id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    // Display an error message if the query fails
    echo "SQL Error: " . mysqli_error($conn);
    exit();
}

if (mysqli_num_rows($result) > 0) {
    echo "<table class='table table-bordered'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Mechanic Name</th>";
   
    echo "<th>Action</th>"; // Add action column here
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Display the mechanics' data
    while ($row = mysqli_fetch_assoc($result)) {
        $mechanicID = $row['id'];
        $mechanicName = $row['firstname'] . " " . $row['lastname'];
        $services = $row['services'];
        $timeSlots = $row['time_slots'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($mechanicName) . "</td>";
      
    
        
        // Action column with Edit and View buttons
        echo "<td>";
        echo "<form action='mechanic_details.php' method='GET' style='display:inline-block;'>";
        echo "<input type='hidden' name='mechanic_id' value='" . htmlspecialchars($mechanicID) . "'>";
        echo "<button type='submit' class='btn btn-info'>View Details</button>";
        echo "</form>";

        echo "<form action='edit_mechanic.php' method='GET' style='display:inline-block;'>";
        echo "<input type='hidden' name='mechanic_id' value='" . htmlspecialchars($mechanicID) . "'>";
        echo "<button type='submit' class='btn btn-warning'>Edit</button>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div class='alert alert-warning'>Koi mechanics available nahi hain!</div>";
}

mysqli_close($conn); // Close the database connection
?>