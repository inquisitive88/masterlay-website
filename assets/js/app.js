// ============================================
// Masterlay Renovations - App Initialization
// ============================================

(function() {
    function init() {
        // Initialize scroll-triggered animations
        if (window.MasterlayAnimations) {
            MasterlayAnimations.init();
            MasterlayAnimations.customCursor();
        }

        // Initialize counters
        if (window.MasterlayCounter) {
            MasterlayCounter.init();
        }

        // Initialize gallery if on gallery page
        if (window.MasterlayGallery) {
            MasterlayGallery.init();
        }

        // Initialize forms if present
        if (window.MasterlayForms) {
            MasterlayForms.init();
        }

        // Initialize FAQ if present
        if (window.MasterlayFAQ) {
            MasterlayFAQ.init();
        }
    }

    // Wait for preloader to finish, then trigger hero and init.
    // Guarded so it only runs once — both the `preloaderDone` event AND the
    // setTimeout fallback below can fire on the same load, which previously
    // caused the hero title to animate twice on inner pages (gallery, about).
    let heroAnimationStarted = false;
    function onPreloaderDone() {
        if (heroAnimationStarted) return;
        heroAnimationStarted = true;
        if (window.MasterlayAnimations && document.querySelector('.hero-heading')) {
            MasterlayAnimations.heroAnimation();
        }
    }

    // Init animations after DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // After preloader
    window.addEventListener('preloaderDone', onPreloaderDone);

    // Fallback if preloader doesn't fire event (e.g., no preloader on page).
    // No-op if onPreloaderDone has already run via the event above.
    setTimeout(() => {
        if (document.querySelector('.hero-heading')) {
            onPreloaderDone();
        }
    }, 2500);

    // Refresh ScrollTrigger after all images load (layout may shift)
    window.addEventListener('load', () => {
        if (window.ScrollTrigger) {
            ScrollTrigger.refresh();
        }
    });

})();
