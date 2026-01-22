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

// Check if PHPMailer is available
$phpMailerPath = __DIR__ . '/admin/inc/PHPMailer';
$usePHPMailer = file_exists($phpMailerPath . '/PHPMailer.php');

// Load PHPMailer classes if available
if ($usePHPMailer) {
    require_once($phpMailerPath . '/PHPMailer.php');
    require_once($phpMailerPath . '/SMTP.php');
    require_once($phpMailerPath . '/Exception.php');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = false;
$errorMessage = '';

if (defined('SMTP_ENABLED') && SMTP_ENABLED && $usePHPMailer) {
    // Use PHPMailer with SMTP
    
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress(CONTACT_EMAIL);
        $mail->addReplyTo($email, $name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission from ' . $name;
        $mail->Body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
        ";
        $mail->AltBody = "New Contact Form Submission\n\n" .
                        "Name: $name\n" .
                        "Email: $email\n" .
                        "Message:\n$message";
        
        $mail->send();
        $success = true;
    } catch (Exception $e) {
        $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        error_log("Contact form error: " . $mail->ErrorInfo);
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
        $errorMessage = "Failed to send email. Please try again later.";
        error_log("Contact form error: mail() function failed");
    }
}

// Redirect with appropriate message
if ($success) {
    $_SESSION['contact_success'] = true;
    header('Location: index.php?id=contact&success=1');
} else {
    $_SESSION['contact_error'] = $errorMessage;
    $_SESSION['contact_data'] = $_POST;
    header('Location: index.php?id=contact&error=send');
}
exit;
