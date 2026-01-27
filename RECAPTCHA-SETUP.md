# reCAPTCHA Integration Setup Guide

## What Was Implemented

I've successfully integrated Google reCAPTCHA v2 into your contact form to prevent spam submissions. The implementation includes:

1. **Frontend Integration** - Added "I'm not a robot" checkbox to the contact form
2. **Backend Verification** - Server-side validation to verify reCAPTCHA responses
3. **Configuration** - Added reCAPTCHA key constants to config files

## Files Modified

1. **config.dev.php.example** - Added reCAPTCHA configuration constants
2. **theme/ResponsiveCE/contact-content.php** - Added reCAPTCHA widget and script
3. **contact-handler.php** - Added server-side reCAPTCHA verification

## Setup Instructions

### Step 1: Add Your reCAPTCHA Keys

Open your `config.dev.php` file and add these two lines (replace with your actual keys):

```php
define('RECAPTCHA_SITE_KEY', 'your-site-key-here');
define('RECAPTCHA_SECRET_KEY', 'your-secret-key-here');
```

**Important:** Add these lines BEFORE the closing `?>` tag at the end of the file.

### Step 2: Test the Implementation

1. Visit your contact page: http://yourdomain.com/index.php?id=contact
2. You should see the "I'm not a robot" reCAPTCHA checkbox
3. Try submitting the form WITHOUT checking the box - it should show an error
4. Try submitting the form WITH the checkbox checked - it should work normally

### Step 3: Monitor for Issues

If you encounter any problems:

1. Check your PHP error log for reCAPTCHA verification errors
2. Verify your keys are correct in `config.dev.php`
3. Ensure your domain is registered with Google reCAPTCHA
4. Make sure cURL is enabled on your server (required for verification)

## How It Works

### Frontend (User Experience)
- User fills out the contact form
- User must check the "I'm not a robot" reCAPTCHA box
- Form cannot be submitted without completing reCAPTCHA

### Backend (Security)
- When form is submitted, the reCAPTCHA response is sent to Google's servers
- Google verifies the response is legitimate
- Only verified submissions are processed and emailed
- Bot submissions are rejected with an error message

## Security Features

✅ **Server-Side Verification** - Can't be bypassed by disabling JavaScript
✅ **IP Address Validation** - Google checks the user's IP address
✅ **One-Time Use** - Each reCAPTCHA response can only be used once
✅ **Error Logging** - Failed verifications are logged for monitoring

## Troubleshooting

### "Please complete the reCAPTCHA verification" Error
- The user didn't check the reCAPTCHA box before submitting

### "reCAPTCHA verification failed" Error
- Check that your secret key is correct in config.dev.php
- Verify your domain is registered with Google
- Check server logs for more detailed error messages

### reCAPTCHA Box Not Appearing
- Verify RECAPTCHA_SITE_KEY is defined in config.dev.php
- Check browser console for JavaScript errors
- Ensure the Google reCAPTCHA API script is loading

## Additional Notes

- The implementation gracefully degrades if reCAPTCHA keys are not configured
- If keys are not set, the form works normally (but without spam protection)
- All error messages are user-friendly and help guide form completion
- The reCAPTCHA widget respects your site's styling and layout

## Support

If you need to disable reCAPTCHA temporarily, simply comment out or remove the RECAPTCHA constants from config.dev.php:

```php
// define('RECAPTCHA_SITE_KEY', 'your-site-key-here');
// define('RECAPTCHA_SECRET_KEY', 'your-secret-key-here');
```

The form will continue to work normally without spam protection.
