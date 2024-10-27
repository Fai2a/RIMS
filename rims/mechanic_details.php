<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Details</title>
    <style>
        /* Styling for the mechanic details page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 28px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background-color: #f9f9f9;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: 18px;
            text-align: left;
        }

        ul li strong {
            color: #333;
        }

        /* Button animation */
        .fade-in {
            animation: fadeIn 1.5s ease-in-out forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styling for bold day_of_week */
        .day-of-week {
            font-weight: bold;
            color: #333;
        }

        /* Back button styling */
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .back-btn i {
            margin-right: 10px;
        }

        .back-btn svg {
            fill: #fff;
        }
    </style>
</head>
<body>

<div class="container fade-in">
    <?php
    require_once "database.php"; // Include your database connection

    if (isset($_GET['mechanic_id'])) {
        $mechanicID = $_GET['mechanic_id'];

        // Query to fetch mechanic details by ID, including all day_of_week and time slots
        $sql = "SELECT 
                    u.firstname, 
                    u.lastname, 
                    GROUP_CONCAT(DISTINCT s.service_name SEPARATOR ', ') AS services,
                    t.day_of_week, 
                    t.start_time, 
                    t.end_time
                FROM 
                    user u 
                JOIN 
                    service s ON u.id = s.mechanic_id 
                JOIN 
                    timeslots t ON u.id = t.mechanic_id 
                WHERE 
                    u.id = ?
                GROUP BY t.day_of_week, t.start_time, t.end_time
                ORDER BY FIELD(t.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            // If the statement preparation fails, show an error
            echo "SQL Error: " . htmlspecialchars($conn->error);
            exit();
        }

        // Bind the parameter and execute the query
        $stmt->bind_param("i", $mechanicID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Fetch all rows
            $mechanicName = '';
            $services = '';
            $timeSlots = [];

            while ($row = $result->fetch_assoc()) {
                $mechanicName = $row['firstname'] . " " . $row['lastname'];
                $services = $row['services'];

                // Store time slots per day
                $timeSlots[$row['day_of_week']][] = $row['start_time'] . ' to ' . $row['end_time'];
            }

            // Display mechanic details
            echo "<h2>Mechanic Details for " . htmlspecialchars($mechanicName) . "</h2>";
            echo "<ul>";
            echo "<li><strong>Mechanic Name:</strong> " . htmlspecialchars($mechanicName) . "</li>";
            echo "<li><strong>Services:</strong> " . htmlspecialchars($services) . "</li>";
            echo "<li><strong>Available Time Slots:</strong></li>";
            echo "<ul>";

            // Loop through time slots and display each day with all respective times
            foreach ($timeSlots as $day => $slots) {
                echo "<li><span class='day-of-week'>" . htmlspecialchars($day) . ":</span> ";
                echo implode(", ", array_map('htmlspecialchars', $slots));
                echo "</li>";
            }

            echo "</ul>";
            echo "</ul>";
        } else {
            echo "<div class='alert alert-warning'>Mechanic details not found.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid mechanic ID.</div>";
    }

    mysqli_close($conn); // Close the database connection
    ?>

    <!-- Back button with icon -->
    <a href="javascript:history.back()" class="back-btn">
        <i>
            <!-- SVG icon for the back button -->
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708.708L3.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
            </svg>
        </i>
        Back
    </a>

</div>

</body>
</html>
