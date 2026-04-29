<?php 
session_start();
include ("db_connection.php");

if (!isset($_SESSION['userID']) || $_SESSION['isAdmin']== 1) {
    header("Location: login.php?error=You must login first");
    exit();
}

$userID = (int) $_SESSION['userID'];

$query = mysqli_query($conn, "SELECT name, photo FROM users WHERE id= $userID");
$user = mysqli_fetch_assoc($query);

$name = $user['name'];
$photo = $user['photo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Support - Rakkez+</title>

<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="style.css">
</head>

<body class="body">

<!-- Navbar --> 
<div class="navbar"> 
<div class="logo"> 
<a href="index.php"> 
<img src="images/logo.jpeg" width="100"> </a> </div> 
<div class="nav-links"> 
<a href="index.php">Main</a> 
<a href="home.html">Log Out</a> </div> </div>

<button class="menu-btn" onclick="toggleMenu()">☰</button>

<!-- SIDEBAR -->
<div id="sidebar" class="sidebar">

    <div class="sidebar-header">
        <img src="images/<?php echo htmlspecialchars($photo); ?>" alt="">
        <h3><?php echo htmlspecialchars($name); ?></h3>
    </div>

    <div class="sidebar-links">
        <a href="profile.php">Profile</a>
		<a href="index.php">Main</a>
		<a href="tips.php">Tips</a>
        <a href="notifications.php">Notifications</a>
        <a href="support.php">Support</a>
    </div>

    <div class="sidebar-footer">
        <button class="logout-btn"><a href="home.html">Log Out</a></button>
    </div>

</div>


<!-- OVERLAY -->
<div id="overlay" class="overlay" onclick="toggleMenu()"></div>

<!-- CONTENT -->
<div class="support-container">

    <h1 class="support-title">Support</h1>

    <div class="support-card">
        <h3>Contact Us</h3>
        <p>If you need help or have any questions, feel free to contact us.</p>
		<br>

<div class="support-contact-item">
    <span class="support-icon">✉️</span>
    <p>support@rakkezplus.com</p>
</div>

<div class="support-contact-item">
    <span class="support-icon">📱</span>
    <p>+966 500000000</p>
</div>
    </div>

    <div class="support-card">
        <h3>FAQs</h3>

        <p><strong>How can I improve my focus?</strong></p>
        <p>Try using shorter study sessions and avoid distractions like your phone.</p>

        <p><strong>Can I track my study sessions?</strong></p>
        <p>Yes, the system allows you to monitor your sessions and interruptions.</p>

        <p><strong>Who can I contact for issues?</strong></p>
        <p>You can contact our support team using the email/phone above.</p>
    </div>

</div>

<!-- Footer -->
<div class="footer">
    <p>©️ Rakkez+.. Helping students build better study habits</p>
</div>

<script src="jsr.js"></script>

</body>
</html>
