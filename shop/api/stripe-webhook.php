<?php
/**
 * Shop — Stripe webhook. checkout.session.completed → shop_record_order()
 * (shared with the order-complete page fallback, idempotent on session id).
 * Register: https://masterlayrenovations.ca/shop/api/stripe-webhook
 */
require_once dirname(__DIR__) . '/includes/shop-public-bootstrap.php';
require_once dirname(__DIR__) . '/includes/shop-order-recorder.php';

$payload = (string) file_get_contents('php://input');
$sigHeader = (string) ($_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '');

try {
    if (SHOP_STRIPE_WEBHOOK_SECRET !== '') {
        $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, SHOP_STRIPE_WEBHOOK_SECRET);
    } else {
        // No signing secret configured (fresh local env): accept test-mode only.
        $event = \Stripe\Event::constructFrom(json_decode($payload, true) ?: []);
        if ($event->livemode) {
            http_response_code(400);
            exit('signature required for live events');
        }
    }
} catch (Throwable $e) {
    http_response_code(400);
    exit('invalid signature');
}

// Idempotency — process each event exactly once
try {
    $pdo->prepare("INSERT INTO shop_stripe_events (event_id, event_type) VALUES (?, ?)")
        ->execute([$event->id, $event->type]);
} catch (Throwable $e) {
    exit('already processed');
}

if ($event->type !== 'checkout.session.completed') {
    exit('ignored');
}
$session = $event->data->object;
$isOrder = ($session->metadata->shop_order ?? '') === '1';
$isBalance = ($session->metadata->shop_balance ?? '') === '1';
if (!$isOrder && !$isBalance) {
    exit('not a shop session'); // the portal's Stripe traffic shares this account
}

try {
    $orderNumber = $isBalance
        ? shop_record_balance($pdo, (string) $session->id)
        : shop_record_order($pdo, (string) $session->id);
    exit($orderNumber ? 'order ' . $orderNumber . ' recorded' : 'not applicable');
} catch (Throwable $e) {
    error_log('[shop] webhook order creation failed: ' . $e->getMessage());
    // Un-mark the event so Stripe's retry can attempt it again
    try {
        $pdo->prepare("DELETE FROM shop_stripe_events WHERE event_id = ?")->execute([$event->id]);
    } catch (Throwable $ignored) {}
    http_response_code(500);
    exit('error');
}
