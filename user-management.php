<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require 'db.php';  // Include the database connection
require 'functions.php';  // Include the logging function

// Pagination setup
$limit = 10; // Number of users per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch users for the current page
$stmt = $pdo->prepare("SELECT * FROM users WHERE role != 'admin' LIMIT :start, :limit");
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Get the total number of users
$totalUsersStmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'");
$totalUsers = $totalUsersStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
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
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
            font-weight: 600;
        }

        h3 {
            color: #555;
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        /* Grid Layout for User Cards */
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .user-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            font-size: 0.9em; /* Reduced text size */
        }

        .user-card:hover {
            transform: scale(1.05);
        }

        /* Form Elements */
        label {
            font-weight: 600;
            margin: 5px 0;
            display: block;
            font-size: 0.9em; /* Smaller text */
        }

        input[type="checkbox"], select {
            margin: 5px 0;
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 0.9em;
        }

        /* Button Styles */
        .btn {
            background-color: #FFD700; /* Gold */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 14px; /* Smaller button text */
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
            width: 100%;
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
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
            width: 100%;
        }

        .logout-btn:hover {
            background-color: #7CB342;
            transform: scale(1.05);
        }

        /* Pagination Styles */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            background-color: #FFD700;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #F0E68C;
        }

        .pagination a.active {
            background-color: #8BC34A;
        }
    </style>
</head>
<body>

    <div class="admin-panel-container">
        <h2>User Management</h2>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

        <h3>Manage Users</h3>

        <div class="user-grid">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <form action="update-user-access.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                        <p><strong>User:</strong> <?php echo $user['username']; ?></p>

                        <label for="role_<?php echo $user['id']; ?>">Role:</label>
                        <select name="role" id="role_<?php echo $user['id']; ?>">
                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>

                        <label for="add_visible_<?php echo $user['id']; ?>">Add Feature:</label>
                        <input type="checkbox" name="add_visible" id="add_visible_<?php echo $user['id']; ?>" <?php echo isset($user['add_visible']) && $user['add_visible'] == 1 ? 'checked' : ''; ?>>

                        <label for="edit_visible_<?php echo $user['id']; ?>">Edit Feature:</label>
                        <input type="checkbox" name="edit_visible" id="edit_visible_<?php echo $user['id']; ?>" <?php echo isset($user['edit_visible']) && $user['edit_visible'] == 1 ? 'checked' : ''; ?>>

                        <label for="delete_visible_<?php echo $user['id']; ?>">Delete Feature:</label>
                        <input type="checkbox" name="delete_visible" id="delete_visible_<?php echo $user['id']; ?>" <?php echo isset($user['delete_visible']) && $user['delete_visible'] == 1 ? 'checked' : ''; ?>>

                        <label for="filter_visible_<?php echo $user['id']; ?>">Filter Feature:</label>
                        <input type="checkbox" name="filter_visible" id="filter_visible_<?php echo $user['id']; ?>" <?php echo isset($user['filter_visible']) && $user['filter_visible'] == 1 ? 'checked' : ''; ?>>

                        <button type="submit" class="btn">Save Changes</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>

        <a href="admin-dashboard.php" class="btn">Back to Admin Dashboard</a>
    </div>

</body>
</html>
