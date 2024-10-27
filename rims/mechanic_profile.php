<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <title>Profile</title>
  <style>
    /* Add basic styling */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #074a91, #0957a9);
      color: #fff;
      overflow-x: hidden;
    }

    .main {
      height: 100vh;
      width: 100vw;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 50px;
    }

    .profile-content {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.6);
      margin: 0 auto;
      display: flex;
      max-width: 500px;
      padding: 2vw;
      border-radius: 15px;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      animation: fadeIn 1.5s ease-in-out;
    }

    .profile-content #description {
      text-align: center;
    }

    img {
      width: 150px;
      height: 150px;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
      animation: bounce 2s infinite;
    }

    .rating {
      display: flex;
      align-items: center;
      margin-top: 15px;
    }

    .rating span {
      font-size: 30px;
      color: gold;
      animation: starTwinkle 1.5s infinite;
    }

    .services-table {
      width: 100%;
      max-width: 500px;
      margin-top: 20px;
      border-collapse: collapse;
      color: #fff;
    }

    .services-table th, .services-table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    .services-table th {
      background-color: #0957a9;
    }

    .services-table td {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .contact-mechanic {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 10px;
      animation: slideUp 1.5s ease-in-out;
    }

    .calendar-input,
    input[type="email"],
    select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
      width: 100%;
      max-width: 300px;
      outline: none;
      transition: all 0.3s ease;
    }

    input[type="email"]:focus,
    .calendar-input:focus,
    select:focus {
      border-color: #fff;
    }

    #book-appointment {
      display: inline-block;
      padding: 10px 15px;
      background-color: #fff;
      color: #0957a9;
      text-align: center;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: bold;
    }

    #book-appointment:hover {
      background-color: #ffb74d;
      color: #fff;
    }

    .appointment-form {
      display: none; /* Form initially hidden */
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      max-width: 400px;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.6);
      animation: fadeSlideIn 0.8s ease forwards;
    }

    .appointment-form input {
      padding: 10px;
      margin-bottom: 10px;
      width: 95%;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    #submit-appointment {
      padding: 10px 15px;
      background-color: #0957a9;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: bold;
      width: 100%;
    }

    #submit-appointment:hover {
      background-color: #ffb74d;
      color: #fff;
    }

    /* Animations */
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

    @keyframes slideUp {
      from {
        transform: translateY(100px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes bounce {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    @keyframes starTwinkle {
      0%, 100% {
        opacity: 0.8;
      }
      50% {
        opacity: 1;
      }
    }

    /* Form fade and slide animation */
    @keyframes fadeSlideIn {
      0% {
        opacity: 0;
        transform: translate(-50%, -60%);
      }
      100% {
        opacity: 1;
        transform: translate(-50%, -50%);
      }
    }
  </style>
</head>

<body>
<div class="main">
    <div class="profile-content">
      <img id="img" src="" alt="profile image">
      <div class="rating">
        <span>&#9733;</span>
        <span>&#9733;</span>
        <span>&#9733;</span>
        <span>&#9733;</span>
        <span>&#9734;</span>
      </div>
      <h1 id="title">Profile Title</h1>
      <p id="description">Profile Description</p>
    </div>

    <!-- Table for services -->
    <table class="services-table" id="servicesTable">
      <thead>
        <tr>
          <th>Service</th>
          <th>Price</th>
          <th>Duration</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // Connect to the database
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "rims"; // Your database name

          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Fetch services from the database
          $sql = "SELECT service_type, service_cost, service_duration FROM service";
          $result = $conn->query($sql);

          // Check if query execution was successful
          if ($result === false) {
              echo "<tr><td colspan='3'>Error executing query: " . $conn->error . "</td></tr>";
          } elseif ($result->num_rows > 0) {
              // Output data for each row
              while($row = $result->fetch_assoc()) {
                  echo "<tr><td>" . htmlspecialchars($row["service_type"]) . "</td><td>" . htmlspecialchars($row["service_cost"]) . "</td><td>" . htmlspecialchars($row["service_duration"]) . "</td></tr>";
              }
          } else {
              echo "<tr><td colspan='3'>No services available</td></tr>";
          }
          
          // Close connection
          $conn->close();
        ?>
      </tbody>
    </table>

    <div class="contact-mechanic">
      <input type="text" id="calendar" class="calendar-input" placeholder="Select Date">
      <button id="book-appointment">Book Appointment</button>
    </div>

    <div class="appointment-form" id="appointmentForm">
      <h2>Appointment Details</h2>
      <input type="text" placeholder="Name" id="name">
      <input type="text" placeholder="Contact Number" id="contactNumber">
      <input type="text" placeholder="Car Model" id="carModel">
      <input type="text" placeholder="Car Make" id="carMake">
      <button id="submit-appointment">Submit Appointment</button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      flatpickr("#calendar", {
        dateFormat: "Y-m-d",
      });

      const bookButton = document.getElementById('book-appointment');
      const appointmentForm = document.getElementById('appointmentForm');

      bookButton.addEventListener('click', function() {
        appointmentForm.style.display = 'block';
      });
    });
  </script>
</body>
</html>
