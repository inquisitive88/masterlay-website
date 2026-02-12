<?php
/**
 * CMS Admin - Authentication Manager
 * Simplified session management for the website admin panel
 * Adapted from estimator portal's SessionManager
 */

class CmsAuth {
    private static $initialized = false;
    private static $cookieDomain = '';
    private static $isSecure = false;

    /**
     * Initialize and start admin session
     */
    public static function start() {
        if (session_status() === PHP_SESSION_ACTIVE) return;
        if (self::$initialized) return;

        self::$initialized = true;
        self::$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $host = explode(':', $host)[0];

        // Local dev: empty domain
        if (strpos($host, 'masterlay.renovations') !== false ||
            strpos($host, 'localhost') !== false ||
            strpos($host, '127.0.0.1') !== false) {
            self::$cookieDomain = '';
        } else {
            self::$cookieDomain = '.' . $host;
        }

        // Environment-prefixed session name
        $prefix = defined('CMS_APP_ENV') ? CMS_APP_ENV : 'local';
        session_name($prefix . '_cms_admin_session');

        session_set_cookie_params([
            'lifetime' => 60 * 60 * 8, // 8 hours
            'path'     => '/',
            'domain'   => self::$cookieDomain,
            'secure'   => self::$isSecure,
            'httponly'  => true,
            'samesite'  => 'Lax',
        ]);

        session_start();
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool {
        return isset($_SESSION['cms_user_id']) && isset($_SESSION['cms_role']);
    }

    /**
     * Get current user ID
     */
    public static function getUserId(): ?int {
        return $_SESSION['cms_user_id'] ?? null;
    }

    /**
     * Get current username
     */
    public static function getUsername(): ?string {
        return $_SESSION['cms_username'] ?? null;
    }

    /**
     * Login user
     */
    public static function login(int $userId, string $username, string $role = 'admin') {
        $_SESSION['cms_user_id'] = $userId;
        $_SESSION['cms_username'] = $username;
        $_SESSION['cms_role'] = $role;
        $_SESSION['cms_login_time'] = time();
        session_regenerate_id(true);
    }

    /**
     * Logout user
     */
    public static function logout() {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();

        // Clear remember-me cookie
        setcookie('cms_remember', '', time() - 3600, '/');
    }

    /**
     * Set remember-me cookie and store token
     */
    public static function setRememberToken(PDO $pdo, int $userId) {
        $selector = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

        $stmt = $pdo->prepare("INSERT INTO ml_admin_remember_tokens (user_id, selector, hashed_validator, expires) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $selector, $hashedValidator, $expires]);

        setcookie('cms_remember', $selector . ':' . $validator, [
            'expires'  => time() + (30 * 24 * 60 * 60),
            'path'     => '/',
            'domain'   => self::$cookieDomain,
            'secure'   => self::$isSecure,
            'httponly'  => true,
            'samesite'  => 'Lax',
        ]);
    }

    /**
     * Try auto-login via remember-me cookie
     */
    public static function tryRememberMe(PDO $pdo): bool {
        if (self::isLoggedIn()) return true;
        if (empty($_COOKIE['cms_remember'])) return false;

        $parts = explode(':', $_COOKIE['cms_remember']);
        if (count($parts) !== 2) return false;

        [$selector, $validator] = $parts;

        $stmt = $pdo->prepare("SELECT rt.*, u.username FROM ml_admin_remember_tokens rt JOIN ml_admin_users u ON u.id = rt.user_id WHERE rt.selector = ? AND rt.expires > NOW()");
        $stmt->execute([$selector]);
        $token = $stmt->fetch();

        if ($token && hash_equals($token['hashed_validator'], hash('sha256', $validator))) {
            self::login($token['user_id'], $token['username']);

            // Refresh the token
            $pdo->prepare("DELETE FROM ml_admin_remember_tokens WHERE selector = ?")->execute([$selector]);
            self::setRememberToken($pdo, $token['user_id']);

            return true;
        }

        // Invalid token — clear cookie
        setcookie('cms_remember', '', time() - 3600, '/');
        return false;
    }

    /**
     * Require authentication (redirect to login if not logged in)
     */
    public static function requireAuth(PDO $pdo) {
        self::start();
        if (!self::isLoggedIn()) {
            self::tryRememberMe($pdo);
        }
        if (!self::isLoggedIn()) {
            header('Location: /admin/login');
            exit;
        }
    }
}
