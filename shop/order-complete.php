<?php
/**
 * Shop — post-checkout landing page (Stripe success_url).
 * Verifies the session with Stripe server-side, shows the confirmation,
 * and clears the browser cart.
 */
require_once __DIR__ . '/includes/shop-public-bootstrap.php';
require_once __DIR__ . '/includes/shop-order-recorder.php';

$sessionId = trim((string) ($_GET['session_id'] ?? ''));
$paid = false;
$orderNumber = null;
$customerEmail = '';
$isBalancePayment = false;

if ($sessionId !== '' && strpos($sessionId, 'cs_') === 0) {
    try {
        $session = shop_stripe()->checkout->sessions->retrieve($sessionId);
        $paid = ($session->payment_status ?? '') === 'paid';
        $customerEmail = (string) ($session->customer_details->email ?? '');
        $isBalancePayment = ($session->metadata->shop_balance ?? '') === '1';
        // Webhook normally records within seconds — but record here too
        // (idempotent) so no paid session can ever go missing.
        if ($paid) {
            $orderNumber = $isBalancePayment
                ? shop_record_balance($pdo, $sessionId)
                : shop_record_order($pdo, $sessionId);
        }
    } catch (Throwable $e) {
        error_log('[shop] order-complete fallback failed: ' . $e->getMessage());
    }
}

$pageTitle = 'Order Confirmed | ' . SITE_NAME;
$pageDescription = 'Your Masterlay Woodworks order is confirmed.';
$currentPage = 'shop';
$heroTitle = $paid ? 'Thank You!' : 'Order Status';
$heroSubtitle = $paid ? 'Your order is confirmed' : '';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Shop' => '/shop', 'Order' => ''];
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
        <div class="container-wide text-center" style="max-width:640px;">
            <?php if ($paid): ?>
                <div class="mx-auto mb-6 w-16 h-16 rounded-full flex items-center justify-center" style="background:rgba(34,197,94,0.12);">
                    <svg class="w-8 h-8" style="color:#22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="font-heading text-2xl font-bold text-white"><?= $isBalancePayment ? 'Balance received — order paid in full' : 'Your order is confirmed' ?></h2>
                <?php if ($orderNumber): ?>
                    <p class="text-primary font-bold text-lg mt-2"><?= htmlspecialchars($orderNumber) ?></p>
                <?php endif; ?>
                <p class="text-white/50 mt-4">
                    A confirmation email is on its way<?= $customerEmail ? ' to <b class="text-white/80">' . htmlspecialchars($customerEmail) . '</b>' : '' ?>.
                    Every piece is handcrafted to order — we'll be in touch about timing and
                    <?= true ? 'delivery or pickup' : '' ?> as your piece comes together.
                </p>
                <a href="/shop" class="btn-primary mt-8 inline-flex">Back to the Shop</a>
                <script>
                    // order is paid — the browser cart has served its purpose
                    localStorage.removeItem('ml_shop_cart');
                </script>
            <?php else: ?>
                <h2 class="font-heading text-2xl font-bold text-white">We couldn't confirm this order</h2>
                <p class="text-white/50 mt-4">If you completed payment, you'll still receive a confirmation email shortly. Otherwise your cart is untouched.</p>
                <a href="/shop/cart" class="btn-primary mt-8 inline-flex">Return to Cart</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include $root . '/includes/footer.php'; ?>
<?php include $root . '/includes/scripts.php'; ?>
</body>
</html>
