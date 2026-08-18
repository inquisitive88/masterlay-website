<?php
/**
 * Shop Admin — Orders (build queue). Status flow:
 * paid → in_production → ready → delivered (cancelled available anytime).
 * Each status change optionally emails the customer.
 */
require_once dirname(__DIR__) . '/includes/shop-admin-bootstrap.php';
require_once dirname(__DIR__) . '/includes/shop-stripe.php';
require_once dirname(__DIR__) . '/includes/shop-mail.php';

$adminPageTitle = 'Shop Orders';
$adminCurrentPage = 'shop-orders';
$adminBreadcrumb = ['Shop Orders' => ''];

$STATUS_FLOW = [
    'pending' => ['label' => 'Pending', 'color' => '#9ca3af'],
    'paid' => ['label' => 'Paid', 'color' => '#22c55e'],
    'in_production' => ['label' => 'In Production', 'color' => '#FAA416'],
    'ready' => ['label' => 'Ready', 'color' => '#3b82f6'],
    'delivered' => ['label' => 'Delivered', 'color' => '#8b5cf6'],
    'cancelled' => ['label' => 'Cancelled', 'color' => '#ef4444'],
];
$CUSTOMER_STATUS_MESSAGES = [
    'in_production' => 'Good news — your piece is now in production in our Brampton workshop!',
    'ready' => 'Your piece is finished and ready! We will reach out shortly to arrange %METHOD%.',
    'delivered' => 'Your order is complete. Thank you for supporting local craftsmanship — we hope you love it!',
];

// ---- Balance: send a Stripe payment link to the customer ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'send-balance-link') {
    require_csrf();
    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ?");
    $stmt->execute([(int) ($_POST['order_id'] ?? 0)]);
    $order = $stmt->fetch();
    if (!$order || (float) $order['balance_due'] <= 0.009 || $order['balance_paid_at'] !== null) {
        redirect('/shop/admin/orders', 'error', 'No outstanding balance on that order.');
    }
    try {
        $session = shop_stripe()->checkout->sessions->create([
            'mode' => 'payment',
            'customer_email' => $order['customer_email'] ?: null,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'cad',
                    'unit_amount' => (int) round(((float) $order['balance_due']) * 100),
                    'product_data' => [
                        'name' => 'Balance — Order ' . $order['order_number'],
                        'description' => 'Remaining balance, HST included.',
                    ],
                ],
            ]],
            'metadata' => ['shop_balance' => '1', 'order_id' => (string) $order['id'], 'order_number' => $order['order_number']],
            'payment_intent_data' => [
                'description' => 'Woodworks balance ' . $order['order_number'],
                'metadata' => ['shop_balance' => '1', 'order_number' => $order['order_number']],
            ],
            'success_url' => 'https://' . $_SERVER['HTTP_HOST'] . '/shop/order-complete?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://' . $_SERVER['HTTP_HOST'] . '/shop',
        ]);
        require_once dirname(__DIR__, 2) . '/includes/config.php';
        $mail = get_masterlay_mailer();
        $mail->addAddress($order['customer_email'], $order['customer_name']);
        $mail->isHTML(true);
        $mail->Subject = 'Balance payment — ' . $order['order_number'] . ' | Masterlay Woodworks';
        $mail->Body = shop_branded_email('Balance Payment', $order['order_number'],
            '<p>Hi <b>' . htmlspecialchars($order['customer_name']) . '</b>,</p>'
            . '<p>Your piece is nearly with you! The remaining balance on order <b>' . htmlspecialchars($order['order_number']) . '</b> is <b>$' . number_format((float) $order['balance_due'], 2) . '</b> (HST included).</p>'
            . '<p style="text-align:center;margin:24px 0;"><a href="' . htmlspecialchars($session->url) . '" '
            . 'style="background:#FAA416;color:#0A0A0A;font-weight:bold;padding:14px 32px;border-radius:8px;text-decoration:none;display:inline-block;">Pay Balance Securely</a></p>'
            . '<p style="font-size:12px;color:#888;">This secure Stripe link is valid for 24 hours — if it expires, just reply to this email and we\'ll send a fresh one. You can also pay by cash or e-transfer at ' . ($order['delivery_method'] === 'delivery' ? 'delivery' : 'pickup') . '.</p>');
        $mail->send();
        redirect('/shop/admin/orders', 'success', 'Balance payment link emailed to ' . $order['customer_email'] . '.');
    } catch (Throwable $e) {
        error_log('[shop] balance link failed: ' . $e->getMessage());
        redirect('/shop/admin/orders', 'error', 'Could not create the payment link.');
    }
}

// ---- Balance: mark received outside Stripe (cash / e-transfer / card in person) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'mark-balance-paid') {
    require_csrf();
    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ?");
    $stmt->execute([(int) ($_POST['order_id'] ?? 0)]);
    $order = $stmt->fetch();
    $method = in_array($_POST['method'] ?? '', ['cash', 'e-transfer', 'card', 'other'], true) ? $_POST['method'] : 'other';
    if (!$order || (float) $order['balance_due'] <= 0.009 || $order['balance_paid_at'] !== null) {
        redirect('/shop/admin/orders', 'error', 'No outstanding balance on that order.');
    }
    $pdo->prepare("UPDATE shop_orders SET total = total + balance_due, balance_due = 0,
            balance_paid_at = ?, balance_payment_method = ?, balance_payment_ref = ? WHERE id = ?")
        ->execute([gmdate('Y-m-d H:i:s'), $method, 'recorded by admin', (int) $order['id']]);
    redirect('/shop/admin/orders', 'success', 'Balance for ' . $order['order_number'] . ' marked received (' . $method . ').');
}

// ---- Delete order (record removal only — never touches the Stripe payment) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete-order') {
    require_csrf();
    $stmt = $pdo->prepare("SELECT order_number FROM shop_orders WHERE id = ?");
    $stmt->execute([(int) ($_POST['order_id'] ?? 0)]);
    $num = $stmt->fetchColumn();
    if (!$num) {
        redirect('/shop/admin/orders', 'error', 'Order not found.');
    }
    $pdo->prepare("DELETE FROM shop_orders WHERE id = ?")->execute([(int) $_POST['order_id']]); // items cascade
    redirect('/shop/admin/orders', 'success', 'Order ' . $num . ' deleted.');
}

// ---- Status update ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'set-status') {
    require_csrf();
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $newStatus = (string) ($_POST['status'] ?? '');
    $notify = !empty($_POST['notify']);
    if (!isset($STATUS_FLOW[$newStatus])) {
        redirect('/shop/admin/orders', 'error', 'Invalid status.');
    }
    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    if (!$order) {
        redirect('/shop/admin/orders', 'error', 'Order not found.');
    }
    $pdo->prepare("UPDATE shop_orders SET status = ?, admin_notes = ? WHERE id = ?")
        ->execute([$newStatus, trim((string) ($_POST['admin_notes'] ?? $order['admin_notes'])), $orderId]);

    if ($notify && $order['customer_email'] !== '' && isset($CUSTOMER_STATUS_MESSAGES[$newStatus])) {
        try {
            require_once dirname(__DIR__, 2) . '/includes/config.php';
            $msg = str_replace('%METHOD%', $order['delivery_method'] === 'delivery' ? 'delivery' : 'pickup', $CUSTOMER_STATUS_MESSAGES[$newStatus]);
            $mail = get_masterlay_mailer();
            $mail->addAddress($order['customer_email'], $order['customer_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Order ' . $order['order_number'] . ' — ' . $STATUS_FLOW[$newStatus]['label'] . ' | Masterlay Woodworks';
            $mail->Body = shop_branded_email($STATUS_FLOW[$newStatus]['label'], 'Order ' . $order['order_number'],
                '<p>Hi <b>' . htmlspecialchars($order['customer_name']) . '</b>,</p>'
                . '<p>' . htmlspecialchars($msg) . '</p>'
                . ((float) $order['balance_due'] > 0.009 && $order['balance_paid_at'] === null && in_array($newStatus, ['ready'], true)
                    ? '<p>A friendly reminder: the remaining balance of <b>$' . number_format((float) $order['balance_due'], 2) . '</b> (HST included) is due at ' . ($order['delivery_method'] === 'delivery' ? 'delivery' : 'pickup') . '.</p>'
                    : ''));
            $mail->send();
        } catch (Throwable $e) {
            error_log('[shop] status email failed: ' . $e->getMessage());
        }
    }
    redirect('/shop/admin/orders', 'success', 'Order ' . $order['order_number'] . ' → ' . $STATUS_FLOW[$newStatus]['label'] . ($notify ? ' (customer emailed)' : ''));
}

$statusFilter = (string) ($_GET['status'] ?? '');
$where = isset($STATUS_FLOW[$statusFilter]) ? "WHERE o.status = " . $pdo->quote($statusFilter) : "";
$orders = $pdo->query("
    SELECT o.*, (SELECT COUNT(*) FROM shop_order_items i WHERE i.order_id = o.id) AS item_count
    FROM shop_orders o {$where}
    ORDER BY o.created_at DESC LIMIT 200")->fetchAll();

$itemsByOrder = [];
if ($orders) {
    $ids = implode(',', array_map(fn($o) => (int) $o['id'], $orders));
    foreach ($pdo->query("SELECT * FROM shop_order_items WHERE order_id IN ($ids)") as $it) {
        $itemsByOrder[(int) $it['order_id']][] = $it;
    }
}

include dirname(__DIR__, 2) . '/admin/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Shop Orders</h1>
        <p class="text-white/40 text-sm mt-1">Your build queue — move each order through Paid → In Production → Ready → Delivered.</p>
    </div>
</div>

<?= render_flash_messages() ?>

<div class="flex items-center gap-2 mb-5 flex-wrap">
    <a href="/shop/admin/orders" class="admin-btn admin-btn-sm <?= $statusFilter === '' ? 'admin-btn-primary' : 'admin-btn-secondary' ?>">All</a>
    <?php foreach ($STATUS_FLOW as $key => $meta): ?>
        <a href="/shop/admin/orders?status=<?= $key ?>" class="admin-btn admin-btn-sm <?= $statusFilter === $key ? 'admin-btn-primary' : 'admin-btn-secondary' ?>"><?= $meta['label'] ?></a>
    <?php endforeach; ?>
</div>

<?php if (!$orders): ?>
    <div class="admin-card text-center py-14">
        <h3 class="admin-empty-title">No orders<?= $statusFilter ? ' in this status' : ' yet' ?></h3>
        <p class="admin-empty-text">Orders appear here automatically the moment Stripe confirms payment.</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
    <?php foreach ($orders as $o): $meta = $STATUS_FLOW[$o['status']] ?? $STATUS_FLOW['pending']; ?>
        <div class="admin-card">
            <div class="flex items-start justify-between flex-wrap gap-3">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-heading font-bold text-white text-lg"><?= e($o['order_number']) ?></span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:<?= $meta['color'] ?>22;color:<?= $meta['color'] ?>;"><?= $meta['label'] ?></span>
                        <span class="text-white/30 text-xs"><?= e(date('M j, Y g:i A', strtotime($o['created_at']))) ?></span>
                    </div>
                    <div class="text-white/60 text-sm mt-2">
                        <b class="text-white"><?= e($o['customer_name']) ?></b>
                        · <a class="text-primary" href="mailto:<?= e($o['customer_email']) ?>"><?= e($o['customer_email']) ?></a>
                        <?= $o['customer_phone'] ? '· ' . e($o['customer_phone']) : '' ?>
                    </div>
                    <div class="text-white/40 text-xs mt-1">
                        <?= $o['delivery_method'] === 'delivery'
                            ? 'Delivery — ' . e(trim($o['address_line1'] . ' ' . $o['address_line2'] . ', ' . $o['address_city'] . ' ' . $o['address_postal']))
                            : 'Pickup (Brampton workshop)' ?>
                    </div>
                    <ul class="text-white/70 text-sm mt-3 space-y-0.5">
                        <?php foreach ($itemsByOrder[(int) $o['id']] ?? [] as $it): ?>
                            <li>• <?= e($it['product_name']) ?> × <?= (int) $it['qty'] ?>
                                <span class="text-white/35">(<?= shop_money((float) $it['line_total']) ?><?= $it['lead_time_weeks'] ? ' · ~' . (int) $it['lead_time_weeks'] . ' wk' : '' ?>)</span></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (trim((string) $o['customer_notes']) !== ''): ?>
                        <div class="mt-3 text-sm rounded-lg px-3 py-2" style="background:rgba(250,164,22,0.07);border:1px solid rgba(250,164,22,0.25);color:rgba(255,255,255,0.75);">
                            <b class="text-primary">Customer notes:</b> <?= nl2br(e($o['customer_notes'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-right" style="min-width:230px;">
                    <div class="text-primary font-bold text-xl"><?= shop_money((float) $o['total']) ?> <span class="text-white/40 text-xs font-normal">paid</span></div>
                    <?php if ($o['balance_paid_at'] !== null): ?>
                        <div class="font-bold text-sm mt-0.5" style="color:#22c55e;">Paid in full ✓ <span class="text-white/35 font-normal">(balance via <?= e($o['balance_payment_method'] ?: 'card') ?>, <?= e(date('M j', strtotime($o['balance_paid_at']))) ?>)</span></div>
                    <?php elseif ((float) $o['balance_due'] > 0.009): ?>
                        <div class="font-bold text-sm mt-0.5" style="color:#ef4444;">Balance due: <?= shop_money((float) $o['balance_due']) ?></div>
                    <?php endif; ?>
                    <?php if ((float) $o['order_value'] > 0.009): ?>
                        <div class="text-white/35 text-xs mt-1">Order value <?= shop_money((float) $o['order_value']) ?> incl. HST</div>
                    <?php endif; ?>
                    <div class="text-white/35 text-xs mt-2" style="border-top:1px dashed rgba(255,255,255,0.1);padding-top:6px;">
                        <?= $o['card_brand'] ? strtoupper(e($o['card_brand'])) . ' •••• ' . e($o['card_last4']) . '<br>' : '' ?>
                        <?= $o['paid_at'] ? 'Paid ' . e(date('M j, Y g:i A', strtotime($o['paid_at']))) . ' UTC<br>' : '' ?>
                        <?php if ($o['stripe_payment_intent']): ?>
                            Ref: <span style="user-select:all;"><?= e($o['stripe_payment_intent']) ?></span><br>
                            <a class="underline" target="_blank" href="https://dashboard.stripe.com/payments/<?= e($o['stripe_payment_intent']) ?>">View in Stripe</a>
                        <?php endif; ?>
                        <?= $o['receipt_url'] ? ' · <a class="underline" target="_blank" href="' . e($o['receipt_url']) . '">Card receipt</a>' : '' ?>
                    </div>
                </div>
            </div>

            <form method="POST" action="/shop/admin/orders" class="flex items-end gap-3 flex-wrap mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.07);">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="set-status">
                <input type="hidden" name="order_id" value="<?= (int) $o['id'] ?>">
                <div>
                    <label class="admin-form-label">Status</label>
                    <select name="status" class="admin-form-select" style="min-width:160px;">
                        <?php foreach ($STATUS_FLOW as $key => $m): ?>
                            <option value="<?= $key ?>" <?= $o['status'] === $key ? 'selected' : '' ?>><?= $m['label'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex:1;min-width:200px;">
                    <label class="admin-form-label">Internal notes</label>
                    <input type="text" name="admin_notes" value="<?= e((string) $o['admin_notes']) ?>" class="admin-form-input" placeholder="e.g. walnut ordered, glue-up Friday">
                </div>
                <label class="flex items-center gap-2 text-white/60 text-sm pb-2 cursor-pointer">
                    <input type="checkbox" name="notify" value="1" checked> Email customer
                </label>
                <button type="submit" class="admin-btn admin-btn-primary">Update</button>
                </form>
                <form method="POST" action="/shop/admin/orders" style="margin-left:auto;"
                      onsubmit="return confirm('Delete order <?= e($o['order_number']) ?> permanently?\n\nThis removes the record from YOUR system only — it does not refund or cancel anything in Stripe. This cannot be undone.');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete-order">
                    <input type="hidden" name="order_id" value="<?= (int) $o['id'] ?>">
                    <button type="submit" class="admin-btn admin-btn-sm" style="border-color:rgba(239,68,68,0.4);color:#ef4444;background:none;">Delete</button>
                </form>

            <?php if ($o['status'] !== 'pending' && $o['status'] !== 'cancelled' && (float) $o['balance_due'] > 0.009 && $o['balance_paid_at'] === null): ?>
            <div class="flex items-center gap-3 flex-wrap mt-3 pt-3" style="border-top:1px dashed rgba(255,255,255,0.07);">
                <span class="text-white/40 text-sm">Collect balance (<?= shop_money((float) $o['balance_due']) ?>):</span>
                <form method="POST" action="/shop/admin/orders" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="send-balance-link">
                    <input type="hidden" name="order_id" value="<?= (int) $o['id'] ?>">
                    <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm">📧 Email payment link</button>
                </form>
                <form method="POST" action="/shop/admin/orders" class="flex items-center gap-2" style="display:inline-flex;"
                      onsubmit="return confirm('Mark the balance as received? This records it as paid in full.');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="mark-balance-paid">
                    <input type="hidden" name="order_id" value="<?= (int) $o['id'] ?>">
                    <select name="method" class="admin-form-select" style="width:auto;padding:6px 28px 6px 10px;font-size:13px;">
                        <option value="cash">Cash</option>
                        <option value="e-transfer">E-transfer</option>
                        <option value="card">Card (in person)</option>
                        <option value="other">Other</option>
                    </select>
                    <button type="submit" class="admin-btn admin-btn-secondary admin-btn-sm">Mark received</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include dirname(__DIR__, 2) . '/admin/includes/admin-layout-bottom.php'; ?>
