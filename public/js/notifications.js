function hideNotification(element) {
    element.classList.add('opacity-0');
    setTimeout(() => {
        element.remove();
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        // Disparait au bout de 3 secondes
        setTimeout(() => {
            hideNotification(notification);
        }, 3000);

        const closeButton = notification.querySelector('.notification-close');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                hideNotification(notification);
            });
        }
    });
});
