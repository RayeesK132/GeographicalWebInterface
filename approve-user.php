<?php
// Start session and check if the user is logged in and an admin
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect if not admin
    exit();
}

require 'db.php';  // Include the database connection

// Check if a user ID is passed in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];  // Get user ID from the URL

    try {
        // Update the user approval status to 1 (approved)
        $stmt = $pdo->prepare("UPDATE users SET approved = 1 WHERE id = :id");
        $stmt->execute(['id' => $userId]);  // Execute the update query

        // Redirect back to the user management page after approval
        header('Location: user-management.php');
        exit();
    } catch (PDOException $e) {
        // If there's an error with the database, handle it here (you can log the error or display a message)
        echo "Error: " . $e->getMessage();
    }
} else {
    // If no user ID is provided, redirect to the user management page
    header('Location: user-management.php');
    exit();
}
?>
