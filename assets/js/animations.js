// ============================================
// Masterlay Renovations - GSAP Animations
// ============================================

gsap.registerPlugin(ScrollTrigger);

// Default ScrollTrigger settings
ScrollTrigger.defaults({
    toggleActions: 'play none none none',
});

// ---- Reusable Animation Patterns ----

function initAnimations() {

    // TEXT REVEAL - Split heading text into lines and animate
    document.querySelectorAll('[data-animate="text-reveal"]').forEach(el => {
        const split = new SplitType(el, { types: 'lines' });

        split.lines.forEach(line => {
            const wrapper = document.createElement('div');
            wrapper.style.overflow = 'hidden';
            wrapper.style.paddingBottom = '0.15em';
            wrapper.style.marginBottom = '-0.15em';
            line.parentNode.insertBefore(wrapper, line);
            wrapper.appendChild(line);
        });

        gsap.from(split.lines, {
            yPercent: 110,
            opacity: 0,
            duration: 0.9,
            ease: 'power3.out',
            stagger: 0.12,
            scrollTrigger: {
                trigger: el,
                start: 'top 88%',
            }
        });
    });

    // FADE UP - Elements fade in and move up
    document.querySelectorAll('[data-animate="fade-up"]').forEach(el => {
        const delay = parseFloat(el.dataset.delay) || 0;
        gsap.fromTo(el,
            { y: 50, autoAlpha: 0 },
            {
                y: 0, autoAlpha: 1,
                duration: 0.8,
                delay: delay,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%',
                }
            }
        );
    });

    // FADE IN - Simple opacity transition
    document.querySelectorAll('[data-animate="fade-in"]').forEach(el => {
        const delay = parseFloat(el.dataset.delay) || 0;
        gsap.fromTo(el,
            { autoAlpha: 0 },
            {
                autoAlpha: 1,
                duration: 1,
                delay: delay,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%',
                }
            }
        );
    });

    // STAGGER UP - Children of container animate in sequence
    document.querySelectorAll('[data-animate="stagger-up"]').forEach(container => {
        const children = container.children;
        gsap.fromTo(children,
            { y: 50, autoAlpha: 0 },
            {
                y: 0, autoAlpha: 1,
                duration: 0.7,
                stagger: 0.1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: container,
                    start: 'top 85%',
                }
            }
        );
    });

    // STAGGER FADE - Lighter stagger animation
    document.querySelectorAll('[data-animate="stagger-fade"]').forEach(container => {
        const children = container.children;
        gsap.fromTo(children,
            { y: 30, autoAlpha: 0 },
            {
                y: 0, autoAlpha: 1,
                duration: 0.6,
                stagger: 0.08,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: container,
                    start: 'top 88%',
                }
            }
        );
    });

    // IMAGE REVEAL - Clip-path reveal with zoom
    document.querySelectorAll('[data-animate="image-reveal"]').forEach(el => {
        const img = el.querySelector('img') || el;
        gsap.fromTo(el,
            { clipPath: 'inset(100% 0 0 0)' },
            {
                clipPath: 'inset(0% 0 0 0)',
                duration: 1.2,
                ease: 'power3.inOut',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                }
            }
        );

        if (el.querySelector('img')) {
            gsap.from(el.querySelector('img'), {
                scale: 1.3,
                duration: 1.4,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                }
            });
        }
    });

    // PARALLAX - Elements move relative to scroll
    ScrollTrigger.matchMedia({
        '(min-width: 1024px)': function() {
            document.querySelectorAll('[data-animate="parallax"]').forEach(el => {
                const speed = parseFloat(el.dataset.speed) || -0.2;
                gsap.to(el, {
                    yPercent: speed * 100,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: el,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true,
                    }
                });
            });
        }
    });

    // SCALE IN - Scale up with opacity
    document.querySelectorAll('[data-animate="scale-in"]').forEach(el => {
        gsap.fromTo(el,
            { scale: 0.8, autoAlpha: 0 },
            {
                scale: 1, autoAlpha: 1,
                duration: 0.6,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 88%',
                }
            }
        );
    });

    // SLIDE IN LEFT
    document.querySelectorAll('[data-animate="slide-left"]').forEach(el => {
        gsap.fromTo(el,
            { x: -80, autoAlpha: 0 },
            {
                x: 0, autoAlpha: 1,
                duration: 0.9,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                }
            }
        );
    });

    // SLIDE IN RIGHT
    document.querySelectorAll('[data-animate="slide-right"]').forEach(el => {
        gsap.fromTo(el,
            { x: 80, autoAlpha: 0 },
            {
                x: 0, autoAlpha: 1,
                duration: 0.9,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                }
            }
        );
    });

    // LINE DRAW - SVG stroke animation
    document.querySelectorAll('[data-animate="line-draw"]').forEach(el => {
        const length = el.getTotalLength ? el.getTotalLength() : 1000;
        el.style.strokeDasharray = length;
        el.style.strokeDashoffset = length;

        gsap.to(el, {
            strokeDashoffset: 0,
            duration: 2,
            ease: 'power2.inOut',
            scrollTrigger: {
                trigger: el,
                start: 'top 80%',
            }
        });
    });
}

// ---- Hero Animation (called from specific pages) ----
function animateHero() {
    const heroTl = gsap.timeline({ delay: 0.3 });

    const heroLabel = document.querySelector('.hero-label');
    const heroHeading = document.querySelector('.hero-heading');
    const heroPills = document.querySelector('.hero-pills');
    const heroButtons = document.querySelector('.hero-buttons');
    const heroScroll = document.querySelector('.hero-scroll');

    if (heroLabel) {
        heroTl.from(heroLabel, { opacity: 0, y: 20, duration: 0.6, ease: 'power3.out' });
    }

    if (heroHeading) {
        const split = new SplitType(heroHeading, { types: 'lines' });
        split.lines.forEach(line => {
            const wrapper = document.createElement('div');
            wrapper.style.overflow = 'hidden';
            wrapper.style.paddingBottom = '0.15em';
            wrapper.style.marginBottom = '-0.15em';
            line.parentNode.insertBefore(wrapper, line);
            wrapper.appendChild(line);
        });
        heroTl.from(split.lines, {
            yPercent: 110,
            duration: 0.9,
            ease: 'power3.out',
            stagger: 0.12,
        }, '-=0.2');
    }

    if (heroPills) {
        heroTl.from(heroPills.children, {
            opacity: 0,
            y: 20,
            duration: 0.5,
            stagger: 0.08,
            ease: 'power2.out',
        }, '-=0.3');
    }

    if (heroButtons) {
        heroTl.from(heroButtons.children, {
            opacity: 0,
            y: 20,
            duration: 0.5,
            stagger: 0.1,
            ease: 'power2.out',
        }, '-=0.2');
    }

    if (heroScroll) {
        heroTl.from(heroScroll, {
            opacity: 0,
            duration: 0.4,
            ease: 'power2.out',
        }, '-=0.1');
    }

    return heroTl;
}

// ---- Custom Cursor (desktop only) ----
function initCustomCursor() {
    if (window.matchMedia('(hover: none)').matches) return;

    const dot = document.createElement('div');
    dot.className = 'cursor-dot';
    const outline = document.createElement('div');
    outline.className = 'cursor-outline';
    document.body.appendChild(dot);
    document.body.appendChild(outline);

    let mouseX = 0, mouseY = 0;
    let outlineX = 0, outlineY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        dot.style.left = mouseX + 'px';
        dot.style.top = mouseY + 'px';
    });

    function animateOutline() {
        outlineX += (mouseX - outlineX) * 0.15;
        outlineY += (mouseY - outlineY) * 0.15;
        outline.style.left = outlineX + 'px';
        outline.style.top = outlineY + 'px';
        requestAnimationFrame(animateOutline);
    }
    animateOutline();

    // Hover effects on interactive elements
    const interactives = document.querySelectorAll('a, button, .service-card, .testimonial-card, input, textarea, select');
    interactives.forEach(el => {
        el.addEventListener('mouseenter', () => {
            dot.classList.add('active');
            outline.classList.add('active');
        });
        el.addEventListener('mouseleave', () => {
            dot.classList.remove('active');
            outline.classList.remove('active');
        });
    });

    // Hide when mouse leaves window
    document.addEventListener('mouseleave', () => {
        dot.style.opacity = '0';
        outline.style.opacity = '0';
    });
    document.addEventListener('mouseenter', () => {
        dot.style.opacity = '1';
        outline.style.opacity = '1';
    });
}

// Export for use in app.js
window.MasterlayAnimations = {
    init: initAnimations,
    heroAnimation: animateHero,
    customCursor: initCustomCursor,
};
