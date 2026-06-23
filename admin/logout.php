<?php
/**
 * CMS Admin - Logout
 */
define('CMS_ADMIN', true);
require_once __DIR__ . '/includes/admin-db.php';
require_once __DIR__ . '/includes/admin-auth.php';

CmsAuth::start();

// Clean up remember token from the unified auth_remember_tokens table.
// We delete by selector (unique per cookie) so this only removes THIS device's
// CMS token — the user's portal token (different selector, same table) is untouched.
if (!empty($_COOKIE['cms_remember'])) {
    $parts = explode(':', $_COOKIE['cms_remember']);
    if (count($parts) === 2) {
        $stmt = $pdo->prepare("DELETE FROM auth_remember_tokens WHERE selector = ? AND portal_scope = 'admin'");
        $stmt->execute([$parts[0]]);
    }
}

CmsAuth::logout();

header('Location: /admin/login');
exit;
