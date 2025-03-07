<?php
/*
 * Developer 5: Anna (Admin Dashboard)
 * - Displays a list of unapproved users for admin approval
 * - Allows admins to approve users and manage assets
 * - Logs admin actions
 * - TODO: Add bulk approval and notification system for user approvals
 */

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Internal CSS -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(240, 160, 0, 0.4), rgba(129, 192, 111, 0.4)), url('https://images.unsplash.com/photo-1567445232-2c10b3be3b4e') center/cover no-repeat;
            background-size: cover;
            min-height: 100vh;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .admin-panel-container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: 600;
        }

        h3 {
            color: #555;
            font-size: 1.3em;
            margin-bottom: 20px;
        }

        /* Button Styles */
        .btn {
            background-color: #FFD700; /* Gold */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            margin: 10px;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #F0E68C; /* Lighter Gold */
            transform: scale(1.05);
        }

        .btn:active {
            background-color: #FFD700;
            transform: scale(0.98);
        }

        .logout-btn {
            background-color: #8BC34A; /* Green */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #7CB342;
            transform: scale(1.05);
        }

        /* Chart container */
        .charts-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
            margin-top: 30px;
        }

        .chart-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 48%;
            margin-top: 20px;
            transition: transform 0.3s;
        }

        .chart-card:hover {
            transform: scale(1.05);
        }

        canvas {
            width: 100%;
            height: 300px;
        }

        @media (max-width: 768px) {
            .charts-container {
                flex-direction: column;
            }

            .chart-card {
                width: 100%;
            }
        }

    </style>
</head>
<body>

    <div class="admin-panel-container">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

        <h3>Access to Other Pages</h3>
        <a href="user-management.php" class="btn">Manage Users</a>
        <a href="asset-management.php" class="btn">Manage Assets</a>
        <a href="map.php" class="btn">Go to Map</a> <!-- Added Go to Map button -->

        <!-- Chart Display Section -->
        <div class="charts-container">
            <!-- User Approval Chart -->
            <div class="chart-card">
                <h4>User Approvals</h4>
                <canvas id="userChart"></canvas>
            </div>

            <!-- Assets Overview Chart -->
            <div class="chart-card">
                <h4>Assets Overview</h4>
                <canvas id="assetChart"></canvas>
            </div>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- JavaScript to initialize charts -->
    <script>
        // Data for the User Approval chart
        var ctxUser = document.getElementById('userChart').getContext('2d');
        var userChart = new Chart(ctxUser, {
            type: 'pie',
            data: {
                labels: ['Approved Users', 'Pending Users'],
                datasets: [{
                    label: 'User Approvals',
                    data: [10, 5], // Sample data: Update with real data
                    backgroundColor: ['#4CAF50', '#FFEB3B'],
                    borderColor: ['#388E3C', '#FBC02D'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' users';
                            }
                        }
                    }
                }
            }
        });

        // Data for the Asset Overview chart
        var ctxAsset = document.getElementById('assetChart').getContext('2d');
        var assetChart = new Chart(ctxAsset, {
            type: 'bar',
            data: {
                labels: ['Assets Added', 'Assets Updated', 'Assets Deleted'],
                datasets: [{
                    label: 'Assets Overview',
                    data: [30, 15, 5], // Sample data: Update with real data
                    backgroundColor: '#8BC34A',
                    borderColor: '#7CB342',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>

</body>
</html>
