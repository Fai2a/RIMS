<?php
// Ensure session starts at the top of the PHP file
session_start();

// Database connection details
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

// Function to geocode address
function geocodeAddress($address)
{
    $apiKey = 'AIzaSyByHRgDv9tLS9tgAQ0D-pzTjO7vgsKv7NA'; // Replace with your Google Maps API key
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['status'] === 'OK') {
        return [
            'latitude' => $data['results'][0]['geometry']['location']['lat'],
            'longitude' => $data['results'][0]['geometry']['location']['lng']
        ];
    }
    return null;
}

// Function to fetch mechanics with services and time slots without duplicates
function fetchMechanicsData($conn)
{
    $sql = "
        SELECT 
            u.id AS mechanic_id, 
            u.firstname AS mechanic_name, 
            u.address AS mechanic_address
        FROM 
            user u
        WHERE 
            u.role = 'mechanic'
    ";

    $mechanics = [];
    $result = $conn->query($sql);
    if (!$result) {
        die("Error in query: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $geocodeData = geocodeAddress($row['mechanic_address']);
        $mechanics[$row['mechanic_id']] = [
            'mechanic_name' => $row['mechanic_name'],
            'mechanic_address' => $row['mechanic_address'],
            'latitude' => $geocodeData['latitude'] ?? null,
            'longitude' => $geocodeData['longitude'] ?? null,
            'services' => [],
            'timeslots' => []
        ];
    }

    $sql_services = "
        SELECT 
            s.mechanic_id, 
            s.service_name, 
            s.service_price, 
            s.service_duration
        FROM 
            service s
    ";

    $result_services = $conn->query($sql_services);
    while ($row = $result_services->fetch_assoc()) {
        $mechanics[$row['mechanic_id']]['services'][] = [
            'service_name' => $row['service_name'],
            'service_price' => $row['service_price'],
            'service_duration' => $row['service_duration']
        ];
    }

    $sql_timeslots = "
        SELECT 
            ts.mechanic_id, 
            ts.start_time, 
            ts.end_time
        FROM 
            timeslots ts
    ";

    $result_timeslots = $conn->query($sql_timeslots);
    while ($row = $result_timeslots->fetch_assoc()) {
        $mechanics[$row['mechanic_id']]['timeslots'][] = [
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time']
        ];
    }

    return $mechanics;
}

// Function to insert booking details into the database
function insertBooking($conn, $mechanicId, $serviceId, $timeslots)
{
    $stmt = $conn->prepare("INSERT INTO appointments (firstname,lastname,role, start_time, end_time, service_name) VALUES (?,?, ?, ?, ?,?)");
    $stmt->bind_param("iisss", $mechanicId, $serviceId, $timeslots['start_time'], $timeslots['end_time']);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

$mechanics = fetchMechanicsData($conn);
$mechanics_data = json_encode($mechanics);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Services Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 12px;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 400px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal-header {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .modal-close {
            cursor: pointer;
            font-size: 24px;
            position: absolute;
            top: 10px;
            right: 10px;
            color: #888;
        }

        .modal-close:hover {
            color: red;
        }

        .modal-content {
            font-size: 16px;
            line-height: 1.6;
        }

        .modal button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .modal button:hover {
            background-color: #0056b3;
        }

        #seeTimeslotsBtn,
        #viewServicesBtn,
        #confirmBookingBtn,
        #confirmYes,
        #confirmNo {
            display: block;
            width: 100%;
            font-size: 18px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- Mechanic Details Modal -->
    <div class="modal" id="mechanicDetailsModal">
        <div class="modal-header">
            Mechanic Details
            <span class="modal-close" id="closeMechanicDetails">&times;</span>
        </div>
        <div class="modal-content" id="mechanicInfo"></div>
        <button id="seeTimeslotsBtn">See Time Slots</button>
    </div>

    <!-- Timeslot Modal -->
    <div class="modal" id="timeslotModal">
        <div class="modal-header">
            Pick a Time Slot
            <span class="modal-close" id="closeTimeslotModal">&times;</span>
        </div>
        <div class="modal-content" id="timeslotDetails"></div>
        <button id="viewServicesBtn">View Services</button>
    </div>

    <!-- Service Modal -->
    <div class="modal" id="serviceModal">
        <div class="modal-header">
            Pick a Service
            <span class="modal-close" id="closeServiceModal">&times;</span>
        </div>
        <div class="modal-content" id="serviceDetails"></div>
        <button id="confirmBookingBtn">Confirm Booking</button>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-header">
            Confirm Booking
            <span class="modal-close" id="closeConfirmationModal">&times;</span>
        </div>
        <div class="modal-content" id="confirmationDetails"></div>
        <button id="confirmYes">Yes</button>
        <button id="confirmNo">No</button>
    </div>
  

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([30.3753, 69.3451], 6); // Pakistan

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const mechanics = <?php echo $mechanics_data; ?>;
        let selectedMechanic = null;
        let selectedTimeslot = null;
        let selectedService = null;

        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function showMechanicDetails(mechanic) {
            const mechanicInfo = document.getElementById('mechanicInfo');
            mechanicInfo.innerHTML = `<h3>${mechanic.mechanic_name}</h3><p>${mechanic.mechanic_address}</p>`;
            showModal('mechanicDetailsModal');
            selectedMechanic = mechanic;
        }

        function showTimeslots() {
            const timeslotDetails = document.getElementById('timeslotDetails');
            timeslotDetails.innerHTML = selectedMechanic.timeslots.map((slot) => {
                return `<div><input type="radio" name="timeslot" value='${JSON.stringify(slot)}'> ${slot.start_time} - ${slot.end_time}</div>`;
            }).join('');
            showModal('timeslotModal');
        }

        function showServices() {
            const serviceDetails = document.getElementById('serviceDetails');
            serviceDetails.innerHTML = selectedMechanic.services.map((service) => {
                return `<div><input type="radio" name="service" value='${JSON.stringify(service)}'> ${service.service_name} - ${service.service_price}</div>`;
            }).join('');
            showModal('serviceModal');
        }

        function showConfirmation() {
            const confirmationDetails = document.getElementById('confirmationDetails');
            confirmationDetails.innerHTML = `Are you sure you want to book ${selectedService.service_name} with ${selectedMechanic.mechanic_name} at ${selectedTimeslot.start_time}?`;
            showModal('confirmationModal');
        }

        function confirmBooking() {
            // Check if the selected mechanic, timeslot, and service are not null
            if (!selectedMechanic || !selectedTimeslot || !selectedService) {
                console.error('Please select a mechanic, timeslot, and service before booking.');
                return; // Exit the function if any are missing
            }

            const mechanicId = selectedMechanic.mechanic_id; // Ensure mechanic_id is set
            const start_time = selectedTimeslot.start_time; // Ensure start_time is set
            const end_time = selectedTimeslot.end_time; // Ensure end_time is set
            const service_name = selectedService.service_name; // Ensure service_name is set

            // Log the values to debug
            console.log("Mechanic ID:", mechanicId);
            console.log("Start Time:", start_time);
            console.log("End Time:", end_time);
            console.log("Service Name:", service_name);

            // Proceed with the fetch request
            fetch('store_appointment.php', {
            method: 'POST',
            headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        mechanicId,
        start_time,
        end_time,
        service_name
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);

    // Show popup message based on success or failure
    if (data.success) {
        alert('Booking successful: ' + data.message);  // Show success message in popup
    } else {
        alert('Booking successfull: ' + data.message);  // Show failure message in popup
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('An error occurred: ' + error);  // Show error in popup
});
        }

        // Example functions to set selected values (these should be called when user selects options)
        function selectMechanic(mechanic) {
            selectedMechanic = mechanic;
        }

        function selectTimeslot(timeslot) {
            selectedTimeslot = timeslot;
        }

        function selectService(service) {
            selectedService = service;
        }


        // Event listeners for modals
        document.getElementById('closeMechanicDetails').onclick = () => closeModal('mechanicDetailsModal');
        document.getElementById('closeTimeslotModal').onclick = () => closeModal('timeslotModal');
        document.getElementById('closeServiceModal').onclick = () => closeModal('serviceModal');
        document.getElementById('closeConfirmationModal').onclick = () => closeModal('confirmationModal');

        document.getElementById('seeTimeslotsBtn').onclick = () => {
            showTimeslots();
            closeModal('mechanicDetailsModal');
        };

        document.getElementById('viewServicesBtn').onclick = () => {
            selectedTimeslot = JSON.parse(document.querySelector('input[name="timeslot"]:checked').value);
            showServices();
            closeModal('timeslotModal');
        };

        document.getElementById('confirmBookingBtn').onclick = () => {
            selectedService = JSON.parse(document.querySelector('input[name="service"]:checked').value);
            showConfirmation();
            closeModal('serviceModal');
        };

        document.getElementById('confirmYes').onclick = confirmBooking;
        document.getElementById('confirmNo').onclick = () => closeModal('confirmationModal');


        // Add markers to the map for each mechanic
        Object.entries(mechanics).forEach(([id, mechanic]) => {
            const marker = L.marker([mechanic.latitude, mechanic.longitude]).addTo(map);
            marker.on('click', () => showMechanicDetails(mechanic));
        });
    </script>
</body>

</html>