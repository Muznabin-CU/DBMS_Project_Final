<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - AnimeVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Q&A Section */
        .help-section {
            margin-top: 20px;
        }

        .faq-container {
            margin-top: 40px;
        }

        .faq-item {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #80f2e7;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
            cursor: pointer;
            position: relative;
        }

        .faq-item:hover {
            background: rgba(128, 242, 231, 0.8);
        }

        .faq-item h3 {
            color: #80f2e7;
            margin: 0;
            font-size: 18px;
        }

        .faq-item p {
            color: #fff;
            margin: 10px 0 0;
            display: none; /* Hidden by default */
            font-size: 16px;
        }

        .faq-item:hover p {
            display: block; /* Show on hover */
        }

        /* Contact Section */
        .contact-section {
            margin-top: 40px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #80f2e7;
            border-radius: 10px;
            text-align: center;
        }

        .contact-section h3 {
            color: #80f2e7;
            margin-bottom: 10px;
        }

        .contact-section p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">AnimeVerse</div>
        <ul>
        <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="user_dashboard.php">Dashboard</a></li>
            <li><a href="help.php">Help</a></li>
        </ul>
    </div>

    <div class="container">
        <h1>Help & FAQ</h1>

        <div class="help-section">
            <p>If you have any questions about using AnimeVerse, this section provides quick answers. Hover over a question to reveal the answer.</p>

            <div class="faq-container">
                <div class="faq-item">
                    <h3>How do I write a review for an anime?</h3>
                    <p>Navigate to the anime details page and click on "Write a Review." Fill out the form and submit your review.</p>
                </div>

                <div class="faq-item">
                    <h3>Can I edit or delete my reviews?</h3>
                    <p>Yes, you can manage your reviews from your profile under the "My Reviews" section.</p>
                </div>

                <div class="faq-item">
                    <h3>How do I join AnimeVerse?</h3>
                    <p>Click on "Signup" from the homepage, fill in your details, and create your account. Once registered, you can log in and explore.</p>
                </div>

                
                <div class="faq-item">
                    <h3>Is AnimeVerse free to use?</h3>
                    <p>Yes, AnimeVerse is completely free to use for browsing, reviewing, and joining discussions about your favorite anime.</p>
                </div>
            </div>
        </div>

        <div class="contact-section">
            <h3>Contact Us</h3>
            <p><strong>Email:</strong> <a href="mailto:muznabin@gmail.com">muznabin@gmail.com</a></p>
            <p><strong>Phone:</strong> 01780995744</p>
        </div>
    </div>

    
</body>
</html>
