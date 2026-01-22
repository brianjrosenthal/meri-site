/* ========================================
   RITES OF PASSAGE - JAVASCRIPT
   ======================================== */

// Hamburger Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navMenu = document.getElementById('navMenu');
    
    if (hamburgerBtn && navMenu) {
        hamburgerBtn.addEventListener('click', function() {
            hamburgerBtn.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Handle submenu toggle on mobile
        const submenuToggles = navMenu.querySelectorAll('.has-submenu > .submenu-toggle');
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                // Only handle on mobile (screen width <= 768px)
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const parentLi = this.closest('.has-submenu');
                    parentLi.classList.toggle('submenu-open');
                }
            });
        });
        
        // Close menu when clicking on a submenu link (not parent link)
        const navLinks = navMenu.querySelectorAll('.submenu a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburgerBtn.classList.remove('active');
                navMenu.classList.remove('active');
                // Also close any open submenus
                document.querySelectorAll('.has-submenu').forEach(item => {
                    item.classList.remove('submenu-open');
                });
            });
        });
        
        // Close menu when clicking on non-submenu links
        const directNavLinks = navMenu.querySelectorAll(':scope > li > a:not(.submenu-toggle)');
        directNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburgerBtn.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnButton = hamburgerBtn.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnButton && navMenu.classList.contains('active')) {
                hamburgerBtn.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
});

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Only handle if it's a hash link (not just "#")
            if (href !== '#' && href.length > 1) {
                const targetId = href.substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    const headerOffset = 80; // Account for sticky header
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
