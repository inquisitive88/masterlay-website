// ============================================
// Masterlay Renovations - Gallery & Lightbox
// ============================================

(function() {
    'use strict';

    var currentFilter = 'all';
    var currentLightboxIndex = 0;
    var visibleItems = [];
    var galleryData = [];
    var isLightboxOpen = false;

    // ---- FILTER FUNCTIONALITY ----

    function initFilters() {
        var filterBtns = document.querySelectorAll('.gallery-filter-btn');
        var galleryItems = document.querySelectorAll('.gallery-item');
        var noResults = document.getElementById('galleryNoResults');
        var grid = document.getElementById('galleryGrid');

        if (!filterBtns.length || !galleryItems.length) return;

        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var filter = btn.getAttribute('data-filter');
                if (filter === currentFilter) return;

                currentFilter = filter;

                // Update active button
                filterBtns.forEach(function(b) {
                    b.classList.remove('bg-primary', 'text-dark', 'border-primary', 'active');
                    b.classList.add('bg-transparent', 'text-white/60', 'border-white/10');
                });
                btn.classList.remove('bg-transparent', 'text-white/60', 'border-white/10');
                btn.classList.add('bg-primary', 'text-dark', 'border-primary', 'active');

                // Filter items
                var visibleCount = 0;

                galleryItems.forEach(function(item) {
                    var category = item.getAttribute('data-category');
                    var shouldShow = (filter === 'all' || category === filter);

                    if (shouldShow) {
                        visibleCount++;
                        // Show with animation
                        item.style.display = '';
                        item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';

                        requestAnimationFrame(function() {
                            requestAnimationFrame(function() {
                                item.style.opacity = '1';
                                item.style.transform = 'scale(1)';
                            });
                        });
                    } else {
                        // Hide with animation
                        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';

                        setTimeout(function() {
                            item.style.display = 'none';
                        }, 300);
                    }
                });

                // Show/hide no results message
                if (noResults) {
                    if (visibleCount === 0) {
                        noResults.classList.remove('hidden');
                    } else {
                        noResults.classList.add('hidden');
                    }
                }

                // Update visible items for lightbox navigation
                updateVisibleItems();
            });
        });
    }

    function updateVisibleItems() {
        visibleItems = [];
        var items = document.querySelectorAll('.gallery-item');
        items.forEach(function(item, index) {
            if (item.style.display !== 'none') {
                visibleItems.push(index);
            }
        });
    }

    // ---- LIGHTBOX FUNCTIONALITY ----

    function buildGalleryData() {
        var items = document.querySelectorAll('.gallery-item');
        galleryData = [];

        items.forEach(function(item) {
            var img = item.querySelector('img');
            var title = item.querySelector('h3');
            var type = item.querySelector('.badge');

            galleryData.push({
                src: img ? img.getAttribute('src').replace('w=800', 'w=1400') : '',
                title: title ? title.textContent : '',
                type: type ? type.textContent : ''
            });
        });
    }

    function openLightbox(index) {
        var lightbox = document.getElementById('galleryLightbox');
        if (!lightbox || !galleryData[index]) return;

        currentLightboxIndex = index;
        isLightboxOpen = true;

        updateLightboxImage();

        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animate in
        var backdrop = lightbox.querySelector('.lightbox-backdrop');
        var content = lightbox.querySelector('.lightbox-content');

        if (backdrop) {
            backdrop.style.opacity = '0';
            backdrop.style.transition = 'opacity 0.3s ease';
            requestAnimationFrame(function() {
                backdrop.style.opacity = '1';
            });
        }

        if (content) {
            content.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            content.style.transition = 'opacity 0.4s ease 0.1s, transform 0.4s ease 0.1s';
            requestAnimationFrame(function() {
                content.style.opacity = '1';
                content.style.transform = 'scale(1)';
            });
        }
    }

    function closeLightbox() {
        var lightbox = document.getElementById('galleryLightbox');
        if (!lightbox) return;

        isLightboxOpen = false;

        var backdrop = lightbox.querySelector('.lightbox-backdrop');
        var content = lightbox.querySelector('.lightbox-content');

        if (content) {
            content.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            content.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
        }

        if (backdrop) {
            backdrop.style.transition = 'opacity 0.3s ease 0.1s';
            backdrop.style.opacity = '0';
        }

        setTimeout(function() {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }, 350);
    }

    function updateLightboxImage() {
        var data = galleryData[currentLightboxIndex];
        if (!data) return;

        var img = document.getElementById('lightboxImage');
        var title = document.getElementById('lightboxTitle');
        var type = document.getElementById('lightboxType');
        var counter = document.getElementById('lightboxCounter');

        if (img) {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';

            img.onload = function() {
                img.style.opacity = '1';
            };
            img.src = data.src;
            img.alt = data.title;
        }

        if (title) title.textContent = data.title;
        if (type) type.textContent = data.type;

        // Counter uses visible items for context
        if (counter) {
            var posInVisible = visibleItems.indexOf(currentLightboxIndex);
            counter.textContent = (posInVisible + 1) + ' / ' + visibleItems.length;
        }
    }

    function navigateLightbox(direction) {
        if (!visibleItems.length) return;

        var currentPos = visibleItems.indexOf(currentLightboxIndex);
        var newPos;

        if (direction === 'next') {
            newPos = (currentPos + 1) % visibleItems.length;
        } else {
            newPos = (currentPos - 1 + visibleItems.length) % visibleItems.length;
        }

        currentLightboxIndex = visibleItems[newPos];
        updateLightboxImage();
    }

    function initLightbox() {
        var lightbox = document.getElementById('galleryLightbox');
        if (!lightbox) return;

        buildGalleryData();
        updateVisibleItems();

        // Open lightbox on image click
        var triggers = document.querySelectorAll('.gallery-image-trigger');
        triggers.forEach(function(trigger) {
            trigger.addEventListener('click', function() {
                var index = parseInt(trigger.getAttribute('data-index'), 10);
                openLightbox(index);
            });
        });

        // Close button
        var closeBtn = lightbox.querySelector('.lightbox-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeLightbox);
        }

        // Backdrop click to close
        var backdrop = lightbox.querySelector('.lightbox-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', closeLightbox);
        }

        // Navigation arrows
        var prevBtn = lightbox.querySelector('.lightbox-prev');
        var nextBtn = lightbox.querySelector('.lightbox-next');

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                navigateLightbox('prev');
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                navigateLightbox('next');
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!isLightboxOpen) return;

            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                navigateLightbox('prev');
            } else if (e.key === 'ArrowRight') {
                navigateLightbox('next');
            }
        });
    }

    // ---- INITIALIZATION ----

    function initGallery() {
        initFilters();
        initLightbox();
    }

    // Export
    window.MasterlayGallery = {
        init: initGallery
    };

})();
