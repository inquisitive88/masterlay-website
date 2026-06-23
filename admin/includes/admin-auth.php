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
     * Set remember-me cookie and store token in the UNIFIED auth_remember_tokens
     * table shared with the estimator portal.
     *
     * The CMS uses its own cookie name (cms_remember) so it doesn't collide
     * with the portal's ml_remember cookie. Selectors are random per cookie,
     * so the two scopes coexist safely in the same DB table.
     */
    public static function setRememberToken(PDO $pdo, int $userId) {
        $selector = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

        $stmt = $pdo->prepare(
            "INSERT INTO auth_remember_tokens (user_id, selector, hashed_validator, expires_at, portal_scope)
             VALUES (?, ?, ?, ?, 'admin')"
        );
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
     * Try auto-login via remember-me cookie.
     * Looks up the token in the unified auth_remember_tokens table and joins
     * to auth_users to verify the user is still active and admin-eligible.
     */
    public static function tryRememberMe(PDO $pdo): bool {
        if (self::isLoggedIn()) return true;
        if (empty($_COOKIE['cms_remember'])) return false;

        $parts = explode(':', $_COOKIE['cms_remember']);
        if (count($parts) !== 2) return false;

        [$selector, $validator] = $parts;

        $stmt = $pdo->prepare(
            "SELECT rt.user_id, rt.hashed_validator, u.username, u.display_name, u.email
             FROM auth_remember_tokens rt
             JOIN auth_users u ON u.id = rt.user_id
             JOIN auth_user_roles ur ON ur.user_id = u.id
             JOIN auth_roles r ON r.id = ur.role_id
             WHERE rt.selector = ?
               AND rt.expires_at > NOW()
               AND rt.portal_scope = 'admin'
               AND u.status = 'active'
               AND r.is_active = 1
               AND r.role_key IN ('admin','admin_manager','partner_user')
             GROUP BY rt.user_id, rt.hashed_validator, u.username, u.display_name, u.email
             LIMIT 1"
        );
        $stmt->execute([$selector]);
        $token = $stmt->fetch();

        if ($token && hash_equals($token['hashed_validator'], hash('sha256', $validator))) {
            $display = $token['username'] ?: ($token['display_name'] ?: $token['email']);
            self::login((int) $token['user_id'], (string) $display);

            // Rotate the token (best practice: invalidate old selector + issue new)
            $pdo->prepare("DELETE FROM auth_remember_tokens WHERE selector = ?")->execute([$selector]);
            self::setRememberToken($pdo, (int) $token['user_id']);

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
