<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Index.css">
    <title><<a href="index.php">AnimeVerse</a></title>
</head>
<body>
    <div class="navbar">
        <div class="logo">AnimeVerse</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="help.php">Help</a></li>
        </ul>
        
    </div>

    <div class="container">
        <div id="side"></div>
        <div>
        <h1>Welcome to AnimeVerse!</h1>
        <p>Your one-stop platform for anime discovery, reviews, and community discussions.</p>
        <button class="join-btn"><a href="signup.php" style="color: inherit; text-decoration: none;">Join Us</a></button>
        </div>
        <div id="side"></div>
    </div>

    
</body>
</html>
