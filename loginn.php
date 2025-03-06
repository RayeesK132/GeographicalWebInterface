<?php
// Handle POST request for login or signup
$host = 'localhost'; // Database host
$dbname = 'userDB'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a new PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$error = '';
$isSignup = isset($_POST['signup']); // Check if it's a signup request
$successMessage = '';

// Handle login or signup requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($isSignup) {
        // Password requirements validation
        if (!preg_match('/[A-Za-z]/', $pass) || !preg_match('/[0-9]/', $pass) || strlen($pass) < 8) {
            $error = "Password must contain at least one letter, one number, and be at least 8 characters long.";
        } else {
            // Check if the username already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $user]);
            if ($stmt->rowCount() > 0) {
                $error = "Username already taken.";
            } else {
                // Create a new user
                $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->execute(['username' => $user, 'password' => $hashedPass]);
                $successMessage = "Signup successful!";
            }
        }
    } else {
        // Login logic
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($pass, $userData['password'])) {
            $successMessage = "Login successful!";
            // Redirect to dashboard or home page
            header("Location: /dashboard");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Auth</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.1.2/tailwind.min.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">

<div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-700 text-center">
        <?php echo $isSignup ? 'Admin Signup' : 'Admin Login'; ?>
    </h2>
    
    <?php if ($successMessage): ?>
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="bg-red-500 text-white p-2 rounded mb-4">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-4">
            <label for="username" class="block text-gray-700">Username</label>
            <input type="text" id="username" name="username" class="w-full p-2 border rounded" required>
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
        </div>
        
        <button type="submit" name="<?php echo $isSignup ? 'signup' : 'login'; ?>" class="w-full py-2 bg-blue-500 text-white rounded">
            <?php echo $isSignup ? 'Signup' : 'Login'; ?>
        </button>
    </form>
    
    <p class="text-center mt-4">
        <?php if ($isSignup): ?>
            <span>Already have an account? <a href="?login" class="text-blue-600">Login</a></span>
        <?php else: ?>
            <span>Need an account? <a href="?signup" class="text-blue-600">Signup</a></span>
        <?php endif; ?>
    </p>
</div>

</body>
</html>