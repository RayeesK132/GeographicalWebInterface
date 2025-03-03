<?php
// register.php
session_start();

// Database connection settings
$host = 'localhost';
$dbname = 'login_register';
$username = 'root';
$password = ''; // Your database password

// Create PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values
    $user_name = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    // Hash the password before saving to the database
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // Insert the user into the database
    try {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $user_name, ':email' => $user_email, ':password' => $hashed_password]);

        $message = 'Registration successful! You can now log in.';
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="indexstyle.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 92.5%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
        }

        .success-message {
            color: green;
            font-size: 14px;
            text-align: center;
        }

        .home-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4caf50;
            color: white;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }

        .home-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>

        <?php if (!empty($message)): ?>
            <p class="<?php echo (strpos($message, 'successful') !== false) ? 'success-message' : 'error-message'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>

            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>

            <button type="submit">Register</button>
        </form>
        <div class="footer-links">
            <a href="Login.php">Have an account? Login</a>
            <a href="index.php" class="home-btn">Home</a>
        </div>
    </div>
</body>

</html>