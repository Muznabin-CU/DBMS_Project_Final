<?php
session_start();

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not an admin
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'myanimeverse1');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reviews
$reviews_result = $conn->query("SELECT reviews.id, reviews.rating, reviews.review, users.username, anime.name AS anime_name 
                                FROM reviews 
                                JOIN users ON reviews.user_id = users.id 
                                JOIN anime ON reviews.anime_id = anime.id");

// Handle removing reviews
if (isset($_GET['delete_review_id'])) {
    $review_id = $_GET['delete_review_id'];
    $conn->query("DELETE FROM reviews WHERE id=$review_id");
    header('Location: reviews.php'); // Refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews - AnimeVerse</title>
    <link rel="stylesheet" href="style.css">
     <style>
        
        .reviews-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .review-box {
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            border-radius: 10px;
            padding: 20px;
            flex: 1 1 calc(50% - 20px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            max-width: 500px;
        }

        .review-box strong {
            color: #fd673a;
            font-size: 18px;
        }

        .review-box em {
            color: #80f2e7;
            font-style: normal;
        }

        .review-box p {
            margin-top: 10px;
            font-size: 16px;
        }

        .review-box a {
            color: red;
            text-decoration: none;
            border: 1px solid red;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: rgba(255, 0, 0, 0.2);
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .review-box a:hover {
            background-color: #ff7200;
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
    <h1>Manage Reviews</h1>
    <div class="reviews-container">
        <?php while ($row = $reviews_result->fetch_assoc()) { ?>
            <div class="review-box">
                <strong><?php echo htmlspecialchars($row['anime_name']); ?></strong>
                by <em><?php echo htmlspecialchars($row['username']); ?></em>
                <p>Rating: <?php echo htmlspecialchars($row['rating']); ?></p>
                <p><?php echo htmlspecialchars($row['review']); ?></p>
                <a href="?delete_review_id=<?php echo $row['id']; ?>">Delete Review</a>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
