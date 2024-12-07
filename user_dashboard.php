<?php
session_start();

// Check if the user is logged in as 'user'
if ($_SESSION['role'] !== 'user') {
    header('Location: login.php'); // Redirect to login page if not a user
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'myanimeverse1');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user ID is set and valid
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    echo "<p>Error: User ID is not set or invalid.</p>";
    exit();
}

// Fetch all animes for the dropdown
$anime_list = $conn->query("SELECT id, name FROM anime");

// Handle search
$search_result = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $search_result = $conn->query("SELECT * FROM anime WHERE name LIKE '%$search%'");
}

// Handle selecting an anime from the dropdown
if (isset($_GET['anime_id']) && is_numeric($_GET['anime_id'])) {
    $selected_anime_id = $_GET['anime_id'];
    $search_result = $conn->query("SELECT * FROM anime WHERE id = $selected_anime_id");
}

// Handle adding a review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $anime_id = $_POST['anime_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("INSERT INTO reviews (anime_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $anime_id, $user_id, $rating, $review);
    if ($stmt->execute()) {
        echo "<p>Review added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Handle editing a review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_review'])) {
    $review_id = $_POST['review_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("UPDATE reviews SET rating = ?, review = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("isii", $rating, $review, $review_id, $user_id);
    if ($stmt->execute()) {
        echo "<p>Review updated successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch user's reviews
$user_reviews = $conn->query("SELECT reviews.id, reviews.rating, reviews.review, anime.name AS anime_name 
                              FROM reviews 
                              JOIN anime ON reviews.anime_id = anime.id 
                              WHERE reviews.user_id = $user_id");

// Handle deleting reviews
if (isset($_GET['delete_review_id'])) {
    $review_id = $_GET['delete_review_id'];
    $conn->query("DELETE FROM reviews WHERE id = $review_id");
    header("Location: user_dashboard.php"); // Refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - AnimeVerse</title>
    <link rel="stylesheet" href="style.css">
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
        <h1>User Dashboard</h1>

        <!-- Select Anime Dropdown -->
        <form method="GET" style="margin-bottom: 20px;">
            <label for="anime-select">Select an Anime:</label>
            <select name="anime_id" id="anime-select">
                <option value="" selected disabled>Select an anime</option>
                <?php while ($anime = $anime_list->fetch_assoc()) { ?>
                    <option value="<?php echo $anime['id']; ?>">
                        <?php echo $anime['name']; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">View</button>
        </form>

        <!-- Search Anime -->
        <form method="GET">
            <label for="search">Or search by name:</label>
            <input type="text" name="search" id="search" placeholder="Search Anime by Name" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($search_result && $search_result->num_rows > 0) { ?>
            <h2>Search Results</h2>
            <ul>
                <?php while ($anime = $search_result->fetch_assoc()) { ?>
                    <li>
                        <img src="<?php echo $anime['thumbnail']; ?>" alt="Anime Thumbnail" width="50" height="50">
                        <strong><?php echo $anime['name']; ?></strong> (<?php echo $anime['genre']; ?>)
                        <p><?php echo $anime['description']; ?></p>

                        <!-- Review Form -->
                        <form method="POST">
                            <input type="hidden" name="anime_id" value="<?php echo $anime['id']; ?>">
                            <input type="number" name="rating" min="1" max="10" placeholder="Rating (1-10)" required>
                            <textarea name="review" placeholder="Write a review" required></textarea>
                            <button type="submit" name="add_review">Submit Review</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        <?php } elseif (isset($search) || isset($selected_anime_id)) { ?>
            <p>No anime found with the specified criteria.</p>
        <?php } ?>

        <!-- User Reviews -->
        <h2>Your Reviews</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Anime Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($review = $user_reviews->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['anime_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['rating']); ?></td>
                        <td><?php echo htmlspecialchars($review['review']); ?></td>
                        <td>
                            <!-- Edit Review Form -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                <input type="number" name="rating" min="1" max="10" value="<?php echo $review['rating']; ?>" required>
                                <textarea name="review" required><?php echo $review['review']; ?></textarea>
                                <button type="submit" name="edit_review">Update</button>
                            </form>
                            <a href="?delete_review_id=<?php echo $review['id']; ?>" style="color: red;">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
