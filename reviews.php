<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'myanimeverse1');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search for anime
$search_result = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    // Fetch reviews for the searched anime name
    $reviews = $conn->query("SELECT r.id, r.anime_id, r.user_id, r.rating, r.review, a.name AS anime_name, 
                             a.thumbnail AS anime_thumbnail, u.username AS user_name 
                             FROM reviews r 
                             JOIN anime a ON r.anime_id = a.id 
                             JOIN users u ON r.user_id = u.id 
                             WHERE a.name LIKE '%$search%'");
} else {
    // Fetch all reviews if no search query is provided
    $reviews = $conn->query("SELECT r.id, r.anime_id, r.user_id, r.rating, r.review, a.name AS anime_name, 
                             a.thumbnail AS anime_thumbnail, u.username AS user_name 
                             FROM reviews r 
                             JOIN anime a ON r.anime_id = a.id 
                             JOIN users u ON r.user_id = u.id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - AnimeVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.5) 50%), 
                        url('bgm.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: #fff;
            line-height: 1.6;
            min-height: 100vh; 
            padding-top: 75px; /* Reserved space for fixed navbar */
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styling */
        .navbar {
            width: 100%;
            height: 75px;
            background-color: rgba(0, 0, 0, 0.8);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            z-index: 1000;
            padding-left: 200px;
            padding-right: 200px;
        }

        .navbar .logo {
            color: #80f2e7;
            font-size: 42px;
            font-family: cursive;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fd673a;
            font-weight: bold;
            transition: 0.4s ease-in-out;
        }

        .navbar ul li a:hover {
            color: #ff7200;
        }

        /* Reviews Section */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
        }

        .reviews-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between; /* Adds space between columns */
        }

        .review-box {
            background-color: rgba(128, 242, 231, 0.8); /* Light Blue */
            border-radius: 10px;
            padding: 20px;
            flex: 1 1 calc(33.33% - 20px); /* Three columns */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            color: #000;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .review-box h3 {
            margin: 0 0 10px;
            color: #fd673a; /* Orange */
        }

        .review-box img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .review-box p {
            margin: 10px 0 0;
            font-size: 14px;
            color: #333;
        }

        /* Search Bar */
        .search-container {
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 10px;
            width: 80%;
            margin-right: 10px;
            font-size: 16px;
        }

        .search-container button {
            padding: 10px;
            background-color: #fd673a;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #ff7200;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">AnimeVerse</div>
    <ul>
        <li><a href="user_dashboard.php">Dashboard</a></li>
        <li><a href="anime_list.php">Anime List</a></li>
        <li><a href="reviews.php">Reviews</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    <h1>All Reviews</h1>

    <!-- Search Bar to Filter Reviews by Anime Name -->
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Search reviews by Anime name" value="<?php echo isset($search) ? $search : ''; ?>" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="reviews-wrapper">
        <?php while ($review = $reviews->fetch_assoc()) { ?>
            <div class="review-box">
                <?php if (!empty($review['anime_thumbnail'])) { ?>
                    <img src="<?php echo $review['anime_thumbnail']; ?>" alt="<?php echo $review['anime_name']; ?> Thumbnail">
                <?php } else { ?>
                    <img src="default-thumbnail.jpg" alt="Default Thumbnail">
                <?php } ?>
                <h3><?php echo $review['anime_name']; ?> (Rating: <?php echo $review['rating']; ?>/10)</h3>
                <p><strong>Reviewer:</strong> <?php echo $review['user_name']; ?></p>
                <p><?php echo $review['review']; ?></p>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
