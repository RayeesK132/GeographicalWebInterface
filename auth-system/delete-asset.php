<?php
session_start();
require 'db.php';  // Database connection
require 'functions.php';  // Logging function

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if asset_id is set and not empty
if (isset($_POST['asset_id']) && !empty($_POST['asset_id'])) {
    $asset_id = $_POST['asset_id'];

    // Check if the asset exists before deleting
    $stmt = $pdo->prepare("SELECT name FROM assets WHERE id = :id");
    $stmt->execute(['id' => $asset_id]);
    $asset = $stmt->fetch();

    if ($asset) {
        // Delete the asset from the database
        $stmt = $pdo->prepare("DELETE FROM assets WHERE id = :id");
        $stmt->execute(['id' => $asset_id]);

        // Log the deletion action
        logAction($_SESSION['username'], "Deleted asset: " . $asset['name']);

        // Redirect with success message
        header('Location: asset-management.php?success=Asset deleted successfully');
        exit();
    } else {
        // Redirect with error message if asset doesn't exist
        header('Location: asset-management.php?error=Asset not found');
        exit();
    }
} else {
    // Redirect if no asset ID is provided
    header('Location: asset-management.php?error=Invalid request');
    exit();
}
?>
