<?php
/**
 * Contact Form Debug Page
 */

session_start();

echo "<h2>Contact Form Debug Information</h2>";

// Check config file
echo "<h3>Configuration File:</h3>";
if (file_exists('config.dev.php')) {
    require_once('config.dev.php');
    echo "✅ config.dev.php EXISTS<br>";
} else {
    echo "❌ config.dev.php DOES NOT EXIST<br>";
}

// Check constants
echo "<h3>Email Constants:</h3>";
echo "CONTACT_EMAIL: " . (defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'NOT DEFINED') . "<br>";
echo "SMTP_ENABLED: " . (defined('SMTP_ENABLED') ? (SMTP_ENABLED ? 'TRUE' : 'FALSE') : 'NOT DEFINED') . "<br>";

if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
    echo "SMTP_HOST: " . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT DEFINED') . "<br>";
    echo "SMTP_PORT: " . (defined('SMTP_PORT') ? SMTP_PORT : 'NOT DEFINED') . "<br>";
    echo "SMTP_SECURE: " . (defined('SMTP_SECURE') ? SMTP_SECURE : 'NOT DEFINED') . "<br>";
    echo "SMTP_USERNAME: " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NOT DEFINED') . "<br>";
    echo "SMTP_PASSWORD: " . (defined('SMTP_PASSWORD') ? (strlen(SMTP_PASSWORD) > 0 ? 'SET (' . strlen(SMTP_PASSWORD) . ' chars)' : 'EMPTY') : 'NOT DEFINED') . "<br>";
    echo "SMTP_FROM_EMAIL: " . (defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'NOT DEFINED') . "<br>";
    echo "SMTP_FROM_NAME: " . (defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'NOT DEFINED') . "<br>";
}

// Check session variables
echo "<h3>Session Variables:</h3>";
if (isset($_SESSION['contact_error'])) {
    echo "contact_error: " . htmlspecialchars($_SESSION['contact_error']) . "<br>";
} else {
    echo "contact_error: NOT SET<br>";
}

if (isset($_SESSION['contact_errors'])) {
    echo "contact_errors: <pre>" . print_r($_SESSION['contact_errors'], true) . "</pre>";
} else {
    echo "contact_errors: NOT SET<br>";
}

// Test SMTP connection
echo "<h3>Test SMTP Connection:</h3>";
if (defined('SMTP_ENABLED') && SMTP_ENABLED && defined('SMTP_HOST') && defined('SMTP_PORT')) {
    require_once('includes/smtp-mailer.php');
    
    echo "Attempting to send test email...<br>";
    $result = sendSMTPEmail(
        CONTACT_EMAIL,
        'Test Email from Contact Form Debug',
        'This is a test email to verify SMTP configuration is working.',
        CONTACT_EMAIL
    );
    
    if ($result['success']) {
        echo "✅ <strong>SUCCESS!</strong> Email sent successfully!<br>";
    } else {
        echo "❌ <strong>FAILED:</strong> " . htmlspecialchars($result['error']) . "<br>";
    }
} else {
    echo "⚠️ SMTP not fully configured. Cannot test.<br>";
}

// Check PHP error log location
echo "<h3>PHP Error Log:</h3>";
echo "error_log setting: " . ini_get('error_log') . "<br>";
echo "Check your PHP error log for detailed error messages.<br>";
?>
