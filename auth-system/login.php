<?php

require 'db.php';  // Include the database connection
require 'functions.php';  // Include the logging function

$error = '';
$successMessage = '';

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for user credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Start session and store user data
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] == 'admin') {
            header('Location: admin-dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <!-- External CSS Libraries -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Internal CSS -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .error, .success {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: none;
        }

        .success {
            background-color: #4CAF50;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            font-size: 14px;
            color: #333;
        }

        .footer-text {
            margin-top: 20px;
            color: #777;
        }

        .footer-text a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error and success messages dynamically -->
        <div class="error" id="error-message"><?php echo $error; ?></div>
        <div class="success" id="success-message"><?php echo $successMessage; ?></div>

        <!-- Login form -->
        <form method="POST" id="login-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p class="footer-text">Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <!-- JavaScript for form validation and dynamic message display -->
    <script>
        // Display error or success message
        const errorMessage = '<?php echo $error; ?>';
        const successMessage = '<?php echo $successMessage; ?>';

        if (errorMessage) {
            document.getElementById('error-message').style.display = 'block';
        }

        if (successMessage) {
            document.getElementById('success-message').style.display = 'block';
        }

        // Add basic form validation (optional)
        document.getElementById('login-form').addEventListener('submit', function(event) {
            const username = document.querySelector('input[name="username"]').value;
            const password = document.querySelector('input[name="password"]').value;

            if (!username || !password) {
                event.preventDefault();
                alert('Please fill in both fields');
            }
        });
    </script>

</body>
</html>
