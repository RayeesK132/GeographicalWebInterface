<?php
// Include database connection
require 'db.php';  // Ensure db.php has the correct PDO connection to your database

// Define admin account details
$username = 'admin'; // Username for the admin
$password = 'adminpassword'; // Plain text password (it will be hashed)
$email = 'admin@example.com'; // Admin email
$role = 'admin'; // Set the role as admin
$approved = 1;  // Admin is automatically approved

// Hash the password using bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Check if the admin account exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);

if ($stmt->rowCount() > 0) {
    // Admin account exists, update it
    $stmt = $pdo->prepare("UPDATE users SET password = :password, email = :email, role = :role, approved = :approved WHERE username = :username");
    $stmt->execute([
        'password' => $hashedPassword,
        'email' => $email,
        'role' => $role,
        'approved' => $approved,
        'username' => $username
    ]);
    echo "Admin account updated successfully!";
} else {
    // Admin account does not exist, insert it
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, approved) VALUES (:username, :password, :email, :role, :approved)");
    $stmt->execute([
        'username' => $username,
        'password' => $hashedPassword,
        'email' => $email,
        'role' => $role,
        'approved' => $approved
    ]);
    echo "Admin account created successfully!";
}
?>
