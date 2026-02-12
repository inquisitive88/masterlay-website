<?php
/**
 * CMS Admin - Login Page
 */
define('CMS_ADMIN', true);
require_once __DIR__ . '/includes/admin-bootstrap.php';

// If already logged in, redirect to dashboard
CmsAuth::start();
if (CmsAuth::isLoggedIn()) {
    header('Location: /admin');
    exit;
}

// Try remember-me auto-login
if (CmsAuth::tryRememberMe($pdo)) {
    header('Location: /admin');
    exit;
}

$error = '';
$username = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM ml_admin_users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            CmsAuth::login($user['id'], $user['username']);

            if ($remember) {
                CmsAuth::setRememberToken($pdo, $user['id']);
            }

            header('Location: /admin');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

// Check if any admin users exist
$adminCount = $pdo->query("SELECT COUNT(*) FROM ml_admin_users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Masterlay CMS</title>
    <link rel="icon" type="image/x-icon" href="<?= CMS_IMG ?>/icons/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#FAA416', light: '#FDB844', dark: '#D88A0A' },
                        dark: { DEFAULT: '#0A0A0A', 50: '#1A1A1A', 100: '#141414', 200: '#1E1E1E', 300: '#2A2A2A', 400: '#3A3A3A' },
                    },
                    fontFamily: { heading: ['Syne', 'sans-serif'], body: ['Inter', 'sans-serif'] },
                },
            },
        }
    </script>
    <link rel="stylesheet" href="/admin/assets/css/admin.css?v=<?= time() ?>">
</head>
<body class="font-body">
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="login-logo">
                <img src="<?= CMS_IMG ?>/logos/icon.png" alt="Masterlay" class="w-12 h-12 rounded-xl">
                <div>
                    <h1 class="font-heading font-bold text-xl text-white leading-tight">Masterlay</h1>
                    <p class="text-white/40 text-xs">Content Management</p>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 mb-5 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span class="text-red-400 text-sm"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($adminCount == 0): ?>
                <div class="bg-primary/10 border border-primary/20 rounded-xl p-3 mb-5">
                    <p class="text-primary text-sm">No admin accounts exist yet. Create one using the estimator portal's registration page, or run the seed script.</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login" class="space-y-5">
                <div>
                    <label class="admin-form-label" for="username">Username or Email</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= htmlspecialchars($username) ?>"
                        class="admin-form-input"
                        placeholder="Enter your username"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label class="admin-form-label" for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="admin-form-input"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-dark-100 text-primary focus:ring-primary/30 focus:ring-2">
                        <span class="text-white/50 text-sm">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="admin-btn admin-btn-primary w-full justify-center py-2.5 text-base">
                    Sign In
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <p class="text-center text-white/20 text-xs mt-6">
                Masterlay Renovations Inc. &copy; <?= date('Y') ?>
            </p>
        </div>
    </div>
</body>
</html>
