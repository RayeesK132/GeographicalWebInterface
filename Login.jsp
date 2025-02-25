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
    </style>
  </head>
  <body>
    <div class="login-container">
      <h2>Login</h2>
      <form action="loginServlet" method="POST">
        <input
          type="text"
          name="username"
          placeholder="Enter username"
          required
        />
        <input
          type="password"
          name="password"
          placeholder="Enter password"
          required
        />
        <button type="submit">Login</button>
      </form>
      <!-- Display error message if login fails -->
      <div class="footer-links">
        <a href="index.html">Back to Home</a>
        <a href="Register.jsp" class="register-btn">Register</a>
      </div>
    </div>
  </body>
</html>
