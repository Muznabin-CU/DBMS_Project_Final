<?php
session_start();

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'myanimeverse1');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle removing a user
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $conn->query("DELETE FROM users WHERE id=$user_id");
}

// Fetch all users except admins
$users_result = $conn->query("SELECT * FROM users WHERE role='user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users - AnimeVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styling for user boxes */
        .users-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .user-box {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            flex: 1 1 calc(33.33% - 20px); /* Three boxes side by side */
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .user-box strong {
            font-size: 18px;
            color: #333;
        }

        .user-box p {
            margin: 10px 0;
            font-size: 16px;
            color: #666;
        }

        .user-box a {
            color: red;
            text-decoration: none;
            border: 1px solid red;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgba(255, 0, 0, 0.2);
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .user-box a:hover {
            background-color: red;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">AnimeVerse</div>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="admin_reviews.php">Reviews</a></li>
        <li><a href="admin_users.php">Users</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    <h1>Admin Users</h1>
    <h2>Manage Registered Users</h2>
    <div class="users-container">
        <?php while ($row = $users_result->fetch_assoc()) { ?>
            <div class="user-box">
                <strong><?php echo htmlspecialchars($row['username']); ?></strong>
                <p><?php echo htmlspecialchars($row['email']); ?></p>
                <a href="?delete_user_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
