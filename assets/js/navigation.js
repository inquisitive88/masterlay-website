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

    // Mobile menu toggle
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            mobileMenu.classList.toggle('open');
            document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';

            // Stagger animate mobile links
            if (mobileMenu.classList.contains('open')) {
                mobileLinks.forEach((link, i) => {
                    link.style.transitionDelay = `${0.1 + i * 0.05}s`;
                });
            } else {
                mobileLinks.forEach(link => {
                    link.style.transitionDelay = '0s';
                });
            }
        });

        // Close on link click
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    }

    // Close mobile menu on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('open')) {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
})();
