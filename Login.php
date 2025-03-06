<?php
// login.php
session_start();


$timeout_duration = 1800; // Auto logout after 30 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
  session_unset();
  session_destroy();
  header("Location: login.php?message=SessionExpired");
  exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

// Database connection settings
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
  $user_password = trim($_POST['password']);

  // Validation
  if (empty($user_name)) {
    $_SESSION['login_error'] = 'Username is required';
  } elseif (empty($user_password)) {
    $_SESSION['login_error'] = 'Password is required';
  } elseif (strlen($user_name) < 3) {
    $_SESSION['login_error'] = 'Username must be at least 3 characters';
  } else {
    // Fetch user from database
    try {
      $query = "SELECT * FROM users WHERE username = :username";
      $stmt = $pdo->prepare($query);
      $stmt->execute([':username' => $user_name]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($user_password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        header('Location: Welcome.php');
        exit;
      } else {
        $_SESSION['login_error'] = 'Invalid username or password';
      }
    } catch (PDOException $e) {
      $_SESSION['login_error'] = 'Error: ' . $e->getMessage();
    }
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
  <!-- Add Google Sign-In Script -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>
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
      width: 299px;
      height: 44px;
      margin: 10px 0;
      padding: 10px;
      text-align: center;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      line-height: 24px;
      box-sizing: border-box;
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
      width: 24px;
      margin-right: 10px;
      vertical-align: middle;
    }

    .social-btn:hover {
      opacity: 0.8;
    }

    h4 {
      text-align: center;
      font-size: 20px;
      margin: 20px 0;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter username" required />
      <input type="password" name="password" placeholder="Enter password" required />
      <button type="submit">Login</button>
    </form>

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
    <div class="social-login">
      <!-- Google Sign-In Button -->
      <div id="g_id_onload"
        data-client_id="30924418284-p221mood7t49ht1j0dpeh1d9ha23sej1.apps.googleusercontent.com"
        data-context="signin"
        data-ux_mode="popup"
        data-callback="handleCredentialResponse"
        data-auto_prompt="false">
      </div>
      <div class="g_id_signin"
        data-type="standard"
        data-size="large"
        data-width="299">
      </div>

      <a href="facebook-login.php" class="social-btn facebook-btn">
        <img src="FB-Logo.png" alt="Facebook" />
      </a>

      <a href="linkedin-login.php" class="social-btn linkedin-btn">
        <img src="linkedin-logo.webp" alt="LinkedIn" />
      </a>
    </div>
  </div>

  <!-- JavaScript for Google Sign-In -->
  <script>
    function handleCredentialResponse(response) {
      console.log('Sending token to google-login.php');
      fetch('google-login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'credential=' + response.credential
        })
        .then(response => {
          console.log('Raw response:', response); // Log the full response object
          if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
          }
          return response.text(); // Get raw text first to debug
        })
        .then(text => {
          console.log('Response text:', text); // Log raw text
          try {
            const data = JSON.parse(text); // Parse JSON manually
            console.log('Parsed data:', data);
            if (data.success) {
              console.log('Redirecting to Welcome.php');
              window.location.href = 'Welcome.php';
            } else {
              alert('Google Login Failed: ' + (data.error || 'Unknown error'));
            }
          } catch (e) {
            console.error('JSON parse error:', e);
            alert('Error parsing response: ' + text);
          }
        })
        .catch(error => {
          console.error('Fetch error:', error);
          alert('Fetch Error: ' + error.message);
        });
    }
  </script>
</body>

</html>