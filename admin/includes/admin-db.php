<?php
/**
 * CMS Admin - Database Connection
 * Environment-aware PDO connection (same credentials as estimator portal)
 */

// Environment detection
if (!defined('CMS_APP_ENV')) {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    if (strpos($host, 'masterlay.renovations') !== false ||
        strpos($host, 'localhost') !== false ||
        strpos($host, '127.0.0.1') !== false) {
        define('CMS_APP_ENV', 'local');
    } elseif (strpos($host, 'dev.') === 0) {
        define('CMS_APP_ENV', 'dev');
    } else {
        define('CMS_APP_ENV', 'prod');
    }
}

// DB credentials per environment
switch (CMS_APP_ENV) {
    case 'local':
        $cms_db_host = '127.0.0.1';
        $cms_db_name = 'local';
        $cms_db_user = 'root';
        $cms_db_pass = 'ServBay.dev';
        $cms_dsn = "mysql:unix_socket=/Applications/ServBay/tmp/mysql-8.4.sock;dbname={$cms_db_name};charset=utf8mb4";
        break;

    case 'dev':
        $cms_db_host = 'localhost';
        $cms_db_name = 'masterlay_dev';
        $cms_db_user = 'masterlay_dev_user';
        $cms_db_pass = 'VWc9u6oHmJr6uVE';
        $cms_dsn = "mysql:host={$cms_db_host};dbname={$cms_db_name};charset=utf8mb4";
        break;

    case 'prod':
    default:
        $cms_db_host = '127.0.0.1';
        $cms_db_name = 'masterlay';
        $cms_db_user = 'masterlay_user';
        $cms_db_pass = 'VWc9u6oHmJr6uVE';
        $cms_dsn = "mysql:host={$cms_db_host};dbname={$cms_db_name};charset=utf8mb4";
        break;
}

// Safety guards
if (CMS_APP_ENV === 'dev' && $cms_db_name === 'masterlay') {
    die('SAFETY: Dev environment cannot use production database.');
}
if (CMS_APP_ENV === 'prod' && $cms_db_name !== 'masterlay') {
    die('SAFETY: Production must use production database.');
}

$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($cms_dsn, $cms_db_user, $cms_db_pass, $pdo_options);
} catch (PDOException $e) {
    error_log('CMS DB connection failed: ' . $e->getMessage());
    die('Database connection failed. Please try again later.');
}
