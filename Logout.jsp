<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Logout Page</title>
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
      .logout-container {
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        width: 300px;
        text-align: center;
      }
      h2 {
        color: #333;
      }
      p {
        font-size: 16px;
        color: #555;
      }
      button {
        width: 100%;
        padding: 10px;
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
      }
      button:hover {
        background-color: #c0392b;
      }
    </style>
  </head>
  <body>
    <div class="logout-container">
      <h2>Logout</h2>
      <p>Are you sure you want to log out?</p>

      <!-- Logout button as a simple link to index.html -->
      <button onclick="window.location.href='index.html';">Log out</button>
    </div>
  </body>
</html>
