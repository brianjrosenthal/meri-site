<?php
/**
 * Contact Form Handler
 * 
 * Processes contact form submissions and sends emails via SMTP
 */

// Load GetSimple configuration
define('IN_GS', TRUE);
require_once('gsconfig.php');

// Load config.dev.php if it exists
$configExists = file_exists('config.dev.php');
if ($configExists) {
    require_once('config.dev.php');
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?id=contact');
    exit;
}

// Get and sanitize form data
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validate required fields
$errors = [];
if (empty($name)) {
    $errors[] = 'Name is required';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}
if (empty($message)) {
    $errors[] = 'Message is required';
}

// If validation fails, redirect back with error
if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_data'] = $_POST;
    header('Location: index.php?id=contact&error=validation');
    exit;
}

// Load custom SMTP mailer
require_once(__DIR__ . '/includes/smtp-mailer.php');

$success = false;
$errorMessage = '';

// Try to send via custom SMTP mailer
if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
    $to = defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'brian.rosenthal@gmail.com';
    $subject = 'New Contact Form Submission from ' . $name;
    $body = "New Contact Form Submission\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Message:\n$message";
    
    $result = sendSMTPEmail($to, $subject, $body, $email);
    
    if ($result['success']) {
        $success = true;
    } else {
        $errorMessage = "Failed to send email: " . $result['error'];
        error_log("Contact form SMTP error: " . $result['error']);
    }
} else {
    // Fallback to PHP mail() function
    $to = defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'brian.rosenthal@gmail.com';
    $subject = 'New Contact Form Submission from ' . $name;
    $body = "New Contact Form Submission\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Message:\n$message";
    $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n" .
              "Reply-To: $email\r\n" .
              "X-Mailer: PHP/" . phpversion();
    
    if (mail($to, $subject, $body, $headers)) {
        $success = true;
    } else {
        $errorMessage = "Failed to send email. SMTP is not configured and PHP mail() failed.";
        error_log("Contact form error: mail() function failed");
    }
}

// Redirect with appropriate message
if ($success) {
    $_SESSION['contact_success'] = true;
    header('Location: index.php?id=contact&success=1');
} else {
    // Make sure we have an error message
    if (empty($errorMessage)) {
        $errorMessage = "Unknown error occurred. Please check server logs.";
    }
    $_SESSION['contact_error'] = $errorMessage;
    $_SESSION['contact_data'] = $_POST;
    
    // Also log to PHP error log for debugging
    error_log("Contact Form Error: " . $errorMessage);
    error_log("SMTP_ENABLED: " . (defined('SMTP_ENABLED') ? (SMTP_ENABLED ? 'true' : 'false') : 'not defined'));
    
    header('Location: index.php?id=contact&error=send');
}
exit;
