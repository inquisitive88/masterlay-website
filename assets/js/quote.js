// ============================================
// Masterlay Renovations - Quote Calculator
// ============================================

(function() {
    'use strict';

    // Info popup data
    // To update an image: upload new image to R2 CDN under /images/quote/ and change the URL below.
    // To update description text: edit the 'desc' property for the corresponding key.
    var infoData = {
        // ---- Stairs: Shared / General ----
        staircases: {
            title: 'Number of Staircases',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/staircases.jpg',
            desc: 'Count each separate flight of stairs as one staircase. If your stairs have a landing that changes direction, each flight counts separately. For example, an L-shaped staircase with a landing is typically 2 staircases.'
        },
        has_railing: {
            title: 'Railing & Posts',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/has-railing.jpg',
            desc: 'Select "Yes" if your staircase currently has a handrail and newel posts, or if you would like them to be sanded and refinished as part of this project. This helps us include the additional labour and materials in your estimate.'
        },
        spindles_qty: {
            title: 'Counting Your Spindles',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/spindles.jpg',
            desc: 'Count all the vertical bars (balusters) between the handrail and the stairs. Most staircases have 2-3 spindles per step. A typical 13-step staircase usually has around 30-40 spindles in total.'
        },
        railing_lf: {
            title: 'Railing Linear Feet',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/railing.jpg',
            desc: 'Measure the total length of new railing needed in feet. This is the distance along the top of the handrail from one end to the other. Include all sections — both along the stairs and any flat runs at the top or bottom.'
        },
        staining: {
            title: 'Staining',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/staining.jpg',
            desc: 'Staining gives your new stair caps a rich, finished colour that matches your decor. We apply professional-grade wood stain and a protective topcoat for a beautiful, durable finish.'
        },
        staining_staircases: {
            title: 'Staircases to Stain',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/staining.jpg',
            desc: 'Enter the number of staircases that need to be stained. This may differ from the total number of staircases if some already have the desired finish.'
        },

        // ---- Stairs: Recapping ----
        box_step: {
            title: 'Box Step',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/box-step.jpg',
            desc: 'A step that is enclosed on all three sides with risers — the most common stair step type found in homes.'
        },
        open_step: {
            title: 'Open Step (Left/Right)',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/open-step.jpg',
            desc: 'A step where one or both sides are visible and exposed rather than enclosed by a wall. Creates a more open, modern look.'
        },
        spindles: {
            title: 'Spindles (Balusters)',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/spindles.jpg',
            desc: 'The vertical bars between the handrail and the stair treads that provide safety and add visual style to your staircase.'
        },
        railing: {
            title: 'Railing (Handrail)',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/railing.jpg',
            desc: 'The handrail that runs along the staircase for safety and support. Can be wood, metal, or a combination.'
        },
        posts: {
            title: 'Newel Posts',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/posts.jpg',
            desc: 'The larger vertical supports at the top and bottom of the staircase that anchor the railing system in place.'
        },

        // ---- Floors ----
        floor_sqft: {
            title: 'Total Square Footage',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/floor-sqft.jpg',
            desc: 'Enter the total area of flooring you need in square feet. Measure the length and width of each room and multiply them together, then add all rooms. For example, a 12x15 room = 180 sqft. Tip: add 10% extra for cuts and waste.'
        },
        floor_demo: {
            title: 'Existing Floor Demolition',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/floor-demo.jpg',
            desc: 'If you have existing flooring that needs to be removed before installing the new floor, select the type here. Different materials require different removal methods and labour, which affects the cost. Select "None" if the subfloor is already exposed.'
        },
        floor_material: {
            title: 'Include Material in Quote',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/floor-material.jpg',
            desc: 'Choose "Yes" if you want us to supply the flooring material. We source quality products at competitive prices. Choose "No" if you already have your own material or prefer to purchase it separately — we will quote labour only.'
        },
        mat_hardwood: {
            title: 'Solid Hardwood',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/mat-hardwood.jpg',
            desc: 'Solid hardwood flooring is made from a single piece of real wood (oak, maple, walnut, etc.). It can be sanded and refinished multiple times over its lifetime, making it a long-lasting investment. Best for main floors — not recommended for basements.'
        },
        mat_engineered: {
            title: 'Engineered Hardwood',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/mat-engineered.jpg',
            desc: 'Engineered hardwood has a real wood top layer bonded to a plywood base. It looks and feels like solid hardwood but is more dimensionally stable, making it suitable for basements and areas with temperature fluctuations. Can be refinished 1-2 times.'
        },
        mat_vinyl: {
            title: 'Luxury Vinyl Plank (LVP)',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/mat-vinyl.jpg',
            desc: 'Luxury vinyl plank is a waterproof, durable flooring that realistically mimics wood or stone. It is scratch-resistant, easy to maintain, and great for kitchens, bathrooms, basements, and high-traffic areas. Installed as a floating floor.'
        },
        mat_laminate: {
            title: 'Laminate',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/mat-laminate.jpg',
            desc: 'Laminate flooring uses a photographic image of wood or stone topped with a wear-resistant layer. It is budget-friendly, easy to install, and works well in living rooms and bedrooms. Not recommended for wet areas like bathrooms.'
        },
        baseboard: {
            title: 'Baseboard',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/baseboard.jpg',
            desc: 'Trim installed along the bottom of walls where they meet the floor. Protects the wall and provides a clean, finished look.'
        },
        shoe_molding: {
            title: 'Shoe Molding',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/shoe-molding.jpg',
            desc: 'A small, flexible trim piece installed where the baseboard meets the floor. Covers any gaps and provides a seamless transition.'
        },
        eng_method: {
            title: 'Installation Method',
            image: 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images/quote/engineered-install.jpg',
            desc: 'Engineered hardwood can be installed with glue and nails for maximum stability, or with nails only for a quicker installation.'
        }
    };

    var selectedCategory = 'stairs';
    var selectedService = '';

    // Validation
    var validators = {
        name: {
            test: function(v) { return v.trim().length >= 2; },
            message: 'Please enter your full name'
        },
        email: {
            test: function(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()); },
            message: 'Please enter a valid email address'
        },
        phone: {
            test: function(v) { return /^\d{10,11}$/.test(v.replace(/[\s\-\(\)\+\.]/g, '')); },
            message: 'Please enter a valid phone number'
        }
    };

    function setError(input, message) {
        var group = input.closest('.form-group');
        if (!group) return;
        group.classList.add('error');
        input.classList.add('error');
        var el = group.querySelector('.form-error');
        if (el && message) el.textContent = message;
    }

    function clearError(input) {
        var group = input.closest('.form-group');
        if (!group) return;
        group.classList.remove('error');
        input.classList.remove('error');
    }

    // ---- Show / Hide helpers ----
    function show(el) {
        if (!el) return;
        el.removeAttribute('hidden');
        el.style.overflow = 'hidden';
        el.style.transition = 'none';
        el.style.maxHeight = '0';
        el.style.opacity = '0';
        el.offsetHeight; // force reflow
        el.style.transition = 'max-height 0.5s ease, opacity 0.35s ease';
        el.style.maxHeight = el.scrollHeight + 200 + 'px';
        el.style.opacity = '1';
        var onEnd = function() {
            el.style.maxHeight = 'none';
            el.style.overflow = 'visible';
            el.removeEventListener('transitionend', onEnd);
        };
        el.addEventListener('transitionend', onEnd);
    }

    function hide(el) {
        if (!el) return;
        el.style.overflow = 'hidden';
        el.style.maxHeight = el.scrollHeight + 'px';
        el.offsetHeight; // force reflow
        el.style.transition = 'max-height 0.4s ease, opacity 0.25s ease';
        el.style.maxHeight = '0';
        el.style.opacity = '0';
    }

    // ---- Category buttons (Stairs / Floors) ----
    function initCategoryButtons() {
        var catStairs = document.getElementById('catStairs');
        var catFloors = document.getElementById('catFloors');
        if (!catStairs || !catFloors) return;

        catStairs.addEventListener('click', function() {
            if (selectedCategory === 'stairs') return;
            selectedCategory = 'stairs';
            selectedService = '';

            catStairs.classList.add('quote-card--active');
            catFloors.classList.remove('quote-card--active');

            document.getElementById('service_type').value = '';

            // Show stairs Step 2, hide floor sections
            show(document.getElementById('stepService'));
            hide(document.getElementById('stepFloorConfig'));
            hide(document.getElementById('sandingOptions'));
            hide(document.getElementById('recappingOptions'));
            hide(document.getElementById('getQuoteBtn'));

            // Deselect any stairs service cards
            document.querySelectorAll('.service-card').forEach(function(c) {
                c.classList.remove('quote-card--active');
            });
        });

        catFloors.addEventListener('click', function() {
            if (selectedCategory === 'floors') return;
            selectedCategory = 'floors';
            selectedService = 'flooring';

            catFloors.classList.add('quote-card--active');
            catStairs.classList.remove('quote-card--active');

            document.getElementById('service_type').value = 'flooring';

            // Hide stairs sections, show floor config
            hide(document.getElementById('stepService'));
            hide(document.getElementById('sandingOptions'));
            hide(document.getElementById('recappingOptions'));
            show(document.getElementById('stepFloorConfig'));
            show(document.getElementById('getQuoteBtn'));

            // Scroll to floor config
            var floorEl = document.getElementById('stepFloorConfig');
            setTimeout(function() {
                floorEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 150);
        });
    }

    // ---- Toggle buttons (Yes/No) ----
    function initToggles() {
        document.querySelectorAll('[data-toggle]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var name = btn.getAttribute('data-toggle');
                var value = btn.getAttribute('data-value');
                var group = btn.parentElement;

                group.querySelectorAll('[data-toggle="' + name + '"]').forEach(function(b) {
                    b.classList.remove('quote-toggle--active');
                });
                btn.classList.add('quote-toggle--active');
                document.getElementById(name).value = value;

                handleToggleChange(name, value);
            });
        });
    }

    function handleToggleChange(name, value) {
        // Stairs toggles
        if (name === 'sanding_has_railing') {
            var details = document.getElementById('sandingRailingDetails');
            if (value === 'yes') { show(details); } else { hide(details); }
        }
        if (name === 'recap_new_railing') {
            var lfEl = document.getElementById('recapRailingLf');
            if (value === 'yes') { show(lfEl); } else { hide(lfEl); }
        }
        if (name === 'recap_staining') {
            var sqEl = document.getElementById('recapStainingQty');
            if (value === 'yes') { show(sqEl); } else { hide(sqEl); }
        }

        // Floor toggles
        if (name === 'floor_include_material') {
            var matSection = document.getElementById('floorMaterialSection');
            if (value === 'yes') { show(matSection); } else { hide(matSection); }
        }
        if (name === 'floor_baseboard') {
            var bbInfo = document.getElementById('floorBaseboardInfo');
            if (value === 'yes') { show(bbInfo); updateFloorLfDisplays(); } else { hide(bbInfo); }
        }
        if (name === 'floor_shoe_molding') {
            var smInfo = document.getElementById('floorShoeMoldingInfo');
            if (value === 'yes') { show(smInfo); updateFloorLfDisplays(); } else { hide(smInfo); }
        }
    }

    // ---- Radio buttons ----
    function initRadios() {
        document.querySelectorAll('[data-radio]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var name = btn.getAttribute('data-radio');
                var value = btn.getAttribute('data-value');

                document.querySelectorAll('[data-radio="' + name + '"]').forEach(function(b) {
                    b.classList.remove('quote-radio--active');
                });
                btn.classList.add('quote-radio--active');
                document.getElementById(name).value = value;

                handleRadioChange(name, value);
            });
        });
    }

    function handleRadioChange(name, value) {
        if (name === 'sanding_spindle_type') {
            var el = document.getElementById('sandingSpindlesQty');
            if (value) { show(el); } else { hide(el); }
        }
        if (name === 'recap_spindle_type') {
            var el2 = document.getElementById('recapSpindlesQty');
            if (value !== 'none' && value !== '') { show(el2); } else { hide(el2); }
        }
        if (name === 'recap_posts_type') {
            var el3 = document.getElementById('recapPostsQty');
            if (value) { show(el3); } else { hide(el3); }
        }
        // floor_demo_type and floor_eng_method don't need conditional reveals
    }

    // ---- Service selection (Stairs only) ----
    function initServiceCards() {
        document.querySelectorAll('.service-card').forEach(function(card) {
            card.addEventListener('click', function() {
                var service = card.getAttribute('data-service');
                selectedService = service;
                document.getElementById('service_type').value = service;

                document.querySelectorAll('.service-card').forEach(function(c) {
                    c.classList.remove('quote-card--active');
                });
                card.classList.add('quote-card--active');

                var sandingEl = document.getElementById('sandingOptions');
                var recapEl = document.getElementById('recappingOptions');
                var getQuoteEl = document.getElementById('getQuoteBtn');

                if (service === 'sanding') {
                    show(sandingEl);
                    hide(recapEl);
                } else {
                    hide(sandingEl);
                    show(recapEl);
                }

                show(getQuoteEl);

                var target = service === 'sanding' ? sandingEl : recapEl;
                setTimeout(function() {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 150);
            });
        });
    }

    // ---- Floor: Material multi-select ----
    function initFloorMaterials() {
        document.querySelectorAll('.floor-mat-card').forEach(function(card) {
            card.addEventListener('click', function() {
                var mat = card.getAttribute('data-material');
                var hiddenInput = document.getElementById('floor_mat_' + mat);
                var sqftDiv = document.getElementById('floorMat' + capitalize(mat) + 'Sqft');
                var isSelected = hiddenInput.value === '1';

                if (isSelected) {
                    // Deselect
                    hiddenInput.value = '0';
                    card.classList.remove('quote-card--active');
                    if (sqftDiv) hide(sqftDiv);

                    // If engineered, also hide method section
                    if (mat === 'engineered') {
                        hide(document.getElementById('floorEngMethodSection'));
                    }
                } else {
                    // Select
                    hiddenInput.value = '1';
                    card.classList.add('quote-card--active');
                    if (sqftDiv) show(sqftDiv);

                    // If engineered, show method section
                    if (mat === 'engineered') {
                        show(document.getElementById('floorEngMethodSection'));
                    }
                }
            });
        });
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // ---- Floor: Sqft → LF auto-calculation ----
    function initFloorSqftListener() {
        var sqftInput = document.getElementById('floor_total_sqft');
        if (!sqftInput) return;

        sqftInput.addEventListener('input', function() {
            updateFloorLfDisplays();
        });
    }

    function updateFloorLfDisplays() {
        var sqft = parseInt(document.getElementById('floor_total_sqft').value) || 0;
        var lf = Math.round(sqft / 3);
        var bbLfEl = document.getElementById('floorBaseboardLf');
        var smLfEl = document.getElementById('floorShoeMoldingLf');
        if (bbLfEl) bbLfEl.textContent = lf;
        if (smLfEl) smLfEl.textContent = lf;
    }

    // ---- Info modal ----
    function initInfoModal() {
        var modal = document.getElementById('infoModal');
        var overlay = document.getElementById('infoModalOverlay');
        var closeBtn = document.getElementById('infoModalClose');
        var content = document.getElementById('infoModalContent');

        function openModal(key) {
            var data = infoData[key];
            if (!data) return;

            document.getElementById('infoModalImg').src = data.image;
            document.getElementById('infoModalImg').alt = data.title;
            document.getElementById('infoModalTitle').textContent = data.title;
            document.getElementById('infoModalDesc').textContent = data.desc;

            modal.classList.remove('pointer-events-none');
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
        }

        function closeModal() {
            modal.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            setTimeout(function() {
                modal.classList.add('pointer-events-none');
            }, 300);
        }

        document.querySelectorAll('.quote-info-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openModal(btn.getAttribute('data-info'));
            });
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (overlay) overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    }

    // ---- Contact modal ----
    function initContactModal() {
        var modal = document.getElementById('contactModal');
        var overlay = document.getElementById('contactModalOverlay');
        var closeBtn = document.getElementById('contactModalClose');
        var content = document.getElementById('contactModalContent');
        var openBtn = document.getElementById('openContactModal');
        var submitBtn = document.getElementById('submitQuoteBtn');

        if (!modal || !openBtn) return;

        function openModal() {
            modal.classList.remove('pointer-events-none');
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
            document.body.style.overflow = 'hidden';
            setTimeout(function() {
                var first = modal.querySelector('input');
                if (first) first.focus();
            }, 350);
        }

        function closeModal() {
            modal.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            document.body.style.overflow = '';
            setTimeout(function() {
                modal.classList.add('pointer-events-none');
            }, 300);
        }

        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!selectedService) {
                alert('Please select a service type first.');
                return;
            }
            openModal();
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (overlay) overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.opacity === '1') closeModal();
        });

        // Real-time validation in modal inputs
        modal.querySelectorAll('.form-input').forEach(function(input) {
            input.addEventListener('input', function() {
                var group = input.closest('.form-group');
                if (group && group.classList.contains('error')) {
                    clearError(input);
                }
            });
        });

        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                submitQuote(submitBtn, closeModal);
            });
        }
    }

    // ---- Submit quote via AJAX ----
    function submitQuote(submitBtn, closeModalFn) {
        var nameInput = document.getElementById('modal_name');
        var emailInput = document.getElementById('modal_email');
        var phoneInput = document.getElementById('modal_phone');
        var addressInput = document.getElementById('modal_address');
        var notesInput = document.getElementById('modal_notes');

        var valid = true;
        if (!validators.name.test(nameInput.value)) {
            setError(nameInput, validators.name.message); valid = false;
        } else { clearError(nameInput); }
        if (!validators.email.test(emailInput.value)) {
            setError(emailInput, validators.email.message); valid = false;
        } else { clearError(emailInput); }
        if (!validators.phone.test(phoneInput.value)) {
            setError(phoneInput, validators.phone.message); valid = false;
        } else { clearError(phoneInput); }

        if (!valid) return;

        submitBtn.disabled = true;
        var origHTML = submitBtn.innerHTML;
        submitBtn.innerHTML =
            '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">' +
                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>' +
            '</svg>' +
            ' Sending...';

        var form = document.getElementById('quoteForm');
        var formData = new FormData(form);
        formData.append('name', nameInput.value.trim());
        formData.append('email', emailInput.value.trim());
        formData.append('phone', phoneInput.value.trim());
        formData.append('address', addressInput ? addressInput.value.trim() : '');
        formData.append('notes', notesInput ? notesInput.value.trim() : '');

        fetch('/api/quote', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json().then(function(data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function(result) {
            if (result.ok && result.data.success) {
                closeModalFn();
                showSuccess(form);
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHTML;
                alert(result.data.message || 'Something went wrong. Please try again.');
            }
        })
        .catch(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origHTML;
            alert('Network error. Please check your connection and try again.');
        });
    }

    function showSuccess(form) {
        var parent = form.closest('.container-narrow') || form.parentElement;

        form.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        form.style.opacity = '0';
        form.style.transform = 'translateY(-20px)';

        setTimeout(function() {
            form.style.display = 'none';

            var successHTML =
                '<div class="success-message" style="opacity: 0; transform: translateY(20px);">' +
                    '<div class="text-center py-16">' +
                        '<div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6">' +
                            '<svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>' +
                            '</svg>' +
                        '</div>' +
                        '<h3 class="font-heading text-2xl font-bold text-white mb-3">Quote Sent to Your Email!</h3>' +
                        '<p class="text-white/60 max-w-md mx-auto mb-2">We\'ve calculated your estimate and sent it to your email address. Check your inbox for the detailed breakdown.</p>' +
                        '<p class="text-white/40 text-sm">This is an automated estimate. Final pricing will be confirmed after an on-site assessment.</p>' +
                        '<div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">' +
                            '<a href="/contact" class="btn btn-primary">Book Free Consultation</a>' +
                            '<a href="/" class="btn btn-outline">Back to Home</a>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            parent.insertAdjacentHTML('beforeend', successHTML);

            var successEl = parent.querySelector('.success-message');
            requestAnimationFrame(function() {
                successEl.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                successEl.style.opacity = '1';
                successEl.style.transform = 'translateY(0)';
            });
        }, 400);
    }

    // Init
    function init() {
        initCategoryButtons();
        initToggles();
        initRadios();
        initServiceCards();
        initFloorMaterials();
        initFloorSqftListener();
        initInfoModal();
        initContactModal();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
