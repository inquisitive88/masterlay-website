// ============================================
// Masterlay Renovations - Preloader
// ============================================

(function() {
    const preloader = document.getElementById('preloader');
    if (!preloader) return;

    const MIN_DISPLAY = 2000;
    const startTime = Date.now();

    function dismissLoader() {
        const elapsed = Date.now() - startTime;
        const remaining = Math.max(0, MIN_DISPLAY - elapsed);

        setTimeout(() => {
            preloader.classList.add('done');

            // Remove from DOM after transition
            setTimeout(() => {
                preloader.remove();
                document.body.classList.remove('loading');

                // Dispatch custom event for other scripts
                window.dispatchEvent(new CustomEvent('preloaderDone'));
            }, 800);
        }, remaining);
    }

    // Dismiss on window load
    if (document.readyState === 'complete') {
        dismissLoader();
    } else {
        window.addEventListener('load', dismissLoader);
    }
})();
