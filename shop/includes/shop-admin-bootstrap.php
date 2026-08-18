<?php
/**
 * Shop admin bootstrap — chains into the website's existing admin stack
 * (DB, auth guard, CSRF, helpers, layout) and adds the shop_ tables.
 * Every page in shop/admin/ starts with this include.
 */
require_once dirname(__DIR__, 2) . '/admin/includes/admin-bootstrap.php';
require_once __DIR__ . '/shop-tables.php';

// Admin tooling must never be cached — Safari aggressively reuses PHP pages
// that carry no cache headers, which leaves users on stale versions.
if (!headers_sent()) {
    header('Cache-Control: no-store, max-age=0');
}

/** Unique slug within shop_products (appends -2, -3… on collision). */
function shop_unique_slug(PDO $pdo, string $base, int $excludeId = 0): string
{
    $slug = slugify($base) ?: 'product';
    $candidate = $slug;
    $n = 2;
    $check = $pdo->prepare("SELECT COUNT(*) FROM shop_products WHERE slug = ? AND id != ?");
    while (true) {
        $check->execute([$candidate, $excludeId]);
        if ((int) $check->fetchColumn() === 0) {
            return $candidate;
        }
        $candidate = $slug . '-' . $n++;
    }
}

function shop_money(float $n): string
{
    return '$' . number_format($n, 2);
}
