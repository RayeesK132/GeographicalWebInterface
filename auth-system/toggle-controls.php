<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require 'functions.php';  // Include the helper functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the visibility status from the form for each control
    $addVisible = isset($_POST['add_visible']) ? 1 : 0;
    $editVisible = isset($_POST['edit_visible']) ? 1 : 0;
    $deleteVisible = isset($_POST['delete_visible']) ? 1 : 0;
    $filterVisible = isset($_POST['filter_visible']) ? 1 : 0;

    // Update the visibility settings in the database
    // Assuming setControlsVisibility is a function that updates visibility in the DB for a user.
    // You can set this based on user ID or apply it globally for all users
    setControlsVisibility($addVisible, $editVisible, $deleteVisible, $filterVisible);

    // Redirect back to the admin dashboard after saving the changes
    header('Location: admin-dashboard.php');
    exit();
}
?>
