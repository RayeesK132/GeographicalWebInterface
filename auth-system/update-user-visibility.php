<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include the necessary files
require 'db.php';  // Database connection
require 'functions.php';  // Log action functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the incoming data
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $add_visible = isset($_POST['add_visible']) ? 1 : 0;
    $edit_visible = isset($_POST['edit_visible']) ? 1 : 0;
    $delete_visible = isset($_POST['delete_visible']) ? 1 : 0;
    $filter_visible = isset($_POST['filter_visible']) ? 1 : 0;

    if ($user_id && $role) {
        try {
            // Update the user's role and visibility settings in the database
            $stmt = $pdo->prepare("UPDATE users SET role = :role, add_visible = :add_visible, edit_visible = :edit_visible, delete_visible = :delete_visible, filter_visible = :filter_visible WHERE id = :user_id");
            $stmt->execute([
                'role' => $role,
                'add_visible' => $add_visible,
                'edit_visible' => $edit_visible,
                'delete_visible' => $delete_visible,
                'filter_visible' => $filter_visible,
                'user_id' => $user_id
            ]);

            // Log the action
            logAction($_SESSION['username'], "Updated user access for user ID $user_id");

            // Redirect back to the user management page with success message
            $_SESSION['success'] = "User access updated successfully.";
            header('Location: user-management.php');
            exit();

        } catch (PDOException $e) {
            // Handle any errors during the database operation
            $_SESSION['error'] = "Error updating user access: " . $e->getMessage();
            header('Location: user-management.php');
            exit();
        }
    } else {
        // Redirect back with an error if validation failed
        $_SESSION['error'] = "Invalid input.";
        header('Location: user-management.php');
        exit();
    }
}
?>

