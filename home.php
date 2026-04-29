<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rakkez+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body class="home-body">

    <!-- Navbar -->
    <div class="home-navbar">
        <div class="home-logo">
            <a href="home.php">  
                <img src="images/logo.jpeg" width="100"> 
            </a>
        </div>

        <div class="home-nav-links">

            <a href="home.php">Home</a>

            <?php if (isset($_SESSION['userID'])) { ?>

                <!-- If logged in -->
                <a href="logout.php">Logout</a>

            <?php } else { ?>

                <!-- If NOT logged in -->
                <a href="login.php">Log In</a>
                <a href="signup.php">Sign Up</a>

            <?php } ?>

        </div>
    </div>

    <!-- Hero Section -->
    <div class="home-hero">
        <h1>Stop Getting Distracted. Start Getting Results</h1>
        <p>
            Rakkez+ helps you stay focused, understand what distracts you,
            and turn your study time into real results.
        </p>

        <?php if (!isset($_SESSION['userID'])) { ?>
            <!-- Show buttons only if NOT logged in -->
            <a href="signup.php" class="home-btn home-btn-primary">Sign Up</a>
            <a href="login.php" class="home-btn home-btn-secondary">Log In</a>
        <?php } ?>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>©️ Rakkez+.. Helping students build better study habits</p>
    </div>

</body>
</html>