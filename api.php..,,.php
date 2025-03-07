<?php
// Include the database connection
require 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the action from the POST request
    $action = $_POST['action'];

    if ($action === 'getAssets') {
        // Prepare the SQL query to fetch assets from the database
        $stmt = $pdo->prepare("SELECT * FROM assets");
        $stmt->execute();
        // Fetch all assets as an associative array
        $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the assets data as a JSON response
        echo json_encode($assets);
    }
}
?>
