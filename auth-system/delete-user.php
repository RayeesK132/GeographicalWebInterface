<?php
session_start();
require 'db.php';  // Database connection
require 'functions.php';  // Include logging function

// Ensure user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if user ID is set and valid
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = $_GET['id'];

    // Prevent admin from deleting their own account
    if ($_SESSION['user_id'] == $userId) {
        header('Location: user-management.php?error=You cannot delete your own account');
        exit();
    }

    // Check if the user exists before deleting
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();

    if ($user) {
        // Delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        // Log the deletion action
        logAction($_SESSION['username'], "Deleted user: " . $user['username']);

        // Redirect with success message
        header('Location: user-management.php?success=User deleted successfully');
        exit();
    } else {
        // Redirect if user not found
        header('Location: user-management.php?error=User not found');
        exit();
    }
} else {
    // Redirect if no user ID provided
    header('Location: user-management.php?error=Invalid request');
    exit();
}
?>
