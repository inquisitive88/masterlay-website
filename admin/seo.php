<?php
/**
 * CMS Admin - SEO Management
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'SEO';
$adminCurrentPage = 'seo';
$adminBreadcrumb = ['SEO' => ''];

// Fetch all SEO entries
$seoPages = $pdo->query("SELECT * FROM ml_cms_seo ORDER BY page_identifier ASC")->fetchAll();

// Define all known pages
$knownPages = [
    'index'        => 'Homepage',
    'about'        => 'About Us',
    'services'     => 'Services',
    'gallery'      => 'Gallery',
    'contact'      => 'Contact',
    'financing'    => 'Financing',
    'testimonials' => 'Testimonials',
    'blog'         => 'Blog',
    'faq'          => 'FAQ',
];

// Map existing SEO data
$seoMap = [];
foreach ($seoPages as $s) {
    $seoMap[$s['page_identifier']] = $s;
}

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">SEO Management</h1>
        <p class="text-white/40 text-sm mt-1">Manage meta titles, descriptions, and Open Graph data per page</p>
    </div>
</div>

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Meta Title</th>
                    <th>Meta Description</th>
                    <th class="w-20">Status</th>
                    <th class="w-24 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($knownPages as $identifier => $label): ?>
                    <?php $seo = $seoMap[$identifier] ?? null; ?>
                    <tr>
                        <td>
                            <span class="text-white font-medium"><?= e($label) ?></span>
                            <p class="text-white/30 text-xs mt-0.5">/<?= $identifier === 'index' ? '' : $identifier ?></p>
                        </td>
                        <td>
                            <?php if ($seo && $seo['meta_title']): ?>
                                <span class="text-white/60 text-sm"><?= e(truncate($seo['meta_title'], 50)) ?></span>
                            <?php else: ?>
                                <span class="text-white/20 text-sm italic">Not set</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($seo && $seo['meta_description']): ?>
                                <span class="text-white/60 text-sm"><?= e(truncate($seo['meta_description'], 60)) ?></span>
                            <?php else: ?>
                                <span class="text-white/20 text-sm italic">Not set</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($seo): ?>
                                <span class="admin-badge admin-badge-success">Set</span>
                            <?php else: ?>
                                <span class="admin-badge admin-badge-warning">Empty</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <a href="/admin/seo-edit?page=<?= $identifier ?>" class="admin-btn admin-btn-secondary admin-btn-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
