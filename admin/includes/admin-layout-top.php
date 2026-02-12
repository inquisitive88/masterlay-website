<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle ?? 'Admin') ?> - Masterlay CMS</title>
    <?= csrf_meta() ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= CMS_IMG ?>/icons/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#FAA416', light: '#FDB844', dark: '#D88A0A' },
                        dark: { DEFAULT: '#0A0A0A', 50: '#1A1A1A', 100: '#141414', 200: '#1E1E1E', 300: '#2A2A2A', 400: '#3A3A3A' },
                    },
                    fontFamily: {
                        heading: ['Syne', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <!-- Admin CSS -->
    <link rel="stylesheet" href="/admin/assets/css/admin.css?v=<?= time() ?>">
</head>
<body class="bg-dark text-white font-body antialiased">

    <?php include __DIR__ . '/admin-sidebar.php'; ?>

    <div class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <button id="sidebarToggle" class="admin-topbar-toggle">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm">
                <a href="/admin" class="text-white/40 hover:text-white transition">Admin</a>
                <?php if (!empty($adminBreadcrumb)): ?>
                    <?php foreach ($adminBreadcrumb as $label => $url): ?>
                        <span class="text-white/20">/</span>
                        <?php if ($url): ?>
                            <a href="<?= $url ?>" class="text-white/40 hover:text-white transition"><?= e($label) ?></a>
                        <?php else: ?>
                            <span class="text-white/70"><?= e($label) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="ml-auto flex items-center gap-4">
                <a href="/" target="_blank" class="text-white/40 hover:text-white text-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Site
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="admin-content-wrapper">
            <?= render_flash_messages() ?>

            <!-- Page Content Start -->
