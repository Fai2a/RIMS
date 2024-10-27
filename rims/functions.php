<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'rims');

// Add service
if (isset($_POST['add_service'])) {
    $name = $_POST['service_name'];
    $price = $_POST['service_price'];
    $time = $_POST['service_time'];

    $mysqli->query("INSERT INTO services (name, price, time_duration) VALUES('$name', '$price', '$time')");
    header('Location: index.php');
}

// Display services
function display_services() {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM services");

    while ($row = $result->fetch_assoc()) {
        echo "<div class='service-item'>
                <p><b>Name:</b> {$row['name']}</p>
                <p><b>Price:</b> {$row['price']}</p>
                <p><b>Duration:</b> {$row['time_duration']}</p>
                <a href='functions.php?delete={$row['id']}'>Delete</a>
             </div>";
    }
}

// Delete service
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $mysqli->query("DELETE FROM services WHERE id=$id");
    header('Location: index.php');
}
?>
