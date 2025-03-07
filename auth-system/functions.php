<?php
// Include the database connection
require 'db.php';

/**
 * Generic logging function
 * @param PDO $pdo Database connection
 * @param string $username User performing the action
 * @param string $action Description of the action
 */
function logAction($pdo, $username, $action) {
    $stmt = $pdo->prepare("INSERT INTO logs (username, action, timestamp) VALUES (:username, :action, NOW())");
    $stmt->execute([
        'username' => $username,
        'action' => $action
    ]);
}

/**
 * Logs a user login event
 */
function logLogin($pdo, $username) {
    logAction($pdo, $username, "User logged in");
}

/**
 * Logs a user logout event
 */
function logLogout($pdo, $username) {
    logAction($pdo, $username, "User logged out");
}

/**
 * Logs an asset addition
 */
function logAssetAdded($pdo, $username, $assetName) {
    logAction($pdo, $username, "Asset '$assetName' added");
}

/**
 * Logs an asset deletion
 */
function logAssetDeleted($pdo, $username, $assetName) {
    logAction($pdo, $username, "Asset '$assetName' deleted");
}

/**
 * Logs user registration
 */
function logUserRegistration($pdo, $username) {
    logAction($pdo, $username, "User registered");
}

/**
 * Logs when an asset is uploaded in bulk
 */
function logAssetBulkUpload($pdo, $username, $numAssets) {
    logAction($pdo, $username, "$numAssets assets uploaded via bulk upload");
}

/**
 * Logs when an asset is updated
 */
function logAssetUpdated($pdo, $username, $assetName) {
    logAction($pdo, $username, "Asset '$assetName' updated");
}

/**
 * Logs when an asset is viewed
 */
function logAssetViewed($pdo, $username, $assetName) {
    logAction($pdo, $username, "Asset '$assetName' viewed");
}

/**
 * Logs when an admin approves a user
 */
function logUserApproval($pdo, $adminUsername, $approvedUser) {
    logAction($pdo, $adminUsername, "Approved user: $approvedUser");
}

/**
 * Fetches control visibility for a given user
 */
function getControlsVisibility($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT add_visible, edit_visible, delete_visible, filter_visible FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Updates control visibility for a given user
 */
function setControlsVisibility($pdo, $user_id, $add_visible, $edit_visible, $delete_visible, $filter_visible) {
    $stmt = $pdo->prepare("UPDATE users SET add_visible = :add_visible, edit_visible = :edit_visible, delete_visible = :delete_visible, filter_visible = :filter_visible WHERE id = :user_id");
    $stmt->execute([
        'add_visible' => $add_visible,
        'edit_visible' => $edit_visible,
        'delete_visible' => $delete_visible,
        'filter_visible' => $filter_visible,
        'user_id' => $user_id
    ]);
}
?>
