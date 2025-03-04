<?php
$host = 'localhost';
$dbname = 'login_register';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and validate input values
    $user_name = trim($_POST['username']);
    $user_email = trim($_POST['email']);
    $user_password = trim($_POST['password']);

    // Validation
    if (empty($user_name)) {
        $message = 'Username is required';
    } elseif (empty($user_email)) {
        $message = 'Email is required';
    } elseif (empty($user_password)) {
        $message = 'Password is required';
    } elseif (strlen($user_name) < 3) {
        $message = 'Username must be at least 3 characters';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email format';
    } elseif (strlen($user_password) < 6) {
        $message = 'Password must be at least 6 characters';
    } else {
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $user_name, ':email' => $user_email]);

        if ($stmt->rowCount() > 0) {
            $message = 'Username or email already exists.';
        } else {
            $insert_query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $pdo->prepare($insert_query);

            if ($stmt->execute([':username' => $user_name, ':email' => $user_email, ':password' => $hashed_password])) {
                $message = 'Registration successful!';
            } else {
                $message = 'An error occurred. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <a href="index.html" class="home-btn">Home</a>
        </div>
    </div>
</body>

</html>