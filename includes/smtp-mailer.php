<?php
/**
 * Simple SMTP Mailer
 * 
 * A lightweight SMTP email sender using PHP sockets
 * No external dependencies required
 */

class SMTPMailer {
    private $smtp_host;
    private $smtp_port;
    private $smtp_secure;
    private $smtp_username;
    private $smtp_password;
    private $from_email;
    private $from_name;
    private $socket;
    private $lastError = '';
    
    public function __construct($config) {
        $this->smtp_host = $config['host'];
        $this->smtp_port = $config['port'];
        $this->smtp_secure = $config['secure']; // 'tls' or 'ssl'
        $this->smtp_username = $config['username'];
        $this->smtp_password = $config['password'];
        $this->from_email = $config['from_email'];
        $this->from_name = $config['from_name'];
    }
    
    public function send($to, $subject, $body, $replyTo = null, $bcc = null) {
        try {
            // Connect to SMTP server
            if (!$this->connect()) {
                return false;
            }
            
            // Send EHLO
            if (!$this->command("EHLO " . $_SERVER['SERVER_NAME'], 250)) {
                $this->close();
                return false;
            }
            
            // Start TLS if needed
            if ($this->smtp_secure === 'tls') {
                if (!$this->command("STARTTLS", 220)) {
                    $this->close();
                    return false;
                }
                
                // Upgrade to TLS
                if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    $this->lastError = "Failed to enable TLS encryption";
                    $this->close();
                    return false;
                }
                
                // Send EHLO again after TLS
                if (!$this->command("EHLO " . $_SERVER['SERVER_NAME'], 250)) {
                    $this->close();
                    return false;
                }
            }
            
            // Authenticate
            if (!$this->command("AUTH LOGIN", 334)) {
                $this->close();
                return false;
            }
            
            if (!$this->command(base64_encode($this->smtp_username), 334)) {
                $this->close();
                return false;
            }
            
            if (!$this->command(base64_encode($this->smtp_password), 235)) {
                $this->close();
                return false;
            }
            
            // Send email
            if (!$this->command("MAIL FROM: <{$this->from_email}>", 250)) {
                $this->close();
                return false;
            }
            
            if (!$this->command("RCPT TO: <{$to}>", 250)) {
                $this->close();
                return false;
            }
            
            // Add BCC recipient if provided
            if ($bcc) {
                if (!$this->command("RCPT TO: <{$bcc}>", 250)) {
                    $this->close();
                    return false;
                }
            }
            
            if (!$this->command("DATA", 354)) {
                $this->close();
                return false;
            }
            
            // Build email headers and body
            $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
            $headers .= "To: <{$to}>\r\n";
            if ($replyTo) {
                $headers .= "Reply-To: <{$replyTo}>\r\n";
            }
            $headers .= "Subject: {$subject}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n";
            $headers .= "\r\n";
            
            $emailData = $headers . $body . "\r\n.";
            
            if (!$this->command($emailData, 250)) {
                $this->close();
                return false;
            }
            
            // Quit
            $this->command("QUIT", 221);
            $this->close();
            
            return true;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            $this->close();
            return false;
        }
    }
    
    private function connect() {
        $context = stream_context_create();
        
        if ($this->smtp_secure === 'ssl') {
            $remote = "ssl://{$this->smtp_host}:{$this->smtp_port}";
        } else {
            $remote = "tcp://{$this->smtp_host}:{$this->smtp_port}";
        }
        
        $this->socket = @stream_socket_client(
            $remote,
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );
        
        if (!$this->socket) {
            $this->lastError = "Failed to connect to SMTP server: {$errstr} ({$errno})";
            return false;
        }
        
        // Read welcome message
        $response = fgets($this->socket, 512);
        if (substr($response, 0, 3) != '220') {
            $this->lastError = "Invalid SMTP server response: {$response}";
            return false;
        }
        
        return true;
    }
    
    private function command($cmd, $expectedCode) {
        fwrite($this->socket, $cmd . "\r\n");
        
        // Read all lines of multi-line response
        // Multi-line responses have a dash after the code (e.g., "250-")
        // The last line has a space instead (e.g., "250 ")
        $response = '';
        do {
            $line = fgets($this->socket, 512);
            if ($line === false) {
                $this->lastError = "Failed to read from SMTP server";
                return false;
            }
            $response .= $line;
            $code = substr($line, 0, 3);
            $separator = substr($line, 3, 1);
        } while ($separator === '-'); // Keep reading while we see dashes
        
        if ($code != $expectedCode) {
            $this->lastError = "SMTP Error: Expected {$expectedCode}, got {$code}. Full response: {$response}";
            return false;
        }
        
        return true;
    }
    
    private function close() {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
    }
    
    public function getLastError() {
        return $this->lastError;
    }
}

/**
 * Helper function to send email using SMTP
 */
function sendSMTPEmail($to, $subject, $body, $replyTo = null, $bcc = null) {
    if (!defined('SMTP_ENABLED') || !SMTP_ENABLED) {
        return ['success' => false, 'error' => 'SMTP is not enabled'];
    }
    
    // If BCC not provided, check for CONTACT_BCC constant
    if ($bcc === null && defined('CONTACT_BCC') && !empty(CONTACT_BCC)) {
        $bcc = CONTACT_BCC;
    }
    
    $config = [
        'host' => SMTP_HOST,
        'port' => SMTP_PORT,
        'secure' => SMTP_SECURE,
        'username' => SMTP_USERNAME,
        'password' => SMTP_PASSWORD,
        'from_email' => SMTP_FROM_EMAIL,
        'from_name' => SMTP_FROM_NAME
    ];
    
    $mailer = new SMTPMailer($config);
    $success = $mailer->send($to, $subject, $body, $replyTo, $bcc);
    
    return [
        'success' => $success,
        'error' => $success ? null : $mailer->getLastError()
    ];
}
