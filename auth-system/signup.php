<?php
session_start();
require 'db.php';  // Include the database connection
require 'functions.php';  // Include the logging function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$successMessage = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];  // Capture the phone number from the form

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if the email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            // Check if the email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $error = "Email is already taken.";
            } else {
                // Generate verification code
                $verificationCode = bin2hex(random_bytes(16));

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user into the database (default role = user, approved = 0)
                $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone, verification_code, role, approved, created_at) 
                                        VALUES (:username, :password, :email, :phone, :verification_code, 'user', 0, NOW())");
                $stmt->execute([
                    'username' => $username,
                    'password' => $hashedPassword,
                    'email' => $email,
                    'phone' => $phone,  // Save phone number in the database
                    'verification_code' => $verificationCode
                ]);

                // Send verification email
                sendVerificationEmail($email, $verificationCode);

                // Log the registration action
                logUserRegistration($username);

                // Set success message
                $successMessage = "Registration successful! A verification email has been sent.";

                // Redirect to prevent resubmission on refresh
                header("Location: signup.php");
                exit();
            }
        }
    }
}

// Function to send verification email
function sendVerificationEmail($to, $verificationCode) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_email_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Verify your email address';
        $mail->Body = "Click the link to verify your email: <a href='verify-email.php?code=$verificationCode'>Verify Email</a>";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
