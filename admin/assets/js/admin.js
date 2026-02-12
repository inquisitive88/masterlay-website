/**
 * Masterlay CMS Admin - Core JavaScript
 * Handles: sidebar toggle, CSRF fetch wrapper, toasts, modals, confirmations
 */

(function() {
    'use strict';

    // ============================================
    // Sidebar Toggle
    // ============================================
    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const SIDEBAR_KEY = 'cms_sidebar_collapsed';

    if (sidebar && toggleBtn) {
        // Restore state
        if (localStorage.getItem(SIDEBAR_KEY) === '1' && window.innerWidth > 1024) {
            sidebar.classList.add('collapsed');
        }

        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem(SIDEBAR_KEY, sidebar.classList.contains('collapsed') ? '1' : '0');
            }
        });

        // Close mobile sidebar on outside click
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 1024 &&
                sidebar.classList.contains('mobile-open') &&
                !sidebar.contains(e.target) &&
                e.target !== toggleBtn) {
                sidebar.classList.remove('mobile-open');
            }
        });
    }

    // ============================================
    // CSRF Token
    // ============================================
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // ============================================
    // Fetch Wrapper with CSRF
    // ============================================
    window.adminFetch = async function(url, options = {}) {
        const defaults = {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
        };

        // Don't set Content-Type for FormData (browser sets boundary automatically)
        if (!(options.body instanceof FormData)) {
            defaults.headers['Content-Type'] = 'application/json';
        }

        const config = {
            ...defaults,
            ...options,
            headers: { ...defaults.headers, ...options.headers },
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || data.message || 'Request failed');
            }

            return data;
        } catch (err) {
            throw err;
        }
    };

    // ============================================
    // Toast Notifications
    // ============================================
    let toastContainer = null;

    window.showToast = function(message, type = 'success', duration = 3000) {
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `admin-toast admin-toast-${type}`;
        toast.innerHTML = `
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success'
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                }
            </svg>
            <span>${message}</span>
        `;

        toastContainer.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });
        });

        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };

    // ============================================
    // Confirm Modal
    // ============================================
    window.showConfirm = function(title, message, onConfirm, confirmText = 'Delete', confirmClass = 'admin-btn-danger') {
        const overlay = document.createElement('div');
        overlay.className = 'admin-modal-overlay';
        overlay.innerHTML = `
            <div class="admin-modal">
                <h3 class="font-heading font-bold text-lg mb-2">${title}</h3>
                <p class="text-white/50 text-sm mb-6">${message}</p>
                <div class="flex items-center justify-end gap-3">
                    <button class="admin-btn admin-btn-secondary" data-dismiss>Cancel</button>
                    <button class="admin-btn ${confirmClass}" data-confirm>${confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        requestAnimationFrame(() => overlay.classList.add('active'));

        const dismiss = () => {
            overlay.classList.remove('active');
            setTimeout(() => overlay.remove(), 200);
        };

        overlay.querySelector('[data-dismiss]').addEventListener('click', dismiss);
        overlay.querySelector('[data-confirm]').addEventListener('click', () => {
            dismiss();
            onConfirm();
        });

        // Close on backdrop click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) dismiss();
        });

        // Close on Escape
        const onEsc = (e) => {
            if (e.key === 'Escape') {
                dismiss();
                document.removeEventListener('keydown', onEsc);
            }
        };
        document.addEventListener('keydown', onEsc);
    };

    // ============================================
    // Delete Confirmations
    // ============================================
    document.addEventListener('click', (e) => {
        const deleteBtn = e.target.closest('[data-delete]');
        if (!deleteBtn) return;

        e.preventDefault();
        const url = deleteBtn.dataset.delete;
        const name = deleteBtn.dataset.name || 'this item';

        showConfirm(
            'Confirm Deletion',
            `Are you sure you want to delete <strong>${name}</strong>? This action cannot be undone.`,
            async () => {
                try {
                    const data = await adminFetch(url, { method: 'DELETE' });
                    showToast(data.message || 'Deleted successfully');
                    // Remove the row or card
                    const row = deleteBtn.closest('tr, [data-item]');
                    if (row) {
                        row.style.transition = 'opacity 0.3s, transform 0.3s';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        location.reload();
                    }
                } catch (err) {
                    showToast(err.message || 'Delete failed', 'error');
                }
            }
        );
    });

    // ============================================
    // Toggle Switches
    // ============================================
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest('.admin-toggle[data-toggle]');
        if (!toggle) return;

        const url = toggle.dataset.toggle;
        const isActive = toggle.classList.contains('active');

        toggle.classList.toggle('active');

        adminFetch(url, {
            method: 'POST',
            body: JSON.stringify({ is_active: isActive ? 0 : 1 }),
        }).then(() => {
            showToast(isActive ? 'Deactivated' : 'Activated');
        }).catch((err) => {
            toggle.classList.toggle('active'); // Revert
            showToast(err.message || 'Update failed', 'error');
        });
    });

    // ============================================
    // Auto-dismiss Flash Messages
    // ============================================
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.3s, transform 0.3s';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-10px)';
            setTimeout(() => flash.remove(), 300);
        }, 5000);
    });

    // ============================================
    // File Upload Areas
    // ============================================
    document.querySelectorAll('.admin-upload-area').forEach((area) => {
        const input = area.querySelector('input[type="file"]');
        if (!input) return;

        area.addEventListener('click', () => input.click());

        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('dragover');
        });

        area.addEventListener('dragleave', () => {
            area.classList.remove('dragover');
        });

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

})();
