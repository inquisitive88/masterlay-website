<?php
/**
 * CMS Admin - Sidebar Navigation
 */

$sidebarItems = [
    ['label' => 'Dashboard', 'url' => '/admin', 'page' => 'index', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    'divider',
    ['label' => 'Services', 'url' => '/admin/services', 'page' => 'services', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
    ['label' => 'Gallery', 'url' => '/admin/gallery', 'page' => 'gallery', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ['label' => 'Testimonials', 'url' => '/admin/testimonials', 'page' => 'testimonials', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
    ['label' => 'FAQs', 'url' => '/admin/faqs', 'page' => 'faqs', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['label' => 'Blog Posts', 'url' => '/admin/blog', 'page' => 'blog', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
    'divider',
    ['label' => 'SEO', 'url' => '/admin/seo', 'page' => 'seo', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    ['label' => 'Settings', 'url' => '/admin/settings', 'page' => 'settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    'divider',
    ['label' => 'View Website', 'url' => '/', 'page' => '_external', 'icon' => 'M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14', 'external' => true],
];
?>

<aside id="adminSidebar" class="admin-sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="/admin" class="flex items-center gap-3">
            <img src="<?= CMS_IMG ?>/logos/icon.png" alt="Masterlay" class="w-9 h-9 rounded-lg">
            <span class="font-heading font-bold text-white text-lg sidebar-label">Admin</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <?php foreach ($sidebarItems as $item): ?>
            <?php if ($item === 'divider'): ?>
                <div class="sidebar-divider"></div>
            <?php else:
                $isActive = false;
                // Check if current page matches
                if ($item['page'] === 'index' && $adminCurrentPage === 'index') {
                    $isActive = true;
                } elseif ($item['page'] !== 'index' && $item['page'] !== '_external') {
                    $isActive = strpos($adminCurrentPage, $item['page']) === 0;
                }
                $activeClass = $isActive ? 'sidebar-link--active' : '';
                $target = !empty($item['external']) ? ' target="_blank" rel="noopener"' : '';
            ?>
                <a href="<?= $item['url'] ?>" class="sidebar-link <?= $activeClass ?>"<?= $target ?>>
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $item['icon'] ?>"/>
                    </svg>
                    <span class="sidebar-label"><?= $item['label'] ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <!-- Bottom: Logout -->
    <div class="sidebar-bottom">
        <div class="sidebar-divider"></div>
        <div class="sidebar-user">
            <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                <span class="text-primary text-sm font-bold"><?= strtoupper(substr(CmsAuth::getUsername() ?? 'A', 0, 1)) ?></span>
            </div>
            <span class="sidebar-label text-sm text-white/60"><?= e(CmsAuth::getUsername() ?? 'Admin') ?></span>
        </div>
        <a href="/admin/logout" class="sidebar-link text-red-400/70 hover:text-red-400">
            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="sidebar-label">Logout</span>
        </a>
    </div>
</aside>
