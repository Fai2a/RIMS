<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIMS Submission Form</title>
    <link rel="stylesheet" href="style1.css">
    <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js'></script>
    <script type='text/javascript'>
        (function(){
            emailjs.init('XQ7bqUC7Akz8KaRf8y-66');
        })();
    </script>
</head>
<body>
<!-- php -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars(trim($_POST['address']));
    $package = htmlspecialchars(trim($_POST['package']));
    $car_make = htmlspecialchars(trim($_POST['car_make']));
    $car_model = htmlspecialchars(trim($_POST['car_model']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } else {
        // Process data (e.g., save to a database)
        $message = "Your appointment successfully booked";
    }

    require_once "submit.php";
     // Output JavaScript to show the message
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var message = '$message';
            var messageBar = document.createElement('div');
            messageBar.textContent = message;
            messageBar.style.position = 'fixed';
            messageBar.style.top = '0';
            messageBar.style.backgroundColor = '  #2d5b8b';
            messageBar.style.width = '50%';
            messageBar.style.height = '50px';
            messageBar.style.color = '#fff';
            messageBar.style.padding = '10px';
            messageBar.style.textAlign = 'center';
            messageBar.style.zIndex = '1000';
            document.body.appendChild(messageBar);
            setTimeout(function() {
                messageBar.style.display = 'none';
            }, 5000); // Hide after 5 seconds
        });
    </script>";
}
?>
    <div class="form-container">
        <h2>RIMS Submission Form</h2>
        <form action="submit_form.php" method="post" id="appointmentform">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
                <span id="name-error" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"  class="form-control" required>
                <span id="email-error" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" class="form-control" required>
                <span id="address-error" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="package">Package:</label>
                <select id="package" name="package" class="form-control" required>
                    <option value="Hybrid Premium Package">Hybrid Premium Package</option>
                    <option value="Super Package">Super Package</option>
                    <option value="Turbo Turning Package">Turbo Tuning Package</option>
                </select>
            </div>
            <div class="form-group">
                <label for="car-make">Car Make:</label>
                <input type="text" id="car-make" name="car_make" class="form-control" required>
                <span id="car-make-error" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="car-model">Car Model:</label>
                <input type="text" id="car-model" name="car_model" class="form-control" required>
                <span id="car-model-error" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="car-model">Select Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('appointmentform').addEventListener('submit', function(event) {
            let valid = true;

            // Validation for Name (Only text, no numbers)
            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('name-error');
            if (!/^[a-zA-Z\s]+$/.test(nameInput.value)) {
                nameError.textContent = "Please enter only text for the name.";
                valid = false;
            } else {
                nameError.textContent = "";
            }

            // Validation for Address (Only text, no numbers)
            // const addressInput = document.getElementById('address');
            // const addressError = document.getElementById('address-error');
            // if (!/^[a-zA-Z\s]+$/.test(addressInput.value)) {
            //     addressError.textContent = "Please enter only text for the address.";
            //     valid = false;
            // } else {
            //     addressError.textContent = "";
            // }

            // Validation for Car Make (Only text, no numbers)
            // const carMakeInput = document.getElementById('car-make');
            // const carMakeError = document.getElementById('car-make-error');
            // if (!/^[a-zA-Z\s]+$/.test(carMakeInput.value)) {
            //     carMakeError.textContent = "Please enter only text for the car make.";
            //     valid = false;
            // } else {
            //     carMakeError.textContent = "";
            // }

            // Validation for Car Model (Only text, no numbers)
            // const carModelInput = document.getElementById('car-model');
            // const carModelError = document.getElementById('car-model-error');
            // if (!/^[a-zA-Z\s]+$/.test(carModelInput.value)) {
            //     carModelError.textContent = "Please enter only text for the car model.";
            //     valid = false;
            // } else {
            //     carModelError.textContent = "";
            // }

            if (!valid) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Disable past dates in the date input field
        window.onload = function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);
        };
    </script>

</body>
</html>
