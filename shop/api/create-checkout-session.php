<?php
/**
 * Shop — create a Stripe Checkout Session from the browser cart.
 *
 * The BUYER's details are collected on OUR cart page (Stripe's billing info
 * belongs to the cardholder, which is not necessarily the buyer). This
 * endpoint creates the order row (status=pending) with the buyer's info and
 * item snapshot FIRST, then hands off to Stripe purely for payment. The
 * order-recorder flips it to paid when payment confirms.
 *
 * Every price is rebuilt from the database — the browser cart is never trusted.
 */
require_once dirname(__DIR__) . '/includes/shop-public-bootstrap.php';
require_once dirname(__DIR__) . '/includes/shop-stripe.php';
header('Content-Type: application/json');

function shop_fail(string $msg, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') shop_fail('POST only', 405);
$body = json_decode((string) file_get_contents('php://input'), true) ?: [];

// ---- Buyer details (collected on our site) ----
$buyer = is_array($body['buyer'] ?? null) ? $body['buyer'] : [];
$b = fn(string $k) => trim((string) ($buyer[$k] ?? ''));
$buyerName = $b('name');
$buyerEmail = strtolower($b('email'));
$buyerPhone = $b('phone');
$addr1 = $b('line1');
$addr2 = $b('line2');
$city = $b('city');
$province = $b('province') ?: 'ON';
$postal = strtoupper($b('postal'));
$notes = mb_substr($b('notes'), 0, 1000);

if ($buyerName === '' || mb_strlen($buyerName) > 150) shop_fail('Please enter your full name.');
if (!filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) shop_fail('Please enter a valid email address.');
if ($buyerPhone === '' || mb_strlen($buyerPhone) > 30) shop_fail('Please enter your phone number.');
if ($addr1 === '' || $city === '' || $postal === '') shop_fail('Please complete your address.');
if (!preg_match('/^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/', $postal)) shop_fail('Please enter a valid Canadian postal code.');

$deliveryMethod = ($body['delivery_method'] ?? 'pickup') === 'delivery' ? 'delivery' : 'pickup';
$itemsIn = is_array($body['items'] ?? null) ? $body['items'] : [];
$qtyById = [];
foreach ($itemsIn as $it) {
    $id = (int) ($it['id'] ?? 0);
    $qty = min(10, max(1, (int) ($it['qty'] ?? 0)));
    if ($id > 0 && $qty > 0) $qtyById[$id] = $qty;
}
if (!$qtyById || count($qtyById) > 30) shop_fail('Your cart is empty.');

// ---- Server-side truth: active products only ----
$in = implode(',', array_fill(0, count($qtyById), '?'));
$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.slug, p.price, p.deposit_amount, p.delivery_fee, p.lead_time_weeks,
           (SELECT image_url FROM shop_product_images i
             WHERE i.product_id = p.id ORDER BY i.is_primary DESC, i.sort_order ASC LIMIT 1) AS image
    FROM shop_products p WHERE p.status = 'active' AND p.id IN ($in)");
$stmt->execute(array_keys($qtyById));
$products = $stmt->fetchAll();
if (count($products) !== count($qtyById)) {
    shop_fail('Some items in your cart are no longer available — please review your cart.');
}

// ---- Money math (deposit model) ----
$fullSubtotal = 0.0;
$upfrontSubtotal = 0.0;
$deliveryFee = 0.0;
foreach ($products as $p) {
    $qty = $qtyById[(int) $p['id']];
    $price = (float) $p['price'];
    $deposit = (float) $p['deposit_amount'];
    $upfront = ($deposit > 0 && $deposit < $price) ? $deposit : $price;
    $fullSubtotal += $price * $qty;
    $upfrontSubtotal += $upfront * $qty;
    $deliveryFee = max($deliveryFee, (float) $p['delivery_fee']);
}
if ($deliveryMethod !== 'delivery') $deliveryFee = 0.0;
$orderValue = round(($fullSubtotal + $deliveryFee) * (1 + SHOP_TAX_RATE / 100), 2);

$base = 'https://' . $_SERVER['HTTP_HOST'];
try {
    $taxRate = shop_hst_tax_rate($pdo);

    $lineItems = [];
    foreach ($products as $p) {
        $price = (float) $p['price'];
        $deposit = (float) $p['deposit_amount'];
        $upfront = ($deposit > 0 && $deposit < $price) ? $deposit : $price;
        $isDeposit = $upfront < $price;
        $lineItems[] = [
            'quantity' => $qtyById[(int) $p['id']],
            'tax_rates' => [$taxRate],
            'price_data' => [
                'currency' => 'cad',
                'unit_amount' => (int) round($upfront * 100),
                'product_data' => array_filter([
                    'name' => ($isDeposit ? 'Deposit — ' : '') . $p['name'],
                    'description' => $isDeposit
                        ? 'Full price $' . number_format($price, 2) . ' + HST — balance due at delivery/pickup. Made to order, approx. ' . (int) $p['lead_time_weeks'] . ' weeks.'
                        : 'Made to order — approx. ' . (int) $p['lead_time_weeks'] . ' weeks',
                    'images' => $p['image'] ? [$p['image']] : null,
                ]),
            ],
        ];
    }
    if ($deliveryFee > 0) {
        $lineItems[] = [
            'quantity' => 1,
            'tax_rates' => [$taxRate],
            'price_data' => [
                'currency' => 'cad',
                'unit_amount' => (int) round($deliveryFee * 100),
                'product_data' => ['name' => 'Local Delivery'],
            ],
        ];
    }

    // ---- Create the order NOW (pending) with the buyer's own details ----
    $orderNumber = 'MLW-' . date('Y') . '-' . str_pad((string) ((int) $pdo->query("SELECT COALESCE(MAX(id),0) FROM shop_orders")->fetchColumn() + 1), 4, '0', STR_PAD_LEFT);
    $pdo->beginTransaction();
    $pdo->prepare("INSERT INTO shop_orders
        (order_number, customer_name, customer_email, customer_phone,
         address_line1, address_line2, address_city, address_province, address_postal,
         delivery_method, subtotal, delivery_fee, tax, total, order_value, balance_due, currency, customer_notes, status)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'cad',?,'pending')")
        ->execute([
            $orderNumber, $buyerName, $buyerEmail, $buyerPhone,
            $addr1, $addr2, $city, $province, $postal,
            $deliveryMethod, $fullSubtotal, $deliveryFee, 0, 0, $orderValue, $orderValue,
            $notes !== '' ? $notes : null,
        ]);
    $orderId = (int) $pdo->lastInsertId();
    $insItem = $pdo->prepare("INSERT INTO shop_order_items
        (order_id, product_id, product_name, unit_price, qty, line_total, lead_time_weeks)
        VALUES (?,?,?,?,?,?,?)");
    foreach ($products as $p) {
        $qty = $qtyById[(int) $p['id']];
        $insItem->execute([
            $orderId, (int) $p['id'], $p['name'], (float) $p['price'], $qty,
            round((float) $p['price'] * $qty, 2), (int) $p['lead_time_weeks'],
        ]);
    }
    $pdo->commit();

    $session = shop_stripe()->checkout->sessions->create([
        'mode' => 'payment',
        'line_items' => $lineItems,
        'currency' => 'cad',
        'customer_email' => $buyerEmail,
        'billing_address_collection' => 'auto',
        'custom_text' => ['submit' => ['message' => 'Every piece is handcrafted to order in our Brampton workshop. We will contact you about timing after checkout.']],
        'metadata' => [
            'shop_order' => '1',
            'order_id' => (string) $orderId,
            'order_number' => $orderNumber,
        ],
        'payment_intent_data' => [
            'description' => 'Woodworks order ' . $orderNumber,
            'metadata' => ['shop_order' => '1', 'order_number' => $orderNumber],
        ],
        'success_url' => $base . '/shop/order-complete?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $base . '/shop/cart',
    ]);

    $pdo->prepare("UPDATE shop_orders SET stripe_session_id = ? WHERE id = ?")
        ->execute([$session->id, $orderId]);

    echo json_encode(['success' => true, 'url' => $session->url]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('[shop] checkout session failed: ' . $e->getMessage());
    shop_fail('Payment service unavailable — please try again shortly.', 502);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('[shop] checkout error: ' . $e->getMessage());
    shop_fail('Could not start checkout.', 500);
}
