<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'db.php';  // Ensure the database is connected
require 'log.php'; // Logging function for email events

// Load environment variables securely
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendVerificationEmail($to, $verificationCode) {
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        // Sender and Recipient
        $mail->setFrom($_ENV['SMTP_USER'], 'Your Company Name');
        $mail->addAddress($to);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body    = "
            <p>Click the link below to verify your email:</p>
            <p><a href='{$_ENV['APP_URL']}/verify-email.php?code=$verificationCode'>Verify Email</a></p>
            <p>If you did not request this, please ignore this email.</p>
        ";

        // Send the email
        if ($mail->send()) {
            logAction('system', "Verification email sent to $to");
        } else {
            logAction('system', "Email failed to $to: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        logAction('system', "PHPMailer Exception: " . $e->getMessage());
    }
}
