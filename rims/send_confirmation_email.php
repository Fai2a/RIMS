<?php
// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Validate email and appointment details
if (isset($data['email'], $data['appointmentDetails'])) {
    $to = $data['email'];
    $subject = "Appointment Confirmation";
    $appointment = $data['appointmentDetails'];
    $message = "
    Dear User,
    
    Your appointment for {$appointment['service']} has been confirmed.
    Date: {$appointment['date']}
    Time: {$appointment['time']}

    Thank you for choosing our service!
    
    Best regards,
    Your Mechanic Team
    ";
    
    $headers = "From: no-reply@yourwebsite.com";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing email or appointment details']);
}
?>
