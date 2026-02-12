// ============================================
// Masterlay Renovations - Counter Animation
// ============================================

function initCounters() {
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.dataset.counter, 10);
        const suffix = el.dataset.suffix || '';
        const prefix = el.dataset.prefix || '';
        const duration = parseFloat(el.dataset.duration) || 2;

        const counter = { value: 0 };

        ScrollTrigger.create({
            trigger: el,
            start: 'top 88%',
            once: true,
            onEnter: () => {
                gsap.to(counter, {
                    value: target,
                    duration: duration,
                    ease: 'power1.out',
                    onUpdate: () => {
                        el.textContent = prefix + Math.round(counter.value) + suffix;
                    },
                });
            },
        });
    });
}

window.MasterlayCounter = { init: initCounters };
