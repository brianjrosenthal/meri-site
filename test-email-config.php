<?php
/**
 * Email Configuration Test Script
 * Navigate to this file in your browser to test email setup
 */

// Load config
if (file_exists('config.dev.php')) {
    require_once('config.dev.php');
    echo "<h2>Configuration File Found</h2>";
} else {
    echo "<h2 style='color:red;'>Configuration File NOT Found</h2>";
    echo "<p>Please copy config.dev.php.example to config.dev.php</p>";
}

echo "<h3>Email Configuration Status:</h3>";
echo "<ul>";

// Check CONTACT_EMAIL
if (defined('CONTACT_EMAIL')) {
    echo "<li>✅ CONTACT_EMAIL: " . CONTACT_EMAIL . "</li>";
} else {
    echo "<li>❌ CONTACT_EMAIL: NOT DEFINED</li>";
}

// Check SMTP_ENABLED
if (defined('SMTP_ENABLED')) {
    echo "<li>SMTP_ENABLED: " . (SMTP_ENABLED ? 'YES' : 'NO') . "</li>";
} else {
    echo "<li>❌ SMTP_ENABLED: NOT DEFINED (will use PHP mail() function)</li>";
}

// Check PHPMailer availability
$phpMailerPath = __DIR__ . '/admin/inc/PHPMailer';
if (file_exists($phpMailerPath . '/PHPMailer.php')) {
    echo "<li>✅ PHPMailer: Available</li>";
} else {
    echo "<li>❌ PHPMailer: NOT Available</li>";
}

// Check SMTP settings if enabled
if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
    echo "<li>SMTP_HOST: " . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT DEFINED') . "</li>";
    echo "<li>SMTP_PORT: " . (defined('SMTP_PORT') ? SMTP_PORT : 'NOT DEFINED') . "</li>";
    echo "<li>SMTP_SECURE: " . (defined('SMTP_SECURE') ? SMTP_SECURE : 'NOT DEFINED') . "</li>";
    echo "<li>SMTP_USERNAME: " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NOT DEFINED') . "</li>";
    echo "<li>SMTP_PASSWORD: " . (defined('SMTP_PASSWORD') ? (empty(SMTP_PASSWORD) ? 'EMPTY' : '***SET***') : 'NOT DEFINED') . "</li>";
    echo "<li>SMTP_FROM_EMAIL: " . (defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'NOT DEFINED') . "</li>";
}

echo "</ul>";

echo "<h3>What will happen:</h3>";
if (defined('SMTP_ENABLED') && SMTP_ENABLED && file_exists($phpMailerPath . '/PHPMailer.php')) {
    echo "<p><strong>✅ Emails will be sent via SMTP (PHPMailer)</strong></p>";
    if (!defined('SMTP_PASSWORD') || empty(SMTP_PASSWORD) || SMTP_PASSWORD === 'your-app-password-here') {
        echo "<p style='color:red;'><strong>⚠️ WARNING: SMTP_PASSWORD appears to be not set or using default value. Emails will fail.</strong></p>";
    }
} else {
    echo "<p><strong>📧 Emails will use PHP mail() function (may not work in local development)</strong></p>";
    echo "<p style='color:orange;'><strong>Note:</strong> PHP's mail() function typically doesn't work on local development machines. You need to configure SMTP for emails to work locally.</p>";
}

echo "<hr>";
echo "<h3>Setup Instructions:</h3>";
echo "<ol>";
echo "<li>Copy config.dev.php.example to config.dev.php</li>";
echo "<li>Edit config.dev.php</li>";
echo "<li>Set your Gmail address in SMTP_USERNAME and SMTP_FROM_EMAIL</li>";
echo "<li>Generate a Gmail App Password at <a href='https://myaccount.google.com/apppasswords' target='_blank'>https://myaccount.google.com/apppasswords</a></li>";
echo "<li>Set the App Password in SMTP_PASSWORD</li>";
echo "<li>Save the file and refresh this page</li>";
echo "</ol>";

?>
