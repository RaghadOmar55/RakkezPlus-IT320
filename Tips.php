<?php
include 'db_connection.php'; 
ini_set('display_errors', 1);
error_reporting(E_ALL);

$query = "SELECT interruption.reason, tip.tip_text 
          FROM tip 
          JOIN interruption ON tip.interruption_id = interruption.interruption_id";

$result = $conn->query($query);

$data = [];

while($row = $result->fetch_assoc()){
    $data[$row['reason']][] = $row['tip_text'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tips - Rakkez+</title>

<style>
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-size: 16px;
    font-family: Arial, sans-serif;
    background-color: #F5F5F5;
}

h2 { font-size: 28px; }

.container { 
    flex: 1; 
    padding: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.home-navbar {
    background-color: white;
    padding: 15px 80px;
}

.home-logo { display: inline-block; }

.home-nav-links {
    float: right;
    margin-top: 17px;
}

.home-nav-links a {
    margin-left: 15px;
    text-decoration: none;
    color: #2E2E2E;
    font-weight: bold;
}
.home-nav-links a:hover { color: #3A78A1; }

.notification-menu-btn {
    position: fixed;
    top: 15px;
    left: 20px;
    font-size: 24px;
    background: none;
    color:black;
    border: none;
    cursor: pointer;
    z-index: 1300;
}

.notification-sidebar {
    position: fixed;
    top: 0;
    left: -280px;
    width: 260px;
    height: 100vh;
    background-color: white;
    box-shadow: 2px 0 15px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    transition: 0.3s ease;
    z-index: 1200;
}

.notification-sidebar.active { left: 0; }

.notification-sidebar-header {
    padding: 25px 20px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.notification-sidebar-links {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.notification-sidebar-links a {
    text-decoration: none;
    color: #2E2E2E;
    padding: 10px;
}

.notification-overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.3);
    display: none;
}

.notification-overlay.active { display: block; }

input {
    padding: 10px;
    width: 250px;
}

.search-btn {
    padding: 10px 14px;
    border: none;
    border-radius: 10px;
    background-color: #3A78A1;
    color: white;
    cursor: pointer;
}

.search-btn:last-of-type {
    background-color: #F28C28;
}

.list {
    margin-top: 30px;
    text-align: left;
    width: 100%;
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.item {
    background: white;
    padding: 18px 20px;
    margin-bottom: 20px;
    border-radius: 16px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.tips {
    display: none;
    margin-top: 10px;
    border-left: 3px solid #3A78A1;
    background-color: #f9fbfc;
    border-radius: 8px;
    padding: 10px;
}

.no-result {
    margin-top: 20px;
    color: red;
}

.search-container {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
</style>
</head>

<body>

<div class="home-navbar">
    <div class="home-logo">
        <img src="images/logo.jpeg" width="100">
    </div>

    <div class="home-nav-links">
        <a href="index.php">Main</a>
        <a href="home.php">Log Out</a>
    </div>
</div>

<button class="notification-menu-btn" onclick="toggleMenu()">☰</button>

<div id="notification-sidebar" class="notification-sidebar">
    <div class="notification-sidebar-header">
        <img src="images/default.png">
        <h3>Ranem</h3>
    </div>

    <div class="notification-sidebar-links">
        <a href="profile.html">Profile</a>
        <a href="index.php">Main</a>
        <a href="Tips.php">Tips</a>
        <a href="notifications.php">Notifications</a>
        <a href="support.php">Support</a>
    </div>
</div>

<div id="notification-overlay" class="notification-overlay" onclick="toggleMenu()"></div>

<div class="container">
    <h2>Focus Tips</h2>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search interruption...">
        <button class="search-btn" onclick="searchTips()">Search</button>
        <button class="search-btn" onclick="resetSearch()">Reset</button>
    </div>

    <div id="errorMsg" style="color:red; margin-top:10px;"></div>
    <div id="list" class="list"></div>
    <div id="noResult" class="no-result"></div>
</div>

<div class="footer">
    <p>©️ Rakkez+.. Helping students build better study habits</p>
</div>

<script>
const data = <?php echo json_encode($data); ?>;

function renderList(filtered) {
    const list = document.getElementById("list");
    list.innerHTML = "";

    Object.keys(filtered).forEach(reason => {
        const div = document.createElement("div");
        div.className = "item";
        div.innerHTML = reason + "<span>▼</span>";

        const tipsDiv = document.createElement("div");
        tipsDiv.className = "tips";

        filtered[reason].forEach(t => {
            const p = document.createElement("p");
            p.textContent = "- " + t;
            tipsDiv.appendChild(p);
        });

        div.onclick = () => {
            tipsDiv.style.display =
                tipsDiv.style.display === "block" ? "none" : "block";
        };

        list.appendChild(div);
        list.appendChild(tipsDiv);
    });
}

function searchTips() {
    const value = document.getElementById("searchInput").value.trim().toLowerCase();
    const error = document.getElementById("errorMsg");

    error.textContent = "";
    document.getElementById("noResult").textContent = "";

    if (value === "") {
        error.textContent = "Please enter a keyword before searching.";
        document.getElementById("list").innerHTML = "";
        return;
    }

    const filtered = {};

    Object.keys(data).forEach(key => {
        if (key.toLowerCase().includes(value)) {
            filtered[key] = data[key];
        }
    });

    if (Object.keys(filtered).length === 0) {
        document.getElementById("noResult").textContent =
            "No results found. Try a different keyword.";
        document.getElementById("list").innerHTML = "";
    } else {
        renderList(filtered);
    }
}

function resetSearch() {
    document.getElementById("searchInput").value = "";
    document.getElementById("noResult").textContent = "";
    document.getElementById("errorMsg").textContent = "";
    renderList(data);
}

function toggleMenu() {
    document.getElementById("notification-sidebar").classList.toggle("active");
    document.getElementById("notification-overlay").classList.toggle("active");
}

window.onload = function() {
    renderList(data);

    document.getElementById("searchInput").addEventListener("keypress", function(e){
        if (e.key === "Enter") {
            searchTips();
        }
    });
};
</script>

</body>
</html>
