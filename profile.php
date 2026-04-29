<?php
session_start();
require_once "db_connection.php";

$message = "";

if (!isset($_SESSION['userID']) || $_SESSION['isAdmin']== 1) {
    header("Location: login.php?error=You must login first");
    exit();
}

$user_id = (int) $_SESSION['userID'];

$stmt = $conn->prepare("SELECT id, name, email, password, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_name"])) {
    $newName = trim($_POST["name"]);

    if (strlen($newName) < 4) {
        $message = "Name must be at least 4 characters.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $user_id);
        $stmt->execute();

        $message = "Name updated successfully.";
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_photo"])) {
    $newPhoto = $_POST["photo"];

    $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
    $stmt->bind_param("si", $newPhoto, $user_id);
    $stmt->execute();

    $message = "Profile picture updated successfully.";
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_password"])) {
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if (strlen($newPassword) < 8) {
        $message = "Password must be at least 8 characters.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
    } 
    elseif (password_verify($newPassword, $user["password"])) {
        $message = "New password must be different from the old password.";
    } 
    else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $user_id);
        $stmt->execute();

        $message = "Password updated successfully.";
    }
}


$stmt = $conn->prepare("SELECT id, name, email, password, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user["name"] ?? "Test User";
$email = $user["email"] ?? "test@test.com";
$password = $user["password"] ?? "12345678";
$photo = !empty($user["photo"]) ? $user["photo"] : "images/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Rakkez+</title>
  <link rel="stylesheet" href="profile.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="navbar"> 
  <div class="logo"> 
    <a href="index.php"> 
      <img src="images/logo.jpeg" width="100" alt="Rakkez+ Logo">
    </a>
  </div> 

  <div class="nav-links"> 
    <a href="index.php">Main</a> 
    <a href="logout.php">Log Out</a>
  </div>
</div>

<button class="menu-btn" onclick="toggleProfileMenu()">☰</button>

<div id="profile-sidebar" class="profile-sidebar">
  <div class="profile-sidebar-header">
    <img id="sidebar-profile-img" src="<?php echo htmlspecialchars($photo); ?>" alt="">
    <h3 id="sidebar-profile-name"><?php echo htmlspecialchars($name); ?></h3>
  </div>

  <div class="profile-sidebar-links">
    <a href="profile.php">Profile</a>
    <a href="index.php">Main</a>
    <a href="Tips.php">Tips</a>
    <a href="notifications.php">Notifications</a>
    <a href="support.php">Support</a>
  </div>

  <div class="profile-sidebar-footer">
    <button class="profile-logout-btn" onclick="goHome()">Log Out</button>
  </div>
</div>

<div id="profile-overlay" class="profile-overlay" onclick="toggleProfileMenu()"></div>

<main class="profile-page">
  <div class="profile-card">
    <h1>My Profile</h1>
    <p class="profile-subtitle">View and edit your account details.</p>

    <div class="profile-layout">

      <div class="profile-left">
        <div class="profile-image-wrap">
          <img id="main-profile-img" class="profile-main-img" src="images/<?php echo htmlspecialchars($photo); ?>" alt="Profile Picture">

          <button class="profile-icon-btn profile-image-edit" onclick="toggleImageOptions()" type="button">
            <img src="images/edit icon.png" alt="Edit">
          </button>
        </div>

        <div id="profile-image-options" class="profile-image-options">

          <form method="POST" class="profile-option">
            <input type="hidden" name="photo" value="images/medicine.png">
            <button type="submit" name="update_photo" class="profile-photo-btn">
              <img src="images/medicine.png" alt="Medicine">
              <span>Medicine</span>
            </button>
          </form>

          <form method="POST" class="profile-option">
            <input type="hidden" name="photo" value="images/CS.png">
            <button type="submit" name="update_photo" class="profile-photo-btn">
              <img src="images/CS.png" alt="CS">
              <span>CS</span>
            </button>
          </form>

          <form method="POST" class="profile-option">
            <input type="hidden" name="photo" value="images/eng.png">
            <button type="submit" name="update_photo" class="profile-photo-btn">
              <img src="images/eng.png" alt="Engineering">
              <span>Engineering</span>
            </button>
          </form>

          <form method="POST" class="profile-option">
            <input type="hidden" name="photo" value="images/business.png">
            <button type="submit" name="update_photo" class="profile-photo-btn">
              <img src="images/business.png" alt="Business">
              <span>Business</span>
            </button>
          </form>

          <form method="POST" class="profile-option">
            <input type="hidden" name="photo" value="images/default.png">
            <button type="submit" name="update_photo" class="profile-photo-btn">
              <img src="images/default.png" alt="Default">
              <span>Default</span>
            </button>
          </form>

        </div>
      </div>

      <div class="profile-right">

        <form method="POST">
          <label>Full Name</label>
          <div class="profile-inline">
            <input id="profile-name" name="name" type="text" value="<?php echo htmlspecialchars($name); ?>" readonly>

            <button class="profile-icon-btn" onclick="enableNameEdit()" type="button">
              <img src="images/edit icon.png" alt="Edit">
            </button>

            <button id="save-name-btn" name="update_name" class="profile-save-btn hidden" type="submit">
              Save
            </button>
          </div>
        </form>

        <label>Email</label>
        <input type="email" value="<?php echo htmlspecialchars($email); ?>" readonly>

        <div class="profile-password-header">
          <label>Current Password</label>
          <button class="profile-icon-btn" onclick="showPasswordEdit()" type="button">
            <img src="images/edit icon.png" alt="Edit">
          </button>
        </div>

        <input id="current-password" type="password" value="<?php echo htmlspecialchars($password); ?>" readonly>

        <form method="POST">
          <div id="password-edit-box" class="password-edit-box">
            <label>New Password</label>
            <input id="new-password" name="new_password" type="password" placeholder="Enter new password">

            <label>Confirm New Password</label>
            <input id="confirm-password" name="confirm_password" type="password" placeholder="Confirm new password">

            <div class="profile-actions">
              <button class="profile-save-btn" name="update_password" type="submit">Confirm</button>
              <button class="profile-cancel-btn" onclick="cancelPasswordEdit()" type="button">Cancel</button>
            </div>
          </div>
        </form>

      </div>
    </div>

    <p id="profile-message" class="profile-message">
      <?php echo htmlspecialchars($message); ?>
    </p>
  </div>
</main>

<div class="footer">
  <p>©️ Rakkez+.. Helping students build better study habits</p>
</div>

<script>
function toggleProfileMenu() {
  document.getElementById("profile-sidebar").classList.toggle("active");
  document.getElementById("profile-overlay").classList.toggle("active");
}

function goHome() {
  window.location.href = "logout.php";
}

function toggleImageOptions() {
  document.getElementById("profile-image-options").classList.toggle("active");
}

function enableNameEdit() {
  const nameInput = document.getElementById("profile-name");
  const saveBtn = document.getElementById("save-name-btn");

  nameInput.removeAttribute("readonly");
  nameInput.focus();
  saveBtn.classList.remove("hidden");
}

function showPasswordEdit() {
  document.getElementById("password-edit-box").classList.add("active");
  document.getElementById("profile-message").textContent = "";
}

function cancelPasswordEdit() {
  document.getElementById("password-edit-box").classList.remove("active");
  document.getElementById("new-password").value = "";
  document.getElementById("confirm-password").value = "";
  document.getElementById("profile-message").textContent = "Password change canceled.";
}
</script>

</body>
</html>