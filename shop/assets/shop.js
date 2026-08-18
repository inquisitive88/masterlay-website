/* Masterlay Shop — cart store (localStorage) + floating cart pill. */
'use strict';

var ShopCart = {
    KEY: 'ml_shop_cart',

    read: function () {
        try {
            var raw = localStorage.getItem(this.KEY);
            var items = raw ? JSON.parse(raw) : [];
            return Array.isArray(items) ? items : [];
        } catch (e) { return []; }
    },
    write: function (items) {
        localStorage.setItem(this.KEY, JSON.stringify(items));
        this.renderPill();
    },
    add: function (productId, qty) {
        var items = this.read();
        var found = false;
        items.forEach(function (it) {
            if (it.id === productId) { it.qty = Math.min(10, it.qty + qty); found = true; }
        });
        if (!found) items.push({ id: productId, qty: Math.min(10, qty) });
        this.write(items);
    },
    setQty: function (productId, qty) {
        var items = this.read().map(function (it) {
            if (it.id === productId) it.qty = qty;
            return it;
        }).filter(function (it) { return it.qty > 0; });
        this.write(items);
    },
    remove: function (productId) {
        this.write(this.read().filter(function (it) { return it.id !== productId; }));
    },
    clear: function () { this.write([]); },
    count: function () {
        return this.read().reduce(function (n, it) { return n + it.qty; }, 0);
    },

    // Floating cart pill (bottom-right) on all shop pages
    renderPill: function () {
        var n = this.count();
        var pill = document.getElementById('shopCartPill');
        if (!pill) {
            pill = document.createElement('a');
            pill.id = 'shopCartPill';
            pill.href = '/shop/cart';
            pill.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:90;display:flex;align-items:center;gap:8px;'
                + 'background:#FAA416;color:#0A0A0A;font-weight:700;padding:12px 20px;border-radius:999px;'
                + 'box-shadow:0 8px 24px rgba(0,0,0,0.45);text-decoration:none;font-size:14px;transition:transform .15s;';
            pill.onmouseenter = function () { pill.style.transform = 'scale(1.05)'; };
            pill.onmouseleave = function () { pill.style.transform = ''; };
            pill.innerHTML = '<svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.3 2.3c-.6.6-.2 1.7.7 1.7H17m0 4a1 1 0 100-2 1 1 0 000 2zm-8 0a1 1 0 100-2 1 1 0 000 2z"/></svg>'
                + '<span id="shopCartPillCount"></span>';
            document.body.appendChild(pill);
        }
        pill.style.display = n > 0 ? 'flex' : 'none';
        var count = document.getElementById('shopCartPillCount');
        if (count) count.textContent = n + (n === 1 ? ' item' : ' items');
    }
};

document.addEventListener('DOMContentLoaded', function () { ShopCart.renderPill(); });
