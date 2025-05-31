        // Sidebar toggle for small screens
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
        // Dark mode toggle
        document.getElementById('darkModeToggle').addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            document.getElementById('sidebar').classList.toggle('dark-mode');
            document.querySelector('.navbar-custom').classList.toggle('dark-mode');
        })