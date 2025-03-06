<?php
session_start();
require_once 'vendor/autoload.php';

use Google\Client;

$client = new Client();
$client->setClientId('30924418284-p221mood7t49ht1j0dpeh1d9ha23sej1.apps.googleusercontent.com'); // Your Client ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['credential'];

    try {
        $payload = $client->verifyIdToken($token);
        if ($payload) {
            $_SESSION['username'] = $payload['name'];
            $_SESSION['logged_in'] = true;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
