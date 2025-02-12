document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    sidebar?.addEventListener('mouseenter', function() {
        mainContent.classList.remove('ml-16');
        mainContent.classList.add('ml-64');
    });

    sidebar?.addEventListener('mouseleave', function() {
        mainContent.classList.remove('ml-64');
        mainContent.classList.add('ml-16');
    });
});