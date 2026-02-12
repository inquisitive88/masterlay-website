// ============================================
// Masterlay Renovations - FAQ Functionality
// ============================================

(function() {
    'use strict';

    /**
     * Initialize FAQ tab switching and accordion behavior
     */
    function initFAQ() {
        initTabs();
        initAccordions();
    }

    // ---- Tab Switching ----

    function initTabs() {
        var tabs = document.querySelectorAll('[data-faq-tab]');
        if (!tabs.length) return;

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var targetCategory = this.getAttribute('data-faq-tab');
                switchTab(targetCategory, tabs);
            });
        });
    }

    function switchTab(targetCategory, tabs) {
        // Update tab button styles
        tabs.forEach(function(tab) {
            if (tab.getAttribute('data-faq-tab') === targetCategory) {
                tab.classList.remove('bg-white/5', 'text-white/60', 'hover:bg-white/10', 'hover:text-white');
                tab.classList.add('bg-primary', 'text-dark');
            } else {
                tab.classList.remove('bg-primary', 'text-dark');
                tab.classList.add('bg-white/5', 'text-white/60', 'hover:bg-white/10', 'hover:text-white');
            }
        });

        // Show/hide category sections with fade
        var sections = document.querySelectorAll('[data-faq-category]');
        sections.forEach(function(section) {
            if (section.getAttribute('data-faq-category') === targetCategory) {
                // Fade in target section
                section.classList.remove('hidden');
                section.style.opacity = '0';
                section.style.transform = 'translateY(10px)';
                section.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                // Trigger reflow then animate
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';
                    });
                });
            } else {
                section.classList.add('hidden');
                section.style.opacity = '0';
                section.style.transform = 'translateY(10px)';
            }
        });
    }

    // ---- Accordion Toggle ----

    function initAccordions() {
        var questions = document.querySelectorAll('.faq-question');
        if (!questions.length) return;

        questions.forEach(function(question) {
            question.addEventListener('click', function() {
                var faqItem = this.closest('.faq-item');
                var isOpen = faqItem.classList.contains('active');

                // Close other open items in the same section
                var parentSection = faqItem.closest('.faq-category-section') || faqItem.closest('section') || faqItem.parentElement;
                var siblings = parentSection.querySelectorAll('.faq-item.active');
                siblings.forEach(function(sibling) {
                    if (sibling !== faqItem) {
                        closeAccordion(sibling);
                    }
                });

                // Toggle current item
                if (isOpen) {
                    closeAccordion(faqItem);
                } else {
                    openAccordion(faqItem);
                }
            });
        });
    }

    function openAccordion(faqItem) {
        var answer = faqItem.querySelector('.faq-answer');
        var icon = faqItem.querySelector('.faq-icon');
        if (!answer) return;

        faqItem.classList.add('active');

        // Expand answer
        answer.style.maxHeight = answer.scrollHeight + 'px';
        answer.style.opacity = '1';

        // Rotate icon to X
        if (icon) {
            icon.style.transform = 'rotate(45deg)';
        }
    }

    function closeAccordion(faqItem) {
        var answer = faqItem.querySelector('.faq-answer');
        var icon = faqItem.querySelector('.faq-icon');
        if (!answer) return;

        faqItem.classList.remove('active');

        // Collapse answer
        answer.style.maxHeight = '0';
        answer.style.opacity = '0';

        // Reset icon rotation
        if (icon) {
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // ---- Export ----

    window.MasterlayFAQ = {
        init: initFAQ
    };

})();
