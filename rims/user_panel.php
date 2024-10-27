<?php

// Set session cookie parameters and start the session
session_set_cookie_params(3600); // Set cookie lifetime to 1 hour
session_start();
require_once 'database.php';

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Increase session lifetime
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time

// Fetch user details using the user_id from the session
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, firstname, lastname, email, role FROM user WHERE id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error: Could not fetch user details.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #007bff, #00bfff);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            flex: 1;
            padding: 20px;
            border-right: 2px solid #ccc;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            font-size: 18px;
            display: block;
            padding: 10px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }

        .content {
            flex: 3;
            padding: 20px;
        }

        .profile-info, .appointments-info {
            padding: 20px;
            border: 1px solid #007bff;
            border-radius: 10px;
            background: white;
            animation: fadeIn 1s;
            display: none; /* Hide by default */
        }

        .profile-info h3, .appointments-info h3 {
            color: #007bff;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-info p, .appointments-info p {
            font-size: 18px;
            margin: 10px 0;
        }

        .logout-btn, .home-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <ul class="menu">
                <li><a href="#" id="profileLink">Profile</a></li>
                <li><a href="#" id="appointmentsLink">Appointments</a></li>
                <li><a href="settings.php" id="settingsLink">Settings</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Profile Section -->
            <div class="profile-info" id="profileSection">
                <h3>User Profile</h3>
                <p><strong>First Name:</strong> <?php echo $user['firstname']; ?></p>
                <p><strong>Last Name:</strong> <?php echo $user['lastname']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
                <a href="index.html" class="btn btn-danger logout-btn">Logout</a>
                <a href="map.php" class="btn btn-primary home-btn">Explore Workshops</a>
            </div>

            <!-- Appointments Section -->
           <!-- Appointments Section -->
<div class="appointments-info" id="appointmentsSection" style="display:none;">
    <h3>Appointments</h3>
    <table class="table custom-table">
        <thead>
            <tr>
                <th>Mechanic Name</th>
                <th>Service Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Dummy appointments with status
            $appointments = [
                [
                    'mechanic_name' => 'Tahir Ali',
                    'service_name' => 'Brake Repair',
                    'start_time' => '10:00 AM',
                    'end_time' => '11:00 AM',
                    'status' => 'Pending'
                ],
                [
                    'mechanic_name' => 'Bashir Ahmad',
                    'service_name' => 'Oil Change',
                    'start_time' => '12:00 PM',
                    'end_time' => '12:45 PM',
                    'status' => 'Completed'
                ],
                [
                    'mechanic_name' => 'Zain Ahmed',
                    'service_name' => 'Tire Replacement',
                    'start_time' => '2:00 PM',
                    'end_time' => '3:00 PM',
                    'status' => 'Completed'
                ]
            ];

            foreach ($appointments as $appointment) {
                echo "<tr>";
                echo "<td>{$appointment['mechanic_name']}</td>";
                echo "<td>{$appointment['service_name']}</td>";
                echo "<td>{$appointment['start_time']}</td>";
                echo "<td>{$appointment['end_time']}</td>";
                
                // Status buttons
                $status = $appointment['status'];
                if ($status == 'Completed') {
                    echo "<td><button class='btn btn-success btn-sm'>Completed</button></td>";
                } elseif ($status == 'Confirmed') {
                    echo "<td><button class='btn btn-primary btn-sm'>Confirmed</button></td>";
                } else {
                    echo "<td><button class='btn btn-warning btn-sm'>Pending</button></td>";
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

        </div>
    </div>

    <script>
        // Function to hide all sections
        function hideAllSections() {
            const sections = document.querySelectorAll(".content div");
            sections.forEach(section => section.style.display = "none");
        }

        // Show Profile Section by default
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("profileSection").style.display = "block";
        });

        // Event listener for Profile Link
        document.getElementById("profileLink").addEventListener("click", function (e) {
            e.preventDefault();
            hideAllSections();
            document.getElementById("profileSection").style.display = "block";
        });

        // Event listener for Appointments Link
        document.getElementById("appointmentsLink").addEventListener("click", function (e) {
            e.preventDefault();
            hideAllSections();
            document.getElementById("appointmentsSection").style.display = "block";
        });

        // Event listener for Home button to increase session timeout
        document.querySelector('.home-btn').addEventListener('click', function () {
            // Update session activity time on home button click
            <?php $_SESSION['LAST_ACTIVITY'] = time(); ?>
        });
    </script>
</body>

</html>
