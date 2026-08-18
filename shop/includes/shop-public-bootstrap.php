<?php
/**
 * Shop — public page bootstrap. Loads the site's content-loader (constants,
 * $navigation, $pdo, design includes context) plus the shop tables/helpers.
 */
require_once dirname(__DIR__, 2) . '/includes/content-loader.php';
require_once __DIR__ . '/shop-tables.php';

const SHOP_TAX_RATE = 13.0; // Ontario HST

function shop_money(float $n): string
{
    return '$' . number_format($n, 2);
}

/** Active products with category + primary image (catalog + cart pricing). */
function shop_active_products(PDO $pdo, ?int $categoryId = null): array
{
    $where = "WHERE p.status = 'active'" . ($categoryId ? " AND p.category_id = " . (int) $categoryId : "");
    return $pdo->query("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug,
               (SELECT image_url FROM shop_product_images i
                 WHERE i.product_id = p.id ORDER BY i.is_primary DESC, i.sort_order ASC LIMIT 1) AS primary_image
        FROM shop_products p
        LEFT JOIN shop_categories c ON c.id = p.category_id
        {$where}
        ORDER BY p.sort_order ASC, p.created_at DESC
    ")->fetchAll();
}

function shop_product_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM shop_products p
        LEFT JOIN shop_categories c ON c.id = p.category_id
        WHERE p.slug = ? AND p.status = 'active' LIMIT 1");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function shop_product_images(PDO $pdo, int $productId): array
{
    $stmt = $pdo->prepare("SELECT * FROM shop_product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC");
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
}
