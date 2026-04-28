<?php
session_start();
require_once "db_connection.php";


if (!isset($_SESSION['user_id'])) {
    die("Access denied");
}

$current_user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT isAdmin FROM users WHERE id = ?");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$adminCheckResult = $stmt->get_result();
$currentUser = $adminCheckResult->fetch_assoc();

if (!$currentUser || $currentUser["isAdmin"] != 1) {
    die("Access denied");
}

$message = "";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) {
    $delete_id = intval($_POST["delete_id"]);


    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND isAdmin = 0");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    $message = "User deleted successfully.";
}


$result = $conn->query("SELECT id, name, email, photo FROM users WHERE isAdmin = 0 ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Rakkez+</title>
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
    <a href="home.html">Log Out</a>
  </div>
</div>

<button class="menu-btn" onclick="toggleAdminMenu()">☰</button>

<div id="admin-sidebar" class="admin-sidebar">
  <div class="admin-sidebar-header">
    <img src="images/default.png" alt="Admin">
    <h3>Admin</h3>
  </div>

  <div class="admin-sidebar-links">
    <a href="profile.php">Profile</a>
    <a href="admin.php">Main</a>
    <a href="support.php">Support</a>
  </div>

  <div class="admin-sidebar-footer">
    <button class="admin-logout-btn" onclick="goAdminHome()">Log Out</button>
  </div>
</div>

<div id="admin-overlay" class="admin-overlay" onclick="toggleAdminMenu()"></div>

<main class="admin-page">
  <div class="admin-card">
    <h1>Admin Dashboard</h1>
    <p class="admin-subtitle">View and manage registered user accounts.</p>

    <div class="admin-table-wrapper">
      <table class="admin-users-table">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody id="admin-users-body">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($user = $result->fetch_assoc()): ?>
              <?php
                $photo = !empty($user["photo"]) ? $user["photo"] : "images/default.png";
              ?>
              <tr>
                <td>
                  <img src="<?php echo htmlspecialchars($photo); ?>" class="admin-user-photo" alt="User">
                </td>
                <td><?php echo htmlspecialchars($user["name"]); ?></td>
                <td><?php echo htmlspecialchars($user["email"]); ?></td>
                <td>
                  <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    <input type="hidden" name="delete_id" value="<?php echo $user["id"]; ?>">
                    <button class="admin-delete-btn" type="submit" name="delete_user">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" style="text-align:center;">No registered users found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <p id="admin-message" class="admin-message">
      <?php echo htmlspecialchars($message); ?>
    </p>
  </div>
</main>

<div class="footer">
  <p>©️ Rakkez+.. Helping students build better study habits</p>
</div>

<script>
function toggleAdminMenu() {
  document.getElementById("admin-sidebar").classList.toggle("active");
  document.getElementById("admin-overlay").classList.toggle("active");
}

function goAdminHome() {
  window.location.href = "home.html";
}
</script>

</body>
</html>