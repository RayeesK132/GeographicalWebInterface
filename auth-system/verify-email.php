<?php
require 'db.php';  // Include the database connection

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    // Fetch the user with the matching verification code
    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_code = :verification_code");
    $stmt->execute(['verification_code' => $verificationCode]);

    if ($stmt->rowCount() > 0) {
        // User found, approve and mark the email as verified
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update user status to approved and clear the verification code
        $stmt = $pdo->prepare("UPDATE users SET approved = 1, verification_code = NULL WHERE id = :user_id");
        $stmt->execute(['user_id' => $user['id']]);

        echo "Email verified successfully. You can now log in.";
    } else {
        echo "Invalid verification code.";
    }
} else {
    echo "No verification code provided.";
}
?>
