<?php
/**
 * Shop — shared order recorder. Called by BOTH the Stripe webhook and (as a
 * resilience fallback) the order-complete page. Idempotent.
 *
 * Orders are created as status=pending by create-checkout-session with the
 * buyer's details collected on OUR site; this flips them to paid and attaches
 * the payment facts (amounts, card, receipt, reference) once Stripe confirms.
 */
require_once __DIR__ . '/shop-stripe.php';
require_once __DIR__ . '/shop-mail.php';

/**
 * @param string $sessionId Stripe Checkout session id (cs_…)
 * @return ?string order_number when paid+recorded, null when not applicable
 */
function shop_record_order(PDO $pdo, string $sessionId): ?string
{
    $session = shop_stripe()->checkout->sessions->retrieve($sessionId, [
        'expand' => ['payment_intent.latest_charge'],
    ]);
    if (($session->payment_status ?? '') !== 'paid') return null;
    if (($session->metadata->shop_order ?? '') !== '1') return null;

    // Locate the pending order (new flow: metadata order_id; fallback: session id)
    $order = null;
    $orderId = (int) ($session->metadata->order_id ?? 0);
    if ($orderId > 0) {
        $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch() ?: null;
    }
    if (!$order) {
        $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE stripe_session_id = ?");
        $stmt->execute([$sessionId]);
        $order = $stmt->fetch() ?: null;
    }
    if (!$order) {
        // Session references no known order (e.g. row manually deleted) — nothing to do
        error_log('[shop] paid session without order row: ' . $sessionId);
        return null;
    }
    if ($order['status'] !== 'pending') {
        return (string) $order['order_number']; // already recorded — idempotent
    }

    // ---- Payment facts from Stripe ----
    $paidTotal = ($session->amount_total ?? 0) / 100;
    $paidTax = ($session->total_details->amount_tax ?? 0) / 100;
    $pi = $session->payment_intent;
    $charge = is_object($pi) ? ($pi->latest_charge ?? null) : null;
    $cardBrand = is_object($charge) ? (string) ($charge->payment_method_details->card->brand ?? '') : '';
    $cardLast4 = is_object($charge) ? (string) ($charge->payment_method_details->card->last4 ?? '') : '';
    $receiptUrl = is_object($charge) ? (string) ($charge->receipt_url ?? '') : '';
    $paidAtTs = is_object($charge) ? (int) ($charge->created ?? time()) : time();
    $piId = is_object($pi) ? (string) $pi->id : (string) ($session->payment_intent ?? '');

    $balanceDue = max(0, round((float) $order['order_value'] - $paidTotal, 2));

    $pdo->prepare("UPDATE shop_orders SET
            status = 'paid', total = ?, tax = ?, balance_due = ?,
            stripe_session_id = ?, stripe_payment_intent = ?,
            card_brand = ?, card_last4 = ?, paid_at = ?, receipt_url = ?
        WHERE id = ? AND status = 'pending'")
        ->execute([
            $paidTotal, $paidTax, $balanceDue,
            $sessionId, $piId, $cardBrand, $cardLast4,
            gmdate('Y-m-d H:i:s', $paidAtTs), $receiptUrl,
            (int) $order['id'],
        ]);

    $orderNumber = (string) $order['order_number'];

    // ---- Emails to the BUYER the site collected (not the cardholder) ----
    try {
        require_once dirname(__DIR__, 2) . '/includes/config.php';

        $items = $pdo->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
        $items->execute([(int) $order['id']]);
        $items = $items->fetchAll();
        $maxLead = 0;
        $itemLines = '';
        foreach ($items as $it) {
            $maxLead = max($maxLead, (int) $it['lead_time_weeks']);
            $itemLines .= '<tr><td style="padding:6px 12px 6px 0;">' . htmlspecialchars($it['product_name']) . ' × ' . (int) $it['qty'] . '</td>'
                . '<td style="text-align:right;padding:6px 0;">$' . number_format((float) $it['line_total'], 2) . '</td></tr>';
        }
        $methodWord = $order['delivery_method'] === 'delivery' ? 'delivery' : 'pickup';

        $summary = '<table style="width:100%;max-width:440px;border-collapse:collapse;font-size:14px;">'
            . $itemLines
            . ((float) $order['delivery_fee'] > 0 ? '<tr><td style="padding:6px 12px 6px 0;">Local Delivery</td><td style="text-align:right;">$' . number_format((float) $order['delivery_fee'], 2) . '</td></tr>' : '')
            . '<tr><td style="padding:10px 12px 6px 0;font-weight:bold;border-top:1px solid #ddd;">Order value (incl. HST)</td>'
            . '<td style="text-align:right;font-weight:bold;border-top:1px solid #ddd;">$' . number_format((float) $order['order_value'], 2) . '</td></tr>'
            . '<tr><td style="padding:6px 12px 6px 0;color:#1a7f37;font-weight:bold;">Paid today (incl. HST)</td>'
            . '<td style="text-align:right;color:#1a7f37;font-weight:bold;">$' . number_format($paidTotal, 2) . '</td></tr>'
            . ($balanceDue > 0.009
                ? '<tr><td style="padding:6px 12px 6px 0;font-weight:bold;">Balance due at ' . $methodWord . '</td>'
                    . '<td style="text-align:right;font-weight:bold;">$' . number_format($balanceDue, 2) . '</td></tr>'
                : '')
            . '</table>';

        $receiptBlock = '<div style="margin-top:16px;padding:12px 16px;background:#f6f6f6;border-radius:8px;font-size:13px;color:#444;">'
            . '<b>Payment receipt</b><br>'
            . ($cardBrand !== '' ? strtoupper($cardBrand) . ' ending in ' . $cardLast4 . '<br>' : '')
            . 'Paid: $' . number_format($paidTotal, 2) . ' CAD on ' . gmdate('M j, Y g:i A', $paidAtTs) . ' UTC<br>'
            . 'Reference: ' . htmlspecialchars($piId)
            . ($receiptUrl !== '' ? '<br><a href="' . htmlspecialchars($receiptUrl) . '">View official card receipt</a>' : '')
            . '</div>';

        $addressBlock = '<p style="font-size:13px;color:#555;">'
            . '<b>' . ($order['delivery_method'] === 'delivery' ? 'Delivery address' : 'Contact address') . ':</b> '
            . htmlspecialchars(trim($order['address_line1'] . ' ' . $order['address_line2'] . ', ' . $order['address_city'] . ' ' . $order['address_province'] . ' ' . $order['address_postal']))
            . '</p>';

        if ($order['customer_email'] !== '') {
            $mail = get_masterlay_mailer();
            $mail->addAddress($order['customer_email'], $order['customer_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Order confirmed — ' . $orderNumber . ' | Masterlay Woodworks';
            $mail->Body = shop_branded_email('Order Confirmed', $orderNumber,
                '<p>Thank you for your order, <b>' . htmlspecialchars($order['customer_name']) . '</b>!</p>'
                . '<p>Every piece is handcrafted to order in our Brampton workshop — estimated completion is about <b>' . $maxLead . ' weeks</b>.</p>'
                . $summary . $receiptBlock . $addressBlock
                . '<p style="margin-top:16px;">'
                . ($balanceDue > 0.009 ? 'The remaining balance of <b>$' . number_format($balanceDue, 2) . '</b> is due at ' . $methodWord . '. ' : '')
                . 'We\'ll contact you to arrange ' . $methodWord . ' when your piece is ready.</p>');
            $mail->send();
        }

        $owner = get_masterlay_mailer();
        $owner->addAddress(defined('EMAIL') ? EMAIL : 'inquiry@masterlayrenovations.ca', 'Masterlay Shop');
        $owner->isHTML(true);
        $owner->Subject = '🛒 New shop order ' . $orderNumber . ' — $' . number_format($paidTotal, 2) . ' paid'
            . ($balanceDue > 0.009 ? ', $' . number_format($balanceDue, 2) . ' due' : '');
        $owner->Body = shop_branded_email('New Shop Order', $orderNumber,
            '<p><b>' . htmlspecialchars($order['customer_name']) . '</b> · '
            . htmlspecialchars($order['customer_email']) . ' · ' . htmlspecialchars($order['customer_phone']) . '<br>'
            . ucfirst($methodWord) . ' — '
            . htmlspecialchars(trim($order['address_line1'] . ' ' . $order['address_line2'] . ', ' . $order['address_city'] . ' ' . $order['address_postal']))
            . '</p>'
            . (trim((string) $order['customer_notes']) !== ''
                ? '<p style="background:#fff8e6;border:1px solid #f0d9a0;border-radius:8px;padding:10px 14px;"><b>Customer notes:</b><br>' . nl2br(htmlspecialchars($order['customer_notes'])) . '</p>'
                : '')
            . $summary . $receiptBlock
            . '<p><a href="https://masterlayrenovations.ca/shop/admin/orders">Open Orders admin</a></p>');
        $owner->send();
    } catch (Throwable $mailErr) {
        error_log('[shop] order email failed: ' . $mailErr->getMessage());
    }

    return $orderNumber;
}


/**
 * Records a paid BALANCE checkout session (metadata shop_balance=1).
 * @return ?string order_number when applied, null otherwise
 */
function shop_record_balance(PDO $pdo, string $sessionId): ?string
{
    $session = shop_stripe()->checkout->sessions->retrieve($sessionId, [
        'expand' => ['payment_intent.latest_charge'],
    ]);
    if (($session->payment_status ?? '') !== 'paid') return null;
    if (($session->metadata->shop_balance ?? '') !== '1') return null;

    $orderId = (int) ($session->metadata->order_id ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    if (!$order) return null;
    if ($order['balance_paid_at'] !== null) return (string) $order['order_number']; // idempotent

    $paid = ($session->amount_total ?? 0) / 100;
    $pi = $session->payment_intent;
    $piId = is_object($pi) ? (string) $pi->id : (string) ($session->payment_intent ?? '');
    $charge = is_object($pi) ? ($pi->latest_charge ?? null) : null;
    $receiptUrl = is_object($charge) ? (string) ($charge->receipt_url ?? '') : '';

    $pdo->prepare("UPDATE shop_orders SET balance_due = 0, total = total + ?, balance_paid_at = ?,
            balance_payment_method = 'card', balance_payment_ref = ? WHERE id = ? AND balance_paid_at IS NULL")
        ->execute([$paid, gmdate('Y-m-d H:i:s'), $piId, $orderId]);

    try {
        require_once dirname(__DIR__, 2) . '/includes/config.php';
        if ($order['customer_email'] !== '') {
            $mail = get_masterlay_mailer();
            $mail->addAddress($order['customer_email'], $order['customer_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Balance received — ' . $order['order_number'] . ' | Masterlay Woodworks';
            $mail->Body = shop_branded_email('Balance Received', $order['order_number'],
                '<p>Thank you, <b>' . htmlspecialchars($order['customer_name']) . '</b>! We\'ve received your remaining balance of <b>$' . number_format($paid, 2) . '</b> — your order is now paid in full.</p>'
                . '<p>Reference: ' . htmlspecialchars($piId)
                . ($receiptUrl !== '' ? ' · <a href="' . htmlspecialchars($receiptUrl) . '">View card receipt</a>' : '') . '</p>');
            $mail->send();
        }
        $owner = get_masterlay_mailer();
        $owner->addAddress(defined('EMAIL') ? EMAIL : 'inquiry@masterlayrenovations.ca', 'Masterlay Shop');
        $owner->isHTML(true);
        $owner->Subject = '💰 Balance paid — ' . $order['order_number'] . ' ($' . number_format($paid, 2) . ')';
        $owner->Body = shop_branded_email('Balance Paid', $order['order_number'],
            '<p><b>' . htmlspecialchars($order['customer_name']) . '</b> paid the remaining balance of <b>$' . number_format($paid, 2) . '</b> by card.</p>'
            . '<p>Reference: ' . htmlspecialchars($piId) . '</p>');
        $owner->send();
    } catch (Throwable $e) {
        error_log('[shop] balance email failed: ' . $e->getMessage());
    }
    return (string) $order['order_number'];
}
