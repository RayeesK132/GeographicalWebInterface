<?php
/*
 * Developer 6: Tom (User Dashboard)
 * - Displays user-specific information and assets
 * - Role-based content rendering
 * - Redirects to appropriate dashboards based on user role
 * - TODO: Implement real-time asset updates
 */

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">

    <!-- Internal CSS -->
    <style>
        /* Global Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            text-align: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: #555;
        }

        .btn, .logout-btn {
            background-color: #FFD700; /* Gold */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            width: 200px;
            text-align: center;
            margin: 10px;
        }

        .btn:hover, .logout-btn:hover {
            background-color: #F0E68C; /* Lighter Gold */
            transform: scale(1.05);
        }

        .btn:active, .logout-btn:active {
            background-color: #FFD700;
            transform: scale(0.98);
        }

        .logout-btn {
            background-color: #8BC34A; /* Green */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }

            h2 {
                font-size: 2em;
            }

            p {
                font-size: 1em;
            }

            .btn, .logout-btn {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>

</head>
<body>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>This is your user dashboard. You can navigate to the map to explore locations and add assets.</p>

        <!-- Button to Access Map -->
        <a href="map.php" class="btn">Go to Map</a>

        <!-- Logout -->
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

</body>
</html>

 
 
