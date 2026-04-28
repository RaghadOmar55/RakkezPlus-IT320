function deleteNotification(id) {

    var xhr = new XMLHttpRequest();

    xhr.open("POST", "delete_notification.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {

                var element = document.querySelector('[value="' + id + '"]');
                
                if (element) {
                    var deleteBtn = element.previousElementSibling; 
                    deleteBtn.remove();
                    element.remove();
                }

                if (document.getElementById("notification-list").innerHTML.trim() === "") {
                    document.getElementById("notification-list").innerHTML =
                        '<div class="notification-empty">' +
                        '<h2>No Notifications</h2>' +
                        '<p>You’re all caught up 🎉</p>' +
                        '</div>';
                }

            } else {
                console.log("Error: " + xhr.status);
            }
        }
    };

    xhr.send("id=" + id);
}
// ===== MENU =====
function toggleMenu() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
}

