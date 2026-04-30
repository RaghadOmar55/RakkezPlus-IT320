function deleteNotification(id, button) {
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "delete_notification.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {

            var card = button.closest(".notification-card");

            if (card) {
                card.remove();
            }

            var list = document.getElementById("notification-list");

            if (list.querySelectorAll(".notification-card").length === 0) {
                list.innerHTML =
                    '<div class="notification-empty">' +
                    '<h2>No Notifications</h2>' +
                    '<p>You’re all caught up 🎉</p>' +
                    '</div>';
            }
        }
    };

    xhr.send("id=" + encodeURIComponent(id));
}
// ===== MENU =====
function toggleMenu() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
}

