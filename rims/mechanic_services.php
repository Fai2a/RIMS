<?php
// Database connection
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

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    // Insert data into the database
    $sql = "INSERT INTO services (service_name, price, duration) VALUES ('$service_name', '$price', '$duration')";

    if ($conn->query($sql) === TRUE) {
        $message = "New service added successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .service-form label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        .service-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: green;
            font-size: 18px;
        }

        .error-message {
            text-align: center;
            margin-top: 20px;
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Mechanic Dashboard</h1>
        <!-- Form for adding services -->
        <form action="" method="post" class="service-form">
            <label for="service_name">Service Name:</label>
            <input type="text" id="service_name" name="service_name" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>

            <label for="duration">Time Duration (in hours):</label>
            <input type="number" id="duration" name="duration" required>

            <button type="submit" class="submit-btn">Add Service</button>
        </form>

        <!-- Display success or error message -->
        <?php if (isset($message)) { ?>
            <div class="message"><?= $message; ?></div>
        <?php } ?>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
