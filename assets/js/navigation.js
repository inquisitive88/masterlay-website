// ============================================
// Masterlay Renovations - Navigation
// ============================================

(function() {
    const header = document.getElementById('siteHeader');
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];

    // Header scroll behavior
    let lastScroll = 0;

    function handleScroll() {
        const scrollY = window.scrollY || window.pageYOffset;

        if (scrollY > 80) {
            header.classList.add('site-header--scrolled');
        } else {
            header.classList.remove('site-header--scrolled');
        }

        lastScroll = scrollY;
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Check initial state

    // Prevent Lenis from intercepting touch events inside mobile menu
    if (mobileMenu) {
        mobileMenu.addEventListener('touchstart', function(e) { e.stopPropagation(); }, { passive: true });
        mobileMenu.addEventListener('touchmove', function(e) { e.stopPropagation(); }, { passive: true });
        mobileMenu.addEventListener('touchend', function(e) { e.stopPropagation(); }, { passive: true });
        mobileMenu.addEventListener('wheel', function(e) { e.stopPropagation(); }, { passive: true });
    }

    // Helper: open/close mobile menu
    function openMobileMenu() {
        hamburger.classList.add('active');
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';

        // Stop Lenis smooth scroll so the menu can scroll natively
        if (typeof lenis !== 'undefined') lenis.stop();

        // Show menu (display: flex), then trigger fade-in on next frame
        mobileMenu.classList.add('open');
        mobileMenu.style.opacity = '0';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                mobileMenu.style.opacity = '1';
                // Stagger animate mobile links
                mobileLinks.forEach((link, i) => {
                    link.style.transitionDelay = `${0.1 + i * 0.05}s`;
                });
            });
        });
    }

    function closeMobileMenu() {
        hamburger.classList.remove('active');
        mobileMenu.style.opacity = '0';

        // Wait for fade-out, then hide
        setTimeout(() => {
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';

            mobileLinks.forEach(link => {
                link.style.transitionDelay = '0s';
            });

            // Re-enable Lenis smooth scroll
            if (typeof lenis !== 'undefined') lenis.start();
        }, 400);
    }

    // Mobile menu toggle
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            if (mobileMenu.classList.contains('open')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        // Close on link click
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => closeMobileMenu());
        });
    }

    // Close mobile menu on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('open')) {
            closeMobileMenu();
        }
    });
})();
