<?php
session_start();
require_once 'database.php';

// Check if mechanic_id is set in the session
if (!isset($_SESSION['mechanic_id'])) {
    echo "Mechanic login nahi hua.";
    exit();
}

$mechanic_id = $_SESSION['mechanic_id'];

// Fetch mechanic data based on mechanic_id
$sql = "SELECT id, firstname, lastname, email, role FROM user WHERE id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $mechanic_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if data exists
    $mechanic = mysqli_fetch_assoc($result);
    if (!$mechanic) {
        echo "Mechanic ka data nahi mila.";
        exit();
    }
} else {
    echo "Query execute nahi ho sakti.";
    exit();
}

// Mechanic profile update (email/password)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_email = $_POST['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $update_sql = "UPDATE user SET email = ?, password = ? WHERE id = ?";
    $update_stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($update_stmt, $update_sql)) {
        mysqli_stmt_bind_param($update_stmt, "ssi", $new_email, $new_password, $mechanic_id);
        if (mysqli_stmt_execute($update_stmt)) {
            echo "Profile update ho gayi hai.";
        } else {
            echo "Error: Profile update nahi ho sakti.";
        }
    }
}

// Mechanic adds service with price and duration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $service_price = $_POST['service_price'];
    $service_duration = $_POST['service_duration'];

    $service_sql = "INSERT INTO service (mechanic_id, service_name, service_price, service_duration) VALUES (?, ?, ?, ?)";
    $service_stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($service_stmt, $service_sql)) {
        mysqli_stmt_bind_param($service_stmt, "isdi", $mechanic_id, $service_name, $service_price, $service_duration);
        if (mysqli_stmt_execute($service_stmt)) {
            echo "Service add ho gayi hai.";
        } else {
            echo "Error: Service add nahi ho sakti.";
        }
    }
}

// Mechanic adds time slots
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_timeslot'])) {
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $timeslot_sql = "INSERT INTO timeslots (mechanic_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)";
    $timeslot_stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($timeslot_stmt, $timeslot_sql)) {
        mysqli_stmt_bind_param($timeslot_stmt, "isss", $mechanic_id, $day_of_week, $start_time, $end_time);
        if (mysqli_stmt_execute($timeslot_stmt)) {
            echo "Time slot add ho gaya hai.";
        } else {
            echo "Error: Time slot add nahi ho sakta.";
        }
    }
}

// Fetch mechanic services
$services_sql = "SELECT service_name, service_price, service_duration FROM service WHERE mechanic_id = ?";
$services_stmt = mysqli_stmt_init($conn);

$services = [];
if (mysqli_stmt_prepare($services_stmt, $services_sql)) {
    mysqli_stmt_bind_param($services_stmt, "i", $mechanic_id);
    mysqli_stmt_execute($services_stmt);
    $result = mysqli_stmt_get_result($services_stmt);
    $services = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch mechanic's upcoming appointments
$appointments_sql = "SELECT a.appointment_id, u.firstname, u.lastname, u.role, s.service_name, a.start_time, a.end_time, a.status 
                     FROM appointments a
                     JOIN user u ON a.user_id = u.id
                     JOIN service s ON a.service_id = s.id
                     WHERE a.mechanic_id = ?";
$appointments_stmt = mysqli_stmt_init($conn);

$appointments = [];
if (mysqli_stmt_prepare($appointments_stmt, $appointments_sql)) {
    mysqli_stmt_bind_param($appointments_stmt, "i", $mechanic_id);
    mysqli_stmt_execute($appointments_stmt);
    $result = mysqli_stmt_get_result($appointments_stmt);
    $appointments = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch time slots
$timeslots_sql = "SELECT day_of_week, start_time, end_time FROM timeslots WHERE mechanic_id = ?";
$timeslots_stmt = mysqli_stmt_init($conn);

$timeslots = [];
if (mysqli_stmt_prepare($timeslots_stmt, $timeslots_sql)) {
    mysqli_stmt_bind_param($timeslots_stmt, "i", $mechanic_id);
    mysqli_stmt_execute($timeslots_stmt);
    $result = mysqli_stmt_get_result($timeslots_stmt);
    $timeslots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
            max-width: 1000px;
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

        .profile-info,
        .settings-info,
        .services-info,
        .appointments-info,
        .timeslots-info {
            padding: 20px;
            border: 1px solid #007bff;
            border-radius: 10px;
            background: white;
            margin-bottom: 20px;
            animation: fadeIn 1s;
        }

        .profile-info h3,
        .settings-info h3,
        .services-info h3,
        .appointments-info h3,
        .timeslots-info h3 {
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

        .logout-btn {
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
                <li><a href="#" id="servicesLink">Services</a></li>
                <li><a href="#" id="settingsLink">Settings</a></li>
                <li><a href="#" id="timeslotsLink">Time Slots</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Profile Section -->
            <div class="profile-info" id="profileSection">
                <h3>Mechanic Profile</h3>
                <p><strong>First Name:</strong> <?php echo $mechanic['firstname']; ?></p>
                <p><strong>Last Name:</strong> <?php echo $mechanic['lastname']; ?></p>
                <p><strong>Email:</strong> <?php echo $mechanic['email']; ?></p>
                <p><strong>Role:</strong> <?php echo $mechanic['role']; ?></p>
                <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>
            </div>

            <!-- Settings Section -->
            <div class="settings-info" id="settingsSection" style="display:none;">
                <h3>Update Profile</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $mechanic['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>

            <!-- Services Section -->
            <div class="services-info" id="servicesSection" style="display:none;">
                <h3>Add Service</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="service_price" class="form-label">Service Price</label>
                        <input type="number" class="form-control" id="service_price" name="service_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="service_duration" class="form-label">Service Duration (in hours)</label>
                        <input type="number" class="form-control" id="service_duration" name="service_duration" step="0.1" required>
                    </div>
                    <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
                </form>

                <h3>Your Services</h3>
                <ul>
                    <?php foreach ($services as $service) : ?>
                        <li><?php echo $service['service_name'] . " - Rs." . $service['service_price'] . " - Duration: " . $service['service_duration'] . " hours"; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Appointments Section -->
            <!-- Appointments Section -->
<div class="appointments-info" id="appointmentsSection" style="display:none;">
    <h3>Appointments</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Service</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Sample dummy appointments
            $dummy_appointments = [
                ['firstname' => 'Ali', 'lastname' => 'Khan', 'service_name' => 'Brake Repairs', 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'status' => 'Pending'],
                ['firstname' => 'Sara', 'lastname' => 'Ahmed', 'service_name' => 'Engine Tune-Up', 'start_time' => '11:30 AM', 'end_time' => '12:30 PM', 'status' => 'Completed'],
                ['firstname' => 'Fahad', 'lastname' => 'Shaikh', 'service_name' => 'Fluid Checks', 'start_time' => '1:00 PM', 'end_time' => '2:00 PM', 'status' => 'Completed'],
                ['firstname' => 'Ayesha', 'lastname' => 'Zafar', 'service_name' => 'Transmission Service', 'start_time' => '2:30 PM', 'end_time' => '3:30 PM', 'status' => 'Completed'],
            ];

            foreach ($dummy_appointments as $appointment) : 
                // Set status button classes based on the status
                $status_class = '';
                switch ($appointment['status']) {
                    case 'Completed':
                    case 'Confirmed':
                        $status_class = 'btn btn-success'; // Green button for Completed/Confirmed
                        break;
                    case 'Pending':
                        $status_class = 'btn btn-warning'; // Yellow button for Pending
                        break;
                    case 'In Progress':
                        $status_class = 'btn btn-info'; // Blue button for In Progress
                        break;
                    default:
                        $status_class = 'btn btn-secondary'; // Default button style
                }
            ?>
                <tr>
                    <td><?php echo $appointment['firstname'] . " " . $appointment['lastname']; ?></td>
                    <td><?php echo $appointment['service_name']; ?></td>
                    <td><?php echo $appointment['start_time']; ?></td>
                    <td><?php echo $appointment['end_time']; ?></td>
                    <td><button class="<?php echo $status_class; ?>"><?php echo $appointment['status']; ?></button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


            <!-- Timeslots Section -->
            <div class="timeslots-info" id="timeslotsSection" style="display:none;">
                <h3>Add Time Slot</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Day of the Week</label>
                        <input type="text" class="form-control" id="day_of_week" name="day_of_week" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>
                    <button type="submit" name="add_timeslot" class="btn btn-primary">Add Time Slot</button>
                </form>

                <h3>Your Time Slots</h3>
                <ul>
                    <?php foreach ($timeslots as $timeslot) : ?>
                        <li><?php echo $timeslot['day_of_week'] . ": " . $timeslot['start_time'] . " - " . $timeslot['end_time']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profileLink').addEventListener('click', function() {
            showSection('profileSection');
        });
        document.getElementById('appointmentsLink').addEventListener('click', function() {
            showSection('appointmentsSection');
        });
        document.getElementById('servicesLink').addEventListener('click', function() {
            showSection('servicesSection');
        });
        document.getElementById('settingsLink').addEventListener('click', function() {
            showSection('settingsSection');
        });
        document.getElementById('timeslotsLink').addEventListener('click', function() {
            showSection('timeslotsSection');
        });

        function showSection(sectionId) {
            const sections = document.querySelectorAll('.content > div');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</body>

</html>
