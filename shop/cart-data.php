<?php
/**
 * Shop — cart pricing data (public JSON). The cart stores only product ids +
 * quantities client-side; all prices/fees come from here, server-side.
 */
require_once __DIR__ . '/includes/shop-public-bootstrap.php';
header('Content-Type: application/json');

$ids = array_filter(array_map('intval', explode(',', (string) ($_GET['ids'] ?? ''))));
$ids = array_slice(array_unique($ids), 0, 30);
if (!$ids) {
    echo json_encode(['success' => true, 'products' => [], 'tax_rate' => SHOP_TAX_RATE]);
    exit;
}
$in = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.slug, p.price, p.deposit_amount, p.delivery_fee, p.lead_time_weeks,
           (SELECT image_url FROM shop_product_images i
             WHERE i.product_id = p.id ORDER BY i.is_primary DESC, i.sort_order ASC LIMIT 1) AS image
    FROM shop_products p
    WHERE p.status = 'active' AND p.id IN ($in)");
$stmt->execute($ids);

echo json_encode([
    'success' => true,
    'products' => array_map(fn($p) => [
        'id' => (int) $p['id'],
        'name' => $p['name'],
        'slug' => $p['slug'],
        'price' => (float) $p['price'],
        'deposit' => (float) $p['deposit_amount'] > 0 && (float) $p['deposit_amount'] < (float) $p['price'] ? (float) $p['deposit_amount'] : (float) $p['price'],
        'delivery_fee' => (float) $p['delivery_fee'],
        'lead_time_weeks' => (int) $p['lead_time_weeks'],
        'image' => $p['image'],
    ], $stmt->fetchAll()),
    'tax_rate' => SHOP_TAX_RATE,
]);
