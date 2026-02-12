<?php
/**
 * Public Site - Database Connection
 * Provides $pdo for the public-facing website
 * Same credentials as admin panel and estimator portal
 */

if (isset($pdo) && $pdo instanceof PDO) {
    return; // Already connected
}

// Environment detection
$_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (strpos($_host, 'masterlay.renovations') !== false ||
    strpos($_host, 'localhost') !== false ||
    strpos($_host, '127.0.0.1') !== false) {
    $_db_env = 'local';
} elseif (strpos($_host, 'dev.') === 0) {
    $_db_env = 'dev';
} else {
    $_db_env = 'prod';
}

switch ($_db_env) {
    case 'local':
        $_dsn = "mysql:unix_socket=/Applications/ServBay/tmp/mysql-8.4.sock;dbname=local;charset=utf8mb4";
        $_user = 'root';
        $_pass = 'ServBay.dev';
        break;
    case 'dev':
        $_dsn = "mysql:host=localhost;dbname=masterlay_dev;charset=utf8mb4";
        $_user = 'masterlay_dev_user';
        $_pass = 'VWc9u6oHmJr6uVE';
        break;
    default:
        $_dsn = "mysql:host=127.0.0.1;dbname=masterlay;charset=utf8mb4";
        $_user = 'masterlay_user';
        $_pass = 'VWc9u6oHmJr6uVE';
        break;
}

try {
    $pdo = new PDO($_dsn, $_user, $_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    error_log('Public site DB connection failed: ' . $e->getMessage());
    $pdo = null;
}

// Clean up temp vars
unset($_host, $_db_env, $_dsn, $_user, $_pass);
