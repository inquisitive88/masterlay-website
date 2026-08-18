<?php
/**
 * Shop — cart page (/shop/cart). Items live in localStorage; prices are
 * fetched from cart-data.php (server-side truth). Checkout posts to the
 * Phase 3 endpoint.
 */
require_once __DIR__ . '/includes/shop-public-bootstrap.php';

$pageTitle = 'Your Cart | ' . SITE_NAME;
$pageDescription = 'Review your custom woodworking order.';
$currentPage = 'shop';
$heroTitle = 'Your Cart';
$heroSubtitle = 'Every piece is built to order';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Shop' => '/shop', 'Cart' => ''];
$root = dirname(__DIR__);
$basePath = '/'; // shop pages live one level deep — asset URLs must be root-absolute
?>
<!DOCTYPE html>
<html lang="en">
<?php include $root . '/includes/head.php'; ?>
<body class="bg-dark text-white antialiased">
<?php include $root . '/includes/loader.php'; ?>
<?php include $root . '/includes/header.php'; ?>

<main>
    <?php include $root . '/includes/page-hero.php'; ?>

    <section class="section-padding bg-dark">
        <div class="container-wide" style="max-width:900px;">
            <div id="cartEmpty" class="text-center py-16" style="display:none;">
                <p class="text-white/40 mb-6">Your cart is empty.</p>
                <a href="/shop" class="btn-primary">Browse the Shop</a>
            </div>

            <div id="cartContent" style="display:none;">
                <div id="cartLines" class="space-y-4"></div>

                <!-- Buyer details (collected on OUR site — Stripe only sees the cardholder) -->
                <div class="mt-8 rounded-2xl border border-white/10 bg-white/[0.02] p-5">
                    <h3 class="font-heading font-bold text-white mb-1">Your Details</h3>
                    <p class="text-white/35 text-xs mb-4">Order confirmation and delivery coordination use these details.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="text-white/50 text-xs">Full name *</label>
                            <input id="b-name" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="name"></div>
                        <div><label class="text-white/50 text-xs">Email *</label>
                            <input id="b-email" type="email" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="email"></div>
                        <div><label class="text-white/50 text-xs">Phone *</label>
                            <input id="b-phone" type="tel" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="tel"></div>
                        <div><label class="text-white/50 text-xs">Address line 1 *</label>
                            <input id="b-line1" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="address-line1"></div>
                        <div><label class="text-white/50 text-xs">Address line 2</label>
                            <input id="b-line2" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="address-line2"></div>
                        <div><label class="text-white/50 text-xs">City *</label>
                            <input id="b-city" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="address-level2"></div>
                        <div><label class="text-white/50 text-xs">Province</label>
                            <input id="b-province" value="ON" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="address-level1"></div>
                        <div><label class="text-white/50 text-xs">Postal code *</label>
                            <input id="b-postal" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" autocomplete="postal-code" placeholder="L6R 0G4"></div>
                    </div>
                    <div class="mt-4">
                        <label class="text-white/50 text-xs">Order notes (optional)</label>
                        <textarea id="b-notes" rows="3" maxlength="1000" class="w-full mt-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/15 text-white text-sm" placeholder="Anything we should know — wood/stain preferences, sizing questions, parking notes for delivery…"></textarea>
                    </div>
                    <p id="buyerMsg" class="text-red-400 text-sm mt-3" style="display:none;"></p>
                </div>

                <!-- Delivery choice -->
                <div class="mt-8 rounded-2xl border border-white/10 bg-white/[0.02] p-5">
                    <h3 class="font-heading font-bold text-white mb-3">Delivery</h3>
                    <label class="flex items-center gap-3 cursor-pointer mb-2">
                        <input type="radio" name="delivery" value="pickup" checked class="accent-[#FAA416]">
                        <span class="text-white/70 text-sm">Free pickup — Brampton workshop</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="delivery" value="delivery" class="accent-[#FAA416]">
                        <span class="text-white/70 text-sm">Local delivery — <span id="deliveryFeeLabel">$0.00</span> <span class="text-white/35">(one fee per order)</span></span>
                    </label>
                </div>

                <!-- Totals -->
                <div class="mt-6 rounded-2xl border border-white/10 bg-white/[0.02] p-5">
                    <div class="flex justify-between py-1 text-white/60 text-sm"><span>Order value</span><b class="text-white" id="totSub">$0.00</b></div>
                    <div class="flex justify-between py-1 text-white/60 text-sm" id="rowDelivery" style="display:none;"><span>Delivery</span><b class="text-white" id="totDelivery">$0.00</b></div>
                    <div class="flex justify-between py-1 text-white/60 text-sm"><span>HST (13%) on today's payment</span><b class="text-white" id="totTax">$0.00</b></div>
                    <div class="flex justify-between pt-3 mt-2 border-t border-white/10"><span class="font-heading font-bold">Due today (deposit + HST)</span><b class="text-primary text-xl" id="totGrand">$0.00</b></div>
                    <div class="flex justify-between py-1 text-white/50 text-sm" id="rowBalance" style="display:none;"><span>Balance at delivery/pickup (incl. HST)</span><b class="text-white/80" id="totBalance">$0.00</b></div>
                    <p class="text-white/35 text-xs mt-3" id="leadNote"></p>
                    <button id="checkoutBtn" class="btn-primary w-full justify-center mt-5">Proceed to Secure Checkout</button>
                    <p id="checkoutMsg" class="text-red-400 text-sm mt-3 text-center" style="display:none;"></p>
                    <p class="text-white/30 text-xs mt-3 text-center">Payments processed securely by Stripe</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include $root . '/includes/footer.php'; ?>
<?php include $root . '/includes/scripts.php'; ?>
<script src="/shop/assets/shop.js"></script>
<script>
(function () {
    var PRODUCTS = {};
    var TAX_RATE = 13;

    function money(n) { return '$' + n.toLocaleString('en-CA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

    function load() {
        var items = ShopCart.read();
        if (!items.length) {
            document.getElementById('cartEmpty').style.display = '';
            document.getElementById('cartContent').style.display = 'none';
            return;
        }
        fetch('/shop/cart-data?ids=' + items.map(function (i) { return i.id; }).join(','))
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d.success) return;
                TAX_RATE = d.tax_rate;
                PRODUCTS = {};
                d.products.forEach(function (p) { PRODUCTS[p.id] = p; });
                // prune cart items whose products no longer exist/are inactive
                var valid = items.filter(function (i) { return PRODUCTS[i.id]; });
                if (valid.length !== items.length) ShopCart.write(valid);
                render(valid);
            });
    }

    function render(items) {
        if (!items.length) {
            document.getElementById('cartEmpty').style.display = '';
            document.getElementById('cartContent').style.display = 'none';
            return;
        }
        document.getElementById('cartEmpty').style.display = 'none';
        document.getElementById('cartContent').style.display = '';

        var lines = document.getElementById('cartLines');
        lines.innerHTML = '';
        var subtotal = 0, upfront = 0, maxFee = 0, maxLead = 0;

        items.forEach(function (it) {
            var p = PRODUCTS[it.id];
            var lineTotal = p.price * it.qty;
            subtotal += lineTotal;
            upfront += p.deposit * it.qty;
            if (p.delivery_fee > maxFee) maxFee = p.delivery_fee;
            if (p.lead_time_weeks > maxLead) maxLead = p.lead_time_weeks;

            var row = document.createElement('div');
            row.className = 'flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.02] p-4';
            row.innerHTML =
                '<a href="/shop/item?slug=' + encodeURIComponent(p.slug) + '" class="w-20 h-20 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">'
                + (p.image ? '<img src="' + p.image + '" class="w-full h-full object-cover">' : '')
                + '</a>'
                + '<div class="flex-1 min-w-0">'
                +   '<div class="font-heading font-bold text-white truncate">' + p.name.replace(/</g, '&lt;') + '</div>'
                +   '<div class="text-white/40 text-xs mt-0.5">Made to order — ~' + p.lead_time_weeks + ' weeks</div>'
                +   '<div class="text-primary font-bold mt-1">' + money(p.price)
                +     (p.deposit < p.price ? ' <span class="text-white/40 text-xs font-normal">· ' + money(p.deposit) + ' deposit</span>' : '') + '</div>'
                + '</div>'
                + '<div class="flex items-center border border-white/15 rounded-full overflow-hidden flex-shrink-0">'
                +   '<button class="qminus px-3 py-1.5 text-white/60 hover:text-white">−</button>'
                +   '<span class="w-8 text-center text-sm">' + it.qty + '</span>'
                +   '<button class="qplus px-3 py-1.5 text-white/60 hover:text-white">+</button>'
                + '</div>'
                + '<b class="w-24 text-right flex-shrink-0">' + money(lineTotal) + '</b>'
                + '<button class="rm text-white/30 hover:text-red-400 flex-shrink-0" title="Remove">✕</button>';
            row.querySelector('.qminus').onclick = function () { ShopCart.setQty(it.id, it.qty - 1); load(); };
            row.querySelector('.qplus').onclick = function () { ShopCart.setQty(it.id, Math.min(10, it.qty + 1)); load(); };
            row.querySelector('.rm').onclick = function () { ShopCart.remove(it.id); load(); };
            lines.appendChild(row);
        });

        document.getElementById('deliveryFeeLabel').textContent = maxFee > 0 ? money(maxFee) : 'Free';
        var isDelivery = document.querySelector('input[name="delivery"]:checked').value === 'delivery';
        var deliveryFee = isDelivery ? maxFee : 0;
        // today's charge = deposits + delivery fee, plus HST on that amount;
        // the balance (incl. its HST) is collected at delivery/pickup
        var dueToday = upfront + deliveryFee;
        var tax = dueToday * TAX_RATE / 100;
        var orderValue = (subtotal + deliveryFee) * (1 + TAX_RATE / 100);
        var balance = Math.max(0, orderValue - (dueToday + tax));

        document.getElementById('totSub').textContent = money(orderValue) + ' incl. HST';
        document.getElementById('rowDelivery').style.display = isDelivery && deliveryFee > 0 ? 'flex' : 'none';
        document.getElementById('totDelivery').textContent = money(deliveryFee);
        document.getElementById('totTax').textContent = money(tax);
        document.getElementById('totGrand').textContent = money(dueToday + tax);
        document.getElementById('rowBalance').style.display = balance > 0.009 ? 'flex' : 'none';
        document.getElementById('totBalance').textContent = money(balance);
        document.getElementById('leadNote').textContent =
            'Estimated completion: ~' + maxLead + ' weeks (longest lead time in your order).';
    }

    document.querySelectorAll('input[name="delivery"]').forEach(function (r) {
        r.addEventListener('change', load);
    });

    var BUYER_KEY = 'ml_shop_buyer';
    // restore previously entered details
    try {
        var saved = JSON.parse(localStorage.getItem(BUYER_KEY) || '{}');
        ['name','email','phone','line1','line2','city','province','postal'].forEach(function (k) {
            if (saved[k]) document.getElementById('b-' + k).value = saved[k];
        });
    } catch (e) {}

    function readBuyer() {
        var buyer = {};
        ['name','email','phone','line1','line2','city','province','postal','notes'].forEach(function (k) {
            buyer[k] = document.getElementById('b-' + k).value.trim();
        });
        return buyer;
    }
    function validateBuyer(buyer) {
        if (!buyer.name) return 'Please enter your full name.';
        if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(buyer.email)) return 'Please enter a valid email address.';
        if (!buyer.phone) return 'Please enter your phone number.';
        if (!buyer.line1 || !buyer.city || !buyer.postal) return 'Please complete your address.';
        if (!/^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$/.test(buyer.postal)) return 'Please enter a valid Canadian postal code.';
        return null;
    }

    document.getElementById('checkoutBtn').addEventListener('click', function () {
        var btn = this, msg = document.getElementById('checkoutMsg');
        var buyerMsg = document.getElementById('buyerMsg');
        var buyer = readBuyer();
        var err = validateBuyer(buyer);
        buyerMsg.style.display = err ? '' : 'none';
        buyerMsg.textContent = err || '';
        if (err) { buyerMsg.scrollIntoView({ behavior: 'smooth', block: 'center' }); return; }
        localStorage.setItem(BUYER_KEY, JSON.stringify(buyer));
        btn.disabled = true; btn.textContent = 'Starting checkout…';
        fetch('/shop/api/create-checkout-session', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                items: ShopCart.read(),
                delivery_method: document.querySelector('input[name="delivery"]:checked').value,
                buyer: buyer
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success && d.url) { window.location = d.url; return; }
            throw new Error(d.error || 'Could not start checkout.');
        })
        .catch(function (e) {
            msg.textContent = e.message;
            msg.style.display = '';
            btn.disabled = false; btn.textContent = 'Proceed to Secure Checkout';
        });
    });

    load();
})();
</script>
</body>
</html>
