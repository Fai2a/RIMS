<?php
// Display appointments
function display_appointments() {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM appointments");

    while ($row = $result->fetch_assoc()) {
        echo "<div class='appointment-item'>
                <p><b>Customer:</b> {$row['customer_name']}</p>
                <p><b>Service:</b> {$row['service_name']}</p>
                <p><b>Date:</b> {$row['appointment_date']}</p>
                <p><b>Status:</b> {$row['status']}</p>
                <a href='appointments.php?cancel={$row['id']}'>Cancel</a>
             </div>";
    }
}

// Cancel appointment
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $mysqli->query("UPDATE appointments SET status='cancelled' WHERE id=$id");
    header('Location: index.php');
}
?>
