<?php
session_start(); // Start the session
// Error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST["signup"])) {
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"]; // Address input
    $role = $_POST["role"];

    $errors = array();

    // Validation for empty fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($address) || empty($role)) {
        array_push($errors, "Sab fields bharna zaroori hai");
    }
    
    // Validate first name and last name contain only letters
    if (!preg_match("/^[a-zA-Z]+$/", $firstName)) {
        array_push($errors, "First name sirf letters par mushtamil hona chahiye");
    }
    if (!preg_match("/^[a-zA-Z]+$/", $lastName)) {
        array_push($errors, "Last name sirf letters par mushtamil hona chahiye");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email format ghalat hai");
    }

    // Validate password length and strength
    if (strlen($password) < 8) {
        array_push($errors, "Password kam se kam 8 characters ka hona chahiye");
    }
    if (!preg_match("/[A-Z]/", $password)) {
        array_push($errors, "Password mein kam se kam ek uppercase letter hona chahiye");
    }
    if (!preg_match("/[a-z]/", $password)) {
        array_push($errors, "Password mein kam se kam ek lowercase letter hona chahiye");
    }
    if (!preg_match("/[0-9]/", $password)) {
        array_push($errors, "Password mein kam se kam ek number hona chahiye");
    }
    if (!preg_match("/[\W_]/", $password)) {
        array_push($errors, "Password mein kam se kam ek special character hona chahiye (jaise @, #, $, etc.)");
    }

    require_once "database.php";
    // Check if email already exists
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Email pehle se mojood hai!");
    }

    // If there are validation errors, display them
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Geocode the address to get latitude and longitude
        $address = urlencode($address); // Encode the address for URL
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyBrQaeDGOzlx5Hqc0Yqq7z6krp_5dbsSH0";

        $geocodeResponse = file_get_contents($geocodeUrl);
        $geocodeData = json_decode($geocodeResponse);

        if ($geocodeData->status === 'OK') {
            $latitude = $geocodeData->results[0]->geometry->location->lat;
            $longitude = $geocodeData->results[0]->geometry->location->lng;
        } else {
            $latitude = null; // Default value if geocoding fails
            $longitude = null;
        }

        // Hash the password and insert the user into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (firstname, lastname, email, password, address, role, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $firstName, $lastName, $email, $hashedPassword, $address, $role, $latitude, $longitude);
            mysqli_stmt_execute($stmt);

            // Get the last inserted ID (user's ID)
            $user_id = mysqli_insert_id($conn);

            // Store user ID and role in the session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // Role-based redirection
            if ($role === 'User') {
                header("Location: login.php"); // Redirect to user panel
            } 
            elseif ($role === 'Mechanic') { 
                header("Location: login.php"); // Redirect to mechanic login panel
            } 
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: SQL statement prepare nahi hui. " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="signup.css">
    
    <!-- Google Places API Script with async and defer -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrQaeDGOzlx5Hqc0Yqq7z6krp_5dbsSH0&libraries=places" 
    ></script>
    
    <script>
        function initAutocomplete() {
            var input = document.getElementById('address');  // Address field ka ID use kiya gaya
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.setTypes(['geocode']); // Yeh sirf geocode (address) ke suggestions dega

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                console.log(place); // Yeh console mein selected place ka data show karega (debugging ke liye)
            });
        }

        // Google Autocomplete ko page load hone par initialize karna
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>
</head>
<body>
    <div class="container">
        <form action="signup.php" method="post" id="signupForm">
            <h2>Sign up</h2>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <!-- Address field jahan Google Places API se location suggestions ayen gi -->
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" required placeholder="Enter your location">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control" required>
                    <option value=""></option>
                    <option value="User">User</option>
                    <option value="Mechanic">Mechanic</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="signup" class="btn btn-primary">Sign up</button>
            </div>
        </form>
        <div class="login">
            <p>Have an Account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
