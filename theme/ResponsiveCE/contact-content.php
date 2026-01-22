<h2>Contact Meredith Madon</h2>

<?php
// Display success message
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<div class="alert alert-success">Thank you for your message! Meredith will respond within a few business days.</div>';
    if (isset($_SESSION['contact_success'])) {
        unset($_SESSION['contact_success']);
    }
}

// Display error messages
if (isset($_GET['error'])) {
    echo '<div class="alert alert-error">';
    if (isset($_SESSION['contact_error']) && !empty($_SESSION['contact_error'])) {
        echo '<strong>Error:</strong> ' . htmlspecialchars($_SESSION['contact_error']);
        unset($_SESSION['contact_error']);
    } elseif (isset($_SESSION['contact_errors'])) {
        echo '<strong>Please fix the following errors:</strong>';
        echo '<ul>';
        foreach ($_SESSION['contact_errors'] as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['contact_errors']);
    } else {
        echo '<strong>Error:</strong> There was an error sending your message. Please check your configuration or try again later.';
    }
    echo '</div>';
}

// Get saved form data if available
$savedName = isset($_SESSION['contact_data']['name']) ? htmlspecialchars($_SESSION['contact_data']['name']) : '';
$savedEmail = isset($_SESSION['contact_data']['email']) ? htmlspecialchars($_SESSION['contact_data']['email']) : '';
$savedMessage = isset($_SESSION['contact_data']['message']) ? htmlspecialchars($_SESSION['contact_data']['message']) : '';
if (isset($_SESSION['contact_data'])) {
    unset($_SESSION['contact_data']);
}
?>

<form class="contact-form" method="post" action="contact-handler.php">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $savedName; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $savedEmail; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" required><?php echo $savedMessage; ?></textarea>
    </div>
    
    <button type="submit" class="submit-btn">Send Message</button>
    
    <p class="form-note">Meredith Madon will respond to your message within a few business days.</p>
</form>
