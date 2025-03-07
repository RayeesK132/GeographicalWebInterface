 <?php
 $host = 'localhost';  // Database host
 $dbname = 'userDB';   // Database name
 $username = 'root';   // Database username
 $password = '';       // Database password (empty by default for XAMPP)
 

 try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log error to a file
    error_log("Database connection failed: " . $e->getMessage(), 3, "error_log.txt");

    // Display a user-friendly message
    die("Connection failed. Please try again later.");
}

?>
