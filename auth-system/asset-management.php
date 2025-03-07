<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect if not admin
    exit();
}

require 'db.php';  // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assets</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .admin-panel-container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Button Styles */
        .btn {
            background-color: #FFD700; /* Gold */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
            width: 100%;
            text-align: center;
            margin: 10px 0;
        }

        .btn:hover {
            background-color: #F0E68C; /* Lighter Gold */
            transform: scale(1.05);
        }

        .btn:active {
            background-color: #FFD700;
            transform: scale(0.98);
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 0.9em;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #FFD700; /* Gold */
            color: white;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        /* Responsive Table Styles */
        @media (max-width: 768px) {
            table {
                font-size: 0.8em;
            }

            .btn {
                width: auto;
                margin: 10px 5px;
            }
        }
    </style>
</head>
<body>

    <div class="admin-panel-container">
        <h2>Manage Assets</h2>

        <!-- Back to Admin Dashboard -->
        <a href="admin-dashboard.php" class="btn">Back to Admin Dashboard</a>

        <?php
        // Fetch all assets
        $stmt = $pdo->prepare("SELECT * FROM assets");
        $stmt->execute();
        $assets = $stmt->fetchAll();

        if (count($assets) > 0):
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assets as $asset): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($asset['name']); ?></td>
                            <td><?php echo $asset['latitude']; ?></td>
                            <td><?php echo $asset['longitude']; ?></td>
                            <td>
                                <a href="delete-asset.php?id=<?php echo $asset['id']; ?>" class="btn">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No assets to manage.</p>
        <?php endif; ?>
    </div>

</body>
</html>
