<?php
/**
 * CMS Admin - Logout
 */
define('CMS_ADMIN', true);
require_once __DIR__ . '/includes/admin-db.php';
require_once __DIR__ . '/includes/admin-auth.php';

CmsAuth::start();

// Clean up remember token from DB if exists
if (!empty($_COOKIE['cms_remember'])) {
    $parts = explode(':', $_COOKIE['cms_remember']);
    if (count($parts) === 2) {
        $stmt = $pdo->prepare("DELETE FROM ml_admin_remember_tokens WHERE selector = ?");
        $stmt->execute([$parts[0]]);
    }
}

CmsAuth::logout();

header('Location: /admin/login');
exit;
