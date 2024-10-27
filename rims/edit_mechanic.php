<?php
require_once "database.php"; // Include your database connection

if (isset($_GET['mechanic_id'])) {
    $mechanicID = $_GET['mechanic_id'];

    // Fetch mechanic details by ID including service names
    $sql = "SELECT 
                u.firstname, 
                u.lastname, 
                GROUP_CONCAT(DISTINCT s.service_id) AS service_ids,
                GROUP_CONCAT(DISTINCT s.service_name) AS service_names,
                GROUP_CONCAT(DISTINCT CONCAT(t.time_slot_id, ' - ', t.day_of_week, ' - ', t.start_time, ' to ', t.end_time) SEPARATOR '<br>') AS time_slots
            FROM 
                user u 
            JOIN 
                service s ON u.id = s.mechanic_id 
            JOIN 
                timeslots t ON u.id = t.mechanic_id 
            WHERE 
                u.id = ? 
            GROUP BY 
                u.id";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("SQL Error: " . htmlspecialchars($conn->error)); // Output the error and stop execution
    }

    // Bind the parameter and execute the query
    $stmt->bind_param("i", $mechanicID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $rowUpdated = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-warning'>Mechanic not found.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Invalid mechanic ID.</div>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update mechanic services
    if (isset($_POST['service'])) {
        foreach ($_POST['service'] as $serviceID) {
            // Ensure service name exists for this service ID
            if (isset($_POST['service_name'][$serviceID])) {
                $serviceName = $_POST['service_name'][$serviceID]; // Get service name
                
                $updateServiceSQL = "UPDATE services SET service_name = ? WHERE service_id = ?";
                $stmtService = $conn->prepare($updateServiceSQL);
                if ($stmtService === false) {
                    die("SQL Error on update: " . htmlspecialchars($conn->error)); // Output error if preparation fails
                }
                $stmtService->bind_param("si", $serviceName, $serviceID);
                $stmtService->execute();
            } else {
                echo "<div class='alert alert-warning'>Service name for ID $serviceID not found.</div>";
            }
        }
    }

    // Update mechanic time slots
    if (isset($_POST['time_slots'])) {
        foreach ($_POST['time_slots'] as $timeSlot) {
            $updateTimeSlotSQL = "UPDATE timeslots SET 
                                    day_of_week = ?, 
                                    start_time = ?, 
                                    end_time = ? 
                                  WHERE 
                                    time_slot_id = ?";
            $stmtTimeSlot = $conn->prepare($updateTimeSlotSQL);
            if ($stmtTimeSlot === false) {
                die("SQL Error on time slot update: " . htmlspecialchars($conn->error)); // Output error if preparation fails
            }
            $stmtTimeSlot->bind_param("sssi", $timeSlot['day_of_week'], $timeSlot['start_time'], $timeSlot['end_time'], $timeSlot['time_slot_id']);
            $stmtTimeSlot->execute();
        }
    }
    echo "<div class='alert alert-success'>Mechanic details updated successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mechanic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #fff;
        }
        .alert-success {
            background-color: #28a745;
        }
        .alert-warning {
            background-color: #ffc107;
        }
        .alert-danger {
            background-color: #dc3545;
        }
        input[type="text"], input[type="time"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Mechanic Details</h2>
    
    <form method="POST">
        <h3>Mechanic Information:</h3>
        <div>
            <strong>Name:</strong> <?= htmlspecialchars($rowUpdated['firstname'] . ' ' . $rowUpdated['lastname']) ?>
        </div>

        <h3>Services:</h3>
        <div id="services">
            <?php
            // Fetch service names and IDs
            $serviceIDs = explode(', ', $rowUpdated['service_ids']);
            $serviceNames = explode(', ', $rowUpdated['service_names']);

            foreach ($serviceIDs as $index => $serviceID) {
                ?>
                <div>
                    <input type="hidden" name="services[]" value="<?= htmlspecialchars($serviceID) ?>">
                    <label for="service_name_<?= $serviceID ?>">Service Name:</label>
                    <input type="text" id="service_name_<?= $serviceID ?>" name="service_name[<?= $serviceID ?>]" value="<?= htmlspecialchars($serviceNames[$index]) ?>" required>
                </div>
                <?php
            }
            ?>
        </div>

        <h3>Time Slots:</h3>
        <div id="timeSlots">
            <?php
            $timeSlotsArray = explode('<br>', $rowUpdated['time_slots']);
            foreach ($timeSlotsArray as $timeSlot) {
                $timeSlotData = explode(' - ', $timeSlot);
                if (count($timeSlotData) === 3) {
                    $timeSlotID = htmlspecialchars($timeSlotData[0]);
                    $dayOfWeek = htmlspecialchars($timeSlotData[1]);
                    $startEndTime = htmlspecialchars($timeSlotData[2]);
                    list($startTime, $endTime) = explode(' to ', $startEndTime);
                    ?>
                    <div>
                        <input type="hidden" name="time_slots[<?= $timeSlotID ?>][time_slot_id]" value="<?= $timeSlotID ?>">
                        <strong><?= $dayOfWeek ?>:</strong>
                        <input type="text" name="time_slots[<?= $timeSlotID ?>][day_of_week]" value="<?= $dayOfWeek ?>" required>
                        <input type="time" name="time_slots[<?= $timeSlotID ?>][start_time]" value="<?= $startTime ?>" required>
                        <input type="time" name="time_slots[<?= $timeSlotID ?>][end_time]" value="<?= $endTime ?>" required>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <button type="submit">Update Mechanic</button>
    </form>
    
    <br>
    <!-- Back button to redirect to manage_mechanics.php -->
    <a href="admin_panel.php"><button>Back</button></a>
</div>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
