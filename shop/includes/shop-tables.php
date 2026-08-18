<?php
/**
 * Shop — schema bootstrap. All tables use the shop_ prefix.
 * Included by shop-admin-bootstrap.php (and later by the public shop pages).
 * CREATE TABLE IF NOT EXISTS only — safe to run on every request.
 */

if (defined('SHOP_TABLES_LOADED')) return;
define('SHOP_TABLES_LOADED', true);

$shopTables = [
    "CREATE TABLE IF NOT EXISTS shop_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL UNIQUE,
        sort_order INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS shop_products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT DEFAULT NULL,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        short_desc VARCHAR(500) NOT NULL DEFAULT '',
        long_desc MEDIUMTEXT,
        price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        dimensions VARCHAR(255) NOT NULL DEFAULT '',
        wood_finish VARCHAR(255) NOT NULL DEFAULT '',
        lead_time_weeks TINYINT UNSIGNED NOT NULL DEFAULT 3,
        status ENUM('draft','active') NOT NULL DEFAULT 'draft',
        sort_order INT NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_category (category_id),
        CONSTRAINT fk_shop_products_category FOREIGN KEY (category_id)
            REFERENCES shop_categories(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS shop_product_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        image_url VARCHAR(500) NOT NULL,
        r2_key VARCHAR(255) NOT NULL DEFAULT '',
        alt_text VARCHAR(255) NOT NULL DEFAULT '',
        sort_order INT NOT NULL DEFAULT 0,
        is_primary TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_product (product_id),
        CONSTRAINT fk_shop_images_product FOREIGN KEY (product_id)
            REFERENCES shop_products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS shop_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_number VARCHAR(20) NOT NULL UNIQUE,
        customer_name VARCHAR(255) NOT NULL DEFAULT '',
        customer_email VARCHAR(255) NOT NULL DEFAULT '',
        customer_phone VARCHAR(50) NOT NULL DEFAULT '',
        address_line1 VARCHAR(255) NOT NULL DEFAULT '',
        address_line2 VARCHAR(255) NOT NULL DEFAULT '',
        address_city VARCHAR(100) NOT NULL DEFAULT '',
        address_province VARCHAR(50) NOT NULL DEFAULT '',
        address_postal VARCHAR(20) NOT NULL DEFAULT '',
        delivery_method ENUM('pickup','delivery') NOT NULL DEFAULT 'pickup',
        subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        tax DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        currency CHAR(3) NOT NULL DEFAULT 'cad',
        stripe_session_id VARCHAR(255) DEFAULT NULL,
        stripe_payment_intent VARCHAR(255) DEFAULT NULL,
        status ENUM('pending','paid','in_production','ready','delivered','cancelled')
            NOT NULL DEFAULT 'pending',
        customer_notes TEXT,
        admin_notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_session (stripe_session_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS shop_order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT DEFAULT NULL,
        product_name VARCHAR(255) NOT NULL,
        unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        qty INT NOT NULL DEFAULT 1,
        line_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        lead_time_weeks TINYINT UNSIGNED NOT NULL DEFAULT 0,
        INDEX idx_order (order_id),
        CONSTRAINT fk_shop_items_order FOREIGN KEY (order_id)
            REFERENCES shop_orders(id) ON DELETE CASCADE,
        CONSTRAINT fk_shop_items_product FOREIGN KEY (product_id)
            REFERENCES shop_products(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Webhook idempotency (same pattern as the portal's stripe_events)
    "CREATE TABLE IF NOT EXISTS shop_stripe_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id VARCHAR(255) NOT NULL UNIQUE,
        event_type VARCHAR(100) NOT NULL,
        received_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($shopTables as $sql) {
    try {
        $pdo->exec($sql);
    } catch (Throwable $e) {
        error_log('[shop] table bootstrap failed: ' . $e->getMessage());
    }
}

// --- Guarded column migrations (idempotent) ---
$shopMigrations = [
    ['shop_products', 'deposit_amount', "ALTER TABLE shop_products ADD COLUMN deposit_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER price"],
    ['shop_orders', 'order_value', "ALTER TABLE shop_orders ADD COLUMN order_value DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER total"],
    ['shop_orders', 'balance_due', "ALTER TABLE shop_orders ADD COLUMN balance_due DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER order_value"],
    ['shop_orders', 'card_brand', "ALTER TABLE shop_orders ADD COLUMN card_brand VARCHAR(20) NOT NULL DEFAULT '' AFTER stripe_payment_intent"],
    ['shop_orders', 'card_last4', "ALTER TABLE shop_orders ADD COLUMN card_last4 VARCHAR(4) NOT NULL DEFAULT '' AFTER card_brand"],
    ['shop_orders', 'paid_at', "ALTER TABLE shop_orders ADD COLUMN paid_at DATETIME DEFAULT NULL AFTER card_last4"],
    ['shop_orders', 'receipt_url', "ALTER TABLE shop_orders ADD COLUMN receipt_url VARCHAR(500) NOT NULL DEFAULT '' AFTER paid_at"],
    ['shop_orders', 'balance_paid_at', "ALTER TABLE shop_orders ADD COLUMN balance_paid_at DATETIME DEFAULT NULL AFTER balance_due"],
    ['shop_orders', 'balance_payment_method', "ALTER TABLE shop_orders ADD COLUMN balance_payment_method VARCHAR(30) NOT NULL DEFAULT '' AFTER balance_paid_at"],
    ['shop_orders', 'balance_payment_ref', "ALTER TABLE shop_orders ADD COLUMN balance_payment_ref VARCHAR(255) NOT NULL DEFAULT '' AFTER balance_payment_method"],
];
foreach ($shopMigrations as [$table, $column, $ddl]) {
    try {
        if (!$pdo->query("SHOW COLUMNS FROM {$table} LIKE " . $pdo->quote($column))->fetch()) {
            $pdo->exec($ddl);
        }
    } catch (Throwable $e) {
        error_log("[shop] migration {$table}.{$column} failed: " . $e->getMessage());
    }
}

// Self-register the "Shop" link in the site's DB-driven navigation (after
// Gallery). Idempotent — runs once per database, including on prod after push.
try {
    $navExists = $pdo->prepare("SELECT COUNT(*) FROM ml_cms_navigation WHERE page_identifier = 'shop'");
    $navExists->execute();
    if ((int) $navExists->fetchColumn() === 0) {
        $gallerySort = (int) $pdo->query("SELECT COALESCE(sort_order, 3) FROM ml_cms_navigation WHERE page_identifier = 'gallery' LIMIT 1")->fetchColumn();
        $pdo->exec("UPDATE ml_cms_navigation SET sort_order = sort_order + 1 WHERE sort_order > {$gallerySort}");
        $ins = $pdo->prepare("INSERT INTO ml_cms_navigation (label, url, page_identifier, has_dropdown, sort_order, is_active) VALUES ('Shop', '/shop', 'shop', 0, ?, 1)");
        $ins->execute([$gallerySort + 1]);
    }
} catch (Throwable $e) {
    error_log('[shop] nav self-register failed: ' . $e->getMessage());
}

// Housekeeping: abandoned checkouts (pending, never paid) expire after 7 days
try {
    $pdo->exec("DELETE FROM shop_orders WHERE status = 'pending' AND created_at < NOW() - INTERVAL 7 DAY");
} catch (Throwable $e) { /* table may not exist yet */ }

// Seed the starting categories once (user-editable afterwards)
try {
    if ((int) $pdo->query("SELECT COUNT(*) FROM shop_categories")->fetchColumn() === 0) {
        $seed = $pdo->prepare("INSERT INTO shop_categories (name, slug, sort_order) VALUES (?, ?, ?)");
        foreach (['Beds', 'Tables', 'Nightstands', 'Coffee Tables', 'Shoe Cabinets'] as $i => $name) {
            $seed->execute([$name, strtolower(str_replace(' ', '-', $name)), $i]);
        }
    }
} catch (Throwable $e) {
    error_log('[shop] category seed failed: ' . $e->getMessage());
}
