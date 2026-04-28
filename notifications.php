<?php 
session_start();
include ("db_connection.php");

if (!isset($_SESSION['userID']) || $_SESSION['userType'] != "user") {
    header("Location: login.php?error=You must login first");
    exit();
}

$userID = (int) $_SESSION['userID'];

$name= mysqli_query($conn, "SELECT name FROM users WHERE id= $userID");
$photo= mysqli_query($conn, "SELECT photo FROM users WHERE id= $userID");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifications - Rakkez+</title>

<link rel="stylesheet" href="styler.css">
 <link rel="stylesheet" href="style.css">
</head>

<body class="notification-body">

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
        <img src="images/<?php echo $photo; ?>">
        <h3><?php echo $name; ?></h3>
    </div>

    <div class="sidebar-links">
        <a href="profile.php">Profile</a>
		<a href="index.php">Main</a>
		<a href="tips.php">Tips</a>
        <a href="notifications.php">Notifications</a>
        <a href="support.php">Support</a>
    </div>

    <div class="sidebar-footer">
        <button class="logout-btn" onclick="location.href='home.html'">Log Out</button>
    </div>

</div>

<!-- OVERLAY -->
<div id="overlay" class="overlay" onclick="toggleMenu()"></div>

<!-- CONTENT -->
<div class="notification-container">
    <h1 class="notification-title">Notifications</h1>
    <div id="notification-list"></div>
</div>

<!-- Footer -->
<div class="footer">
    <p>©️ Rakkez+.. Helping students build better study habits</p>
</div>

<script src="jsr.js"></script>

</body>

</html>
