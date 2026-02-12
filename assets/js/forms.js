// ============================================
// Masterlay Renovations - Form Validation
// ============================================

(function() {
    'use strict';

    // Validation rules
    const validators = {
        name: {
            test: function(value) {
                return value.trim().length >= 2;
            },
            message: 'Please enter your full name'
        },
        email: {
            test: function(value) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
            },
            message: 'Please enter a valid email address'
        },
        phone: {
            test: function(value) {
                // Accept various phone formats: (416) 555-0123, 416-555-0123, 4165550123, +1 416 555 0123
                var cleaned = value.replace(/[\s\-\(\)\+\.]/g, '');
                return /^\d{10,11}$/.test(cleaned);
            },
            message: 'Please enter a valid phone number'
        },
        message: {
            test: function(value) {
                return value.trim().length >= 10;
            },
            message: 'Please describe your project (at least 10 characters)'
        }
    };

    // Set error state on a form group
    function setError(input, message) {
        var group = input.closest('.form-group');
        if (!group) return;

        group.classList.add('error');
        input.classList.add('error');

        var errorEl = group.querySelector('.form-error');
        if (errorEl && message) {
            errorEl.textContent = message;
        }
    }

    // Clear error state on a form group
    function clearError(input) {
        var group = input.closest('.form-group');
        if (!group) return;

        group.classList.remove('error');
        input.classList.remove('error');
    }

    // Validate a single field
    function validateField(input) {
        var fieldName = input.getAttribute('name');
        var validator = validators[fieldName];

        if (!validator) return true;

        if (!validator.test(input.value)) {
            setError(input, validator.message);
            return false;
        }

        clearError(input);
        return true;
    }

    // Validate entire form
    function validateForm(form) {
        var isValid = true;
        var firstInvalid = null;

        var requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(function(field) {
            if (!validateField(field)) {
                isValid = false;
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });

        // Focus the first invalid field
        if (firstInvalid) {
            firstInvalid.focus();
        }

        return isValid;
    }

    // Show success message with animation
    function showSuccessMessage(form) {
        var formParent = form.parentElement;
        var formRect = form.getBoundingClientRect();

        // Fade out form
        form.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        form.style.opacity = '0';
        form.style.transform = 'translateY(-20px)';

        setTimeout(function() {
            // Replace form with success message
            var successHTML = '' +
                '<div class="success-message" style="opacity: 0; transform: translateY(20px);">' +
                    '<div class="text-center py-16">' +
                        '<div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6">' +
                            '<svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' +
                            '</svg>' +
                        '</div>' +
                        '<h3 class="font-heading text-2xl font-bold text-white mb-3">Message Sent Successfully!</h3>' +
                        '<p class="text-white/60 max-w-md mx-auto mb-2">Thank you for reaching out. We\'ve received your project details and will get back to you within 24 hours.</p>' +
                        '<p class="text-white/40 text-sm">Check your email for a confirmation.</p>' +
                        '<div class="mt-8">' +
                            '<a href="/" class="btn btn-outline">Back to Home</a>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            form.style.display = 'none';
            formParent.insertAdjacentHTML('beforeend', successHTML);

            var successEl = formParent.querySelector('.success-message');

            // Animate in
            requestAnimationFrame(function() {
                successEl.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                successEl.style.opacity = '1';
                successEl.style.transform = 'translateY(0)';
            });
        }, 400);
    }

    // Initialize forms
    function initForms() {
        var contactForm = document.getElementById('contactForm');
        if (!contactForm) return;

        // Real-time validation on blur
        var inputs = contactForm.querySelectorAll('.form-input, .form-select');
        inputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                if (input.hasAttribute('required') && input.value.trim() !== '') {
                    validateField(input);
                }
            });

            // Clear error on input
            input.addEventListener('input', function() {
                if (input.closest('.form-group') && input.closest('.form-group').classList.contains('error')) {
                    clearError(input);
                }
            });
        });

        // Form submission
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (validateForm(contactForm)) {
                // Disable submit button and show loading state
                var submitBtn = contactForm.querySelector('[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '' +
                        '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">' +
                            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                            '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>' +
                        '</svg>' +
                        'Sending...';
                }

                // Submit form data via AJAX
                var formData = new FormData(contactForm);

                fetch('api/contact.php', {
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
                        showSuccessMessage(contactForm);
                    } else {
                        // Show error and re-enable button
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Send Message';
                        }
                        alert(result.data.message || 'Something went wrong. Please try again.');
                    }
                })
                .catch(function() {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Send Message';
                    }
                    alert('Network error. Please check your connection and try again.');
                });
            }
        });
    }

    // Export
    window.MasterlayForms = {
        init: initForms
    };

})();
