<?php
/**
 * CMS Admin - Bootstrap
 * Single entry point included by every admin page
 * Sets up: DB connection, table creation, auth guard, CSRF, helpers
 */

// Prevent direct access
if (!defined('CMS_ADMIN')) {
    define('CMS_ADMIN', true);
}

// Load database connection
require_once __DIR__ . '/admin-db.php';

// Create CMS tables if needed
require_once __DIR__ . '/admin-db-tables.php';

// Load auth system
require_once __DIR__ . '/admin-auth.php';

// Load helper functions
require_once __DIR__ . '/admin-functions.php';

// Constants
define('CMS_ADMIN_URL', '/admin');
define('CMS_IMG', 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images');

// Determine current admin page for sidebar highlighting
$adminCurrentPage = basename($_SERVER['SCRIPT_NAME'], '.php');

// Start session and check auth (skip for login page)
if ($adminCurrentPage !== 'login') {
    CmsAuth::requireAuth($pdo);
}
