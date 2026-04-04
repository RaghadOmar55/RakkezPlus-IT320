let notificationData = [
    { id: 1, message: "You have many tips. Check them out!" },
    { id: 2, message: "Great job! You completed a full study session." },
	{ id: 3, message: "Tip: Try studying without your phone nearby." }
];

function renderNotifications() {
    const container = document.getElementById("notification-list");
    container.innerHTML = "";

    if (notificationData.length === 0) {
        container.innerHTML = `
            <div class="notification-empty">
                <h2>No Notifications</h2>
                <p>You’re all caught up 🎉</p>
            </div>
        `;
        return;
    }

    notificationData.forEach(n => {
        const card = document.createElement("div");
        card.className = "notification-card";

        card.innerHTML = `
            <button class="notification-delete-icon" onclick="deleteNotification(${n.id})">✕</button>
            <p class="notification-text">${n.message}</p>
        `;

        container.appendChild(card);
    });
}

// ===== DELETE =====
function deleteNotification(id) {
    notificationData = notificationData.filter(n => n.id !== id);
    renderNotifications();
}

// ===== MENU =====
function toggleMenu() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
}

renderNotifications();