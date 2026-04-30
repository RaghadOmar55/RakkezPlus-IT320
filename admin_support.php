<?php
session_start();
require_once "db_connection.php";

if (!isset($_SESSION['userID']) || $_SESSION['isAdmin'] == 0) {
    header("Location: login.php?error=You must login first");
    exit();
}

$userID = (int) $_SESSION['userID'];

$stmt = $conn->prepare("SELECT name, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$adminResult = $stmt->get_result();
$adminUser = $adminResult->fetch_assoc();

$name = $adminUser["name"] ?? "Admin";
$adminPhoto = !empty($adminUser["photo"]) ? basename($adminUser["photo"]) : "default.png";
$adminPhotoPath = "images/" . $adminPhoto;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Admin - Rakkez+</title>
  <link rel="stylesheet" href="styler.css">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar"> 
  <div class="logo"> 
    <a href="admin.php"> 
      <img src="images/logo.jpeg" width="100" alt="Rakkez+ Logo">
    </a>
  </div> 

  <div class="nav-links"> 
    <a href="admin.php">Main</a> 
    <a href="logout.php">Log Out</a>
  </div>
</div>

<button class="menu-btn" onclick="toggleAdminMenu()">☰</button>

<div id="admin-sidebar" class="admin-sidebar">
  <div class="admin-sidebar-header">
    <img src="<?php echo htmlspecialchars($adminPhotoPath); ?>" alt="Admin">
    <h3><?php echo htmlspecialchars($name); ?></h3>
  </div>

  <div class="admin-sidebar-links">
    <a href="admin_profile.php">Profile</a>
    <a href="admin.php">Main</a>
    <a href="admin_support.php">Support</a>
  </div>

  <div class="admin-sidebar-footer">
    <button class="admin-logout-btn" onclick="goAdminHome()">Log Out</button>
  </div>
</div>

<div id="admin-overlay" class="admin-overlay" onclick="toggleAdminMenu()"></div>

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

<div class="footer">
  <p>©️ Rakkez+.. Helping students build better study habits</p>
</div>

<script>
function toggleAdminMenu() {
  document.getElementById("admin-sidebar").classList.toggle("active");
  document.getElementById("admin-overlay").classList.toggle("active");
}

function goAdminHome() {
  window.location.href = "logout.php";
}
</script>

</body>
</html>

