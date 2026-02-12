<?php
/**
 * CMS Admin - Dashboard
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Dashboard';
$adminCurrentPage = 'index';
$adminBreadcrumb = ['Dashboard' => ''];

// Fetch counts for stat cards
$stats = [];
$statQueries = [
    'services'     => "SELECT COUNT(*) FROM ml_cms_services",
    'gallery'      => "SELECT COUNT(*) FROM ml_cms_gallery",
    'testimonials' => "SELECT COUNT(*) FROM ml_cms_testimonials",
    'faqs'         => "SELECT COUNT(*) FROM ml_cms_faqs",
    'blog_posts'   => "SELECT COUNT(*) FROM ml_cms_blog_posts",
    'published'    => "SELECT COUNT(*) FROM ml_cms_blog_posts WHERE status = 'published'",
];

foreach ($statQueries as $key => $query) {
    try {
        $stats[$key] = (int)$pdo->query($query)->fetchColumn();
    } catch (PDOException $e) {
        $stats[$key] = 0;
    }
}

include __DIR__ . '/includes/admin-layout-top.php';
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-white/40 text-sm mt-1">Welcome back, <?= e(CmsAuth::getUsername() ?? 'Admin') ?></p>
    </div>
    <div class="flex items-center gap-3">
        <a href="/admin/seed" class="admin-btn admin-btn-secondary admin-btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Seed Data
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
    <!-- Services -->
    <a href="/admin/services" class="admin-stat-card hover:border-primary/20 transition-colors group">
        <div class="admin-stat-icon bg-blue-500/10">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
            <div class="admin-stat-value"><?= $stats['services'] ?></div>
            <div class="admin-stat-label">Services</div>
        </div>
    </a>

    <!-- Gallery -->
    <a href="/admin/gallery" class="admin-stat-card hover:border-primary/20 transition-colors group">
        <div class="admin-stat-icon bg-purple-500/10">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div class="admin-stat-value"><?= $stats['gallery'] ?></div>
            <div class="admin-stat-label">Gallery Items</div>
        </div>
    </a>

    <!-- Testimonials -->
    <a href="/admin/testimonials" class="admin-stat-card hover:border-primary/20 transition-colors group">
        <div class="admin-stat-icon bg-green-500/10">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div>
            <div class="admin-stat-value"><?= $stats['testimonials'] ?></div>
            <div class="admin-stat-label">Testimonials</div>
        </div>
    </a>

    <!-- FAQs -->
    <a href="/admin/faqs" class="admin-stat-card hover:border-primary/20 transition-colors group">
        <div class="admin-stat-icon bg-yellow-500/10">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="admin-stat-value"><?= $stats['faqs'] ?></div>
            <div class="admin-stat-label">FAQs</div>
        </div>
    </a>

    <!-- Blog Posts -->
    <a href="/admin/blog" class="admin-stat-card hover:border-primary/20 transition-colors group">
        <div class="admin-stat-icon bg-rose-500/10">
            <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
        <div>
            <div class="admin-stat-value"><?= $stats['blog_posts'] ?></div>
            <div class="admin-stat-label">Blog Posts</div>
        </div>
    </a>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Quick Links -->
    <div class="admin-card">
        <h2 class="admin-card-title mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="/admin/gallery-edit" class="flex items-center gap-3 p-3 rounded-lg bg-dark-100 border border-white/5 hover:border-primary/20 transition group">
                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/70 group-hover:text-white transition">Add Gallery Image</p>
                </div>
            </a>
            <a href="/admin/blog-edit" class="flex items-center gap-3 p-3 rounded-lg bg-dark-100 border border-white/5 hover:border-primary/20 transition group">
                <div class="w-10 h-10 rounded-lg bg-rose-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/70 group-hover:text-white transition">Write Blog Post</p>
                </div>
            </a>
            <a href="/admin/testimonials-edit" class="flex items-center gap-3 p-3 rounded-lg bg-dark-100 border border-white/5 hover:border-primary/20 transition group">
                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/70 group-hover:text-white transition">Add Testimonial</p>
                </div>
            </a>
            <a href="/admin/settings" class="flex items-center gap-3 p-3 rounded-lg bg-dark-100 border border-white/5 hover:border-primary/20 transition group">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white/70 group-hover:text-white transition">Site Settings</p>
                </div>
            </a>
        </div>
    </div>

    <!-- System Info -->
    <div class="admin-card">
        <h2 class="admin-card-title mb-4">System Info</h2>
        <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-white/40 text-sm">Environment</span>
                <span class="admin-badge <?= CMS_APP_ENV === 'prod' ? 'admin-badge-success' : (CMS_APP_ENV === 'dev' ? 'admin-badge-warning' : 'admin-badge-info') ?>"><?= strtoupper(CMS_APP_ENV) ?></span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-white/40 text-sm">PHP Version</span>
                <span class="text-white/60 text-sm"><?= PHP_VERSION ?></span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-white/40 text-sm">Database</span>
                <span class="text-white/60 text-sm"><?= $cms_db_name ?? 'Connected' ?></span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-white/40 text-sm">Image CDN</span>
                <span class="text-white/60 text-sm truncate max-w-[200px]">Cloudflare R2</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-white/40 text-sm">Published Blog Posts</span>
                <span class="text-white/60 text-sm"><?= $stats['published'] ?></span>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
