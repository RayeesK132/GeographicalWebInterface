<?php
// welcome.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome</title>
    <link rel="stylesheet" href="indexstyle.css" />
  </head>
  <body>
    <div class="container">
      <h1>
        Welcome,
        <?php echo htmlspecialchars($_SESSION['username']); ?>!
      </h1>
      <h2>Feel free to explore your favorite places!</h2>
      <h3>Explore and Learn</h3>
      <p>Here, you can <a href="logout.php">Logout</a></p>
    </div>
  </body>
</html>
