<?php
// login.php
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
  $user_password = $_POST['password'];

  // Fetch user from database
  try {
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $user_name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($user_password, $user['password'])) {
      // Password matches
      $_SESSION['username'] = $user['username'];
      $_SESSION['logged_in'] = true;
      header('Location: Welcome.php'); // Redirect to your dashboard or homepage
      exit;
    } else {
      // Invalid username or password
      $_SESSION['login_error'] = 'Invalid username or password';
    }
  } catch (PDOException $e) {
    $_SESSION['login_error'] = 'Error: ' . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
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

    .login-container {
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
      font-size: 12px;
      text-align: center;
    }

    .footer-links {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }

    .register-btn {
      margin-left: auto;
      text-decoration: none;
      padding: 5px 10px;
      background-color: #4caf50;
      color: white;
      border-radius: 4px;
    }

    .register-btn:hover {
      background-color: #45a049;
    }

    .social-login {
      margin-top: 20px;
      text-align: center;
    }

    .social-btn {
      display: inline-block;
      width: 93%;
      margin: 10px 0;
      padding: 10px;
      text-align: center;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
    }

    .google-btn {
      background-color: rgb(242, 212, 148);
      color: white;
    }

    .facebook-btn {
      background-color: rgb(242, 212, 148);
      color: white;
    }

    .linkedin-btn {
      background-color: rgb(242, 212, 148);
      color: white;
    }

    .social-btn img {
      width: 10px;
      margin-right: 10px;
      vertical-align: middle;
    }

    .social-btn:hover {
      opacity: 0.8;
    }

    h4 {
      text-align: center;
      /* this centers the text horizontally */
      font-size: 20px;
      /* to adjust font size as needed */
      margin: 20px 0;
      /* This is used to adds space around the "OR" text */
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST">
      <input
        type="text"
        name="username"
        placeholder="Enter username"
        required />
      <input
        type="password"
        name="password"
        placeholder="Enter password"
        required />
      <button type="submit">Login</button>
    </form>

    <!-- Display error message if login fails -->
    <?php if (isset($_SESSION['login_error'])): ?>
      <div class="error-message">
        <?php echo $_SESSION['login_error']; ?>
      </div>
      <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <div class="footer-links">
      <a href="index.php">Back to Home</a>
      <a href="register.php" class="register-btn">Register</a>
    </div>

    <h4>OR</h4>
    <!-- Social Login Buttons -->
    <div class="social-login">
      <a href="google-login.php" class="social-btn google-btn">
        <img src="Google-Logo.webp" alt="Google" style="width: 50px" />
      </a>

      <a href="facebook-login.php" class="social-btn facebook-btn">
        <img src="FB-Logo.png" alt="Facebook" style="width: 35px" />
      </a>

      <a href="linkedin-login.php" class="social-btn linkedin-btn">
        <img src="linkedin-logo.webp" alt="LinkedIn" style="width: 50px" />
      </a>
    </div>
  </div>
</body>

</html>