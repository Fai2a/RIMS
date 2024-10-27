// Simple notification example for a new appointment
let notificationBox = document.getElementById('notifications');

function showNotification(message) {
    let notification = document.createElement('div');
    notification.className = 'notification-item';
    notification.innerHTML = message;
    notificationBox.appendChild(notification);
}

// Example: show notification when page loads
document.addEventListener('DOMContentLoaded', () => {
    showNotification('New appointment scheduled!');
});
