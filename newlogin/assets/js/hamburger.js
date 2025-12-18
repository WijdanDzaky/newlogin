document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburger-menu');
    const navButtons = document.getElementById('nav-buttons');

    if (hamburgerBtn && navButtons) {
        // Toggle menu saat hamburger diklik
        hamburgerBtn.addEventListener('click', function() {
            navButtons.classList.toggle('show');
        });

        // Close menu saat tombol di-klik
        const navLinks = navButtons.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navButtons.classList.remove('show');
            });
        });

        // Close menu saat klik di luar
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = navButtons.contains(event.target);
            const isClickOnHamburger = hamburgerBtn.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnHamburger) {
                navButtons.classList.remove('show');
            }
        });
    }
});
