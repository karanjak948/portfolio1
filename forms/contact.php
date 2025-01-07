<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response content type to JSON and enable CORS
header('Access-Control-Allow-Origin: *');  // Allow all domains or specify domains e.g., "http://example.com"
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');  // Allow specific methods
header('Access-Control-Allow-Headers: Content-Type, Authorization');  // Allow specific headers
header('Content-Type: application/json');

// Handle OPTIONS request (for preflight check)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // If this is the OPTIONS request, exit without doing anything else
}

// Handle POST request for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data and sanitize inputs
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $subject = htmlspecialchars(strip_tags($_POST['subject']));
    $message = htmlspecialchars(strip_tags($_POST['message']));

    // Validation checks
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit();
    }

    // Email details
    $to = "kevohanasa17@gmail.com";  // Replace with your email
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $emailBody = "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Subject: $subject\n\n";
    $emailBody .= "Message:\n$message\n";

    // Send email
    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Your message was successfully sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There was a problem sending your message.']);
    }
} else {
    // Handle case where request method is not POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method!']);
}
exit;
?>
