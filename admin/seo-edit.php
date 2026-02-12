<?php
/**
 * CMS Admin - SEO Edit per Page
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$pageId = $_GET['page'] ?? '';
$knownPages = [
    'index' => 'Homepage', 'about' => 'About Us', 'services' => 'Services',
    'gallery' => 'Gallery', 'contact' => 'Contact', 'financing' => 'Financing',
    'testimonials' => 'Testimonials', 'blog' => 'Blog', 'faq' => 'FAQ',
];

if (!isset($knownPages[$pageId])) {
    redirect('/admin/seo', 'error', 'Unknown page.');
}

$adminPageTitle = 'SEO - ' . $knownPages[$pageId];
$adminCurrentPage = 'seo';
$adminBreadcrumb = ['SEO' => '/admin/seo', $knownPages[$pageId] => ''];

// Load existing SEO data
$stmt = $pdo->prepare("SELECT * FROM ml_cms_seo WHERE page_identifier = ?");
$stmt->execute([$pageId]);
$seo = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $data = [
        'meta_title'       => trim($_POST['meta_title'] ?? ''),
        'meta_description' => trim($_POST['meta_description'] ?? ''),
        'og_title'         => trim($_POST['og_title'] ?? ''),
        'og_description'   => trim($_POST['og_description'] ?? ''),
        'og_image_url'     => trim($_POST['og_image_url'] ?? ''),
        'canonical_url'    => trim($_POST['canonical_url'] ?? ''),
    ];

    $stmt = $pdo->prepare("INSERT INTO ml_cms_seo (page_identifier, meta_title, meta_description, og_title, og_description, og_image_url, canonical_url)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE meta_title=VALUES(meta_title), meta_description=VALUES(meta_description),
        og_title=VALUES(og_title), og_description=VALUES(og_description), og_image_url=VALUES(og_image_url), canonical_url=VALUES(canonical_url)");
    $stmt->execute([$pageId, $data['meta_title'], $data['meta_description'], $data['og_title'], $data['og_description'], $data['og_image_url'], $data['canonical_url']]);

    redirect('/admin/seo', 'success', 'SEO updated for ' . $knownPages[$pageId] . '.');
}

$form = $seo ?? [
    'meta_title' => '', 'meta_description' => '', 'og_title' => '',
    'og_description' => '', 'og_image_url' => '', 'canonical_url' => '',
];

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <h1 class="font-heading text-2xl font-bold text-white">SEO: <?= e($knownPages[$pageId]) ?></h1>
    <a href="/admin/seo" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<form method="POST" action="/admin/seo-edit?page=<?= e($pageId) ?>">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Meta Tags -->
        <div class="admin-card">
            <h2 class="admin-card-title mb-5">Meta Tags</h2>

            <div class="admin-form-group">
                <label class="admin-form-label">Meta Title</label>
                <input type="text" name="meta_title" value="<?= e($form['meta_title']) ?>" class="admin-form-input" placeholder="Page title for search engines" maxlength="300">
                <p class="admin-form-help">Recommended: 50-60 characters. <span id="titleCount" class="text-primary">0</span>/60</p>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Meta Description</label>
                <textarea name="meta_description" class="admin-form-textarea" rows="3" placeholder="Page description for search engines" maxlength="500"><?= e($form['meta_description']) ?></textarea>
                <p class="admin-form-help">Recommended: 150-160 characters. <span id="descCount" class="text-primary">0</span>/160</p>
            </div>

            <div class="admin-form-group mb-0">
                <label class="admin-form-label">Canonical URL</label>
                <input type="url" name="canonical_url" value="<?= e($form['canonical_url']) ?>" class="admin-form-input" placeholder="https://masterlayrenovations.ca/<?= $pageId === 'index' ? '' : $pageId ?>">
                <p class="admin-form-help">Leave blank to use the default page URL.</p>
            </div>
        </div>

        <!-- Open Graph -->
        <div class="admin-card">
            <h2 class="admin-card-title mb-5">Open Graph (Social Sharing)</h2>

            <div class="admin-form-group">
                <label class="admin-form-label">OG Title</label>
                <input type="text" name="og_title" value="<?= e($form['og_title']) ?>" class="admin-form-input" placeholder="Leave blank to use Meta Title">
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">OG Description</label>
                <textarea name="og_description" class="admin-form-textarea" rows="3" placeholder="Leave blank to use Meta Description"><?= e($form['og_description']) ?></textarea>
            </div>

            <div class="admin-form-group mb-0">
                <label class="admin-form-label">OG Image URL</label>
                <input type="url" name="og_image_url" value="<?= e($form['og_image_url']) ?>" class="admin-form-input" placeholder="https://...">
                <?php if ($form['og_image_url']): ?>
                    <img src="<?= e($form['og_image_url']) ?>" alt="" class="mt-2 w-full h-32 object-cover rounded-lg">
                <?php endif; ?>
                <p class="admin-form-help">Recommended: 1200x630px</p>
            </div>
        </div>
    </div>

    <!-- Preview -->
    <div class="admin-card mt-6">
        <h2 class="admin-card-title mb-4">Search Preview</h2>
        <div class="bg-white rounded-lg p-4 max-w-xl">
            <p id="previewTitle" class="text-blue-700 text-lg font-medium leading-tight" style="font-family: Arial, sans-serif;"><?= e($form['meta_title'] ?: $knownPages[$pageId] . ' - Masterlay Renovations') ?></p>
            <p class="text-green-700 text-sm mt-1" style="font-family: Arial, sans-serif;">masterlayrenovations.ca/<?= $pageId === 'index' ? '' : $pageId ?></p>
            <p id="previewDesc" class="text-gray-600 text-sm mt-1 line-clamp-2" style="font-family: Arial, sans-serif;"><?= e($form['meta_description'] ?: 'Add a meta description to preview how this page appears in search results.') ?></p>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="admin-btn admin-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save SEO Settings
        </button>
    </div>
</form>

<script>
// Character counters
const titleInput = document.querySelector('input[name="meta_title"]');
const descInput = document.querySelector('textarea[name="meta_description"]');
const titleCount = document.getElementById('titleCount');
const descCount = document.getElementById('descCount');
const previewTitle = document.getElementById('previewTitle');
const previewDesc = document.getElementById('previewDesc');

function updateCounts() {
    titleCount.textContent = titleInput.value.length;
    descCount.textContent = descInput.value.length;
    titleCount.style.color = titleInput.value.length > 60 ? '#ef4444' : '#FAA416';
    descCount.style.color = descInput.value.length > 160 ? '#ef4444' : '#FAA416';
    previewTitle.textContent = titleInput.value || '<?= e($knownPages[$pageId]) ?> - Masterlay Renovations';
    previewDesc.textContent = descInput.value || 'Add a meta description...';
}

titleInput.addEventListener('input', updateCounts);
descInput.addEventListener('input', updateCounts);
updateCounts();
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
