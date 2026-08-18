<?php
/**
 * Shop — Stripe configuration (environment-aware, mirrors the portal's model).
 *
 * local/dev: shared TEST-mode keys (safe to commit; no real money possible).
 * prod:      LIVE keys read from /etc/masterlay/stripe-secrets.php — the same
 *            root-owned secrets file the portal uses (same Stripe account).
 *            The SHOP webhook endpoint gets its OWN signing secret, stored in
 *            that file under the key 'shop_webhook_secret' when the endpoint
 *            is registered in the Stripe Dashboard.
 */
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if (!defined('CMS_APP_ENV')) {
    require_once dirname(__DIR__, 2) . '/admin/includes/admin-db.php';
}

if (CMS_APP_ENV === 'prod') {
    $secretsFile = '/etc/masterlay/stripe-secrets.php';
    $secrets = is_readable($secretsFile) ? require $secretsFile : [];
    define('SHOP_STRIPE_SECRET_KEY', (string) ($secrets['secret_key'] ?? ''));
    define('SHOP_STRIPE_WEBHOOK_SECRET', (string) ($secrets['shop_webhook_secret'] ?? ''));
    define('SHOP_STRIPE_MODE', 'live');
} else {
    // TEST-mode keys live in a gitignored local file (GitHub push protection
    // rejects any sk_ key in the repo, test or not). Copy the .example file
    // to shop-stripe-keys.local.php and fill in the test keys.
    $localKeys = __DIR__ . '/shop-stripe-keys.local.php';
    $keys = is_readable($localKeys) ? require $localKeys : [];
    define('SHOP_STRIPE_SECRET_KEY', (string) ($keys['secret_key'] ?? ''));
    define('SHOP_STRIPE_WEBHOOK_SECRET', (string) ($keys['webhook_secret'] ?? ''));
    define('SHOP_STRIPE_MODE', 'test');
}

function shop_stripe(): \Stripe\StripeClient
{
    static $client = null;
    if ($client === null) {
        $client = new \Stripe\StripeClient(SHOP_STRIPE_SECRET_KEY);
    }
    return $client;
}

/**
 * Ontario HST (13%) Stripe tax-rate id — created once per mode, cached in
 * shop_settings so every Checkout Session reuses the same rate object.
 */
function shop_hst_tax_rate(PDO $pdo): string
{
    $key = 'hst_tax_rate_' . SHOP_STRIPE_MODE;
    $pdo->exec("CREATE TABLE IF NOT EXISTS shop_settings (
        setting_key VARCHAR(64) PRIMARY KEY,
        setting_value VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $stmt = $pdo->prepare("SELECT setting_value FROM shop_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $rateId = (string) $stmt->fetchColumn();
    if ($rateId !== '') {
        return $rateId;
    }
    $rate = shop_stripe()->taxRates->create([
        'display_name' => 'HST',
        'percentage' => 13.0,
        'inclusive' => false,
        'country' => 'CA',
        'state' => 'ON',
        'description' => 'Ontario HST 13%',
    ]);
    $pdo->prepare("INSERT INTO shop_settings (setting_key, setting_value) VALUES (?, ?)
                   ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")
        ->execute([$key, $rate->id]);
    return $rate->id;
}
