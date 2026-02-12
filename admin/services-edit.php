<?php
/**
 * CMS Admin - Service Create/Edit
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$id = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$service = null;

if ($isEdit) {
    $service = $pdo->prepare("SELECT * FROM ml_cms_services WHERE id = ?");
    $service->execute([$id]);
    $service = $service->fetch();
    if (!$service) {
        redirect('/admin/services', 'error', 'Service not found.');
    }
}

$adminPageTitle = $isEdit ? 'Edit Service' : 'Add Service';
$adminCurrentPage = 'services';
$adminBreadcrumb = ['Services' => '/admin/services', $adminPageTitle => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $data = [
        'slug'              => slugify(trim($_POST['slug'] ?? '')),
        'title'             => trim($_POST['title'] ?? ''),
        'short_description' => trim($_POST['short_description'] ?? ''),
        'full_description'  => trim($_POST['full_description'] ?? ''),
        'icon_svg_path'     => trim($_POST['icon_svg_path'] ?? ''),
        'image_url'         => trim($_POST['image_url'] ?? ''),
        'hero_image_url'    => trim($_POST['hero_image_url'] ?? ''),
        'is_active'         => isset($_POST['is_active']) ? 1 : 0,
    ];

    // Validation
    $errors = [];
    if (empty($data['title'])) $errors[] = 'Title is required.';
    if (empty($data['slug'])) $data['slug'] = slugify($data['title']);

    // Check slug uniqueness
    $slugCheck = $pdo->prepare("SELECT id FROM ml_cms_services WHERE slug = ? AND id != ?");
    $slugCheck->execute([$data['slug'], $id]);
    if ($slugCheck->fetch()) $errors[] = 'A service with this slug already exists.';

    if (!empty($errors)) {
        set_flash('error', implode(' ', $errors));
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE ml_cms_services SET slug=?, title=?, short_description=?, full_description=?, icon_svg_path=?, image_url=?, hero_image_url=?, is_active=? WHERE id=?");
            $stmt->execute([
                $data['slug'], $data['title'], $data['short_description'], $data['full_description'],
                $data['icon_svg_path'], $data['image_url'], $data['hero_image_url'], $data['is_active'], $id
            ]);
            redirect('/admin/services', 'success', 'Service updated successfully.');
        } else {
            $maxOrder = (int)$pdo->query("SELECT MAX(sort_order) FROM ml_cms_services")->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO ml_cms_services (slug, title, short_description, full_description, icon_svg_path, image_url, hero_image_url, is_active, sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $data['slug'], $data['title'], $data['short_description'], $data['full_description'],
                $data['icon_svg_path'], $data['image_url'], $data['hero_image_url'], $data['is_active'], $maxOrder + 1
            ]);
            redirect('/admin/services', 'success', 'Service created successfully.');
        }
    }
}

// Use POST data on error, or existing service data
$form = $service ?? [
    'slug' => '', 'title' => '', 'short_description' => '', 'full_description' => '',
    'icon_svg_path' => '', 'image_url' => '', 'hero_image_url' => '', 'is_active' => 1,
];

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white"><?= $isEdit ? 'Edit Service' : 'Add Service' ?></h1>
    </div>
    <div class="flex items-center gap-3">
        <?php if ($isEdit): ?>
            <a href="/admin/service-sections?id=<?= $id ?>" class="admin-btn admin-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Edit Page Content
            </a>
        <?php endif; ?>
        <a href="/admin/services" class="admin-btn admin-btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Services
        </a>
    </div>
</div>

<form method="POST" action="/admin/services-edit<?= $isEdit ? '?id=' . $id : '' ?>">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Service Details</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" class="admin-form-input" required placeholder="e.g. Floor Installation">
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" class="admin-form-input" placeholder="Auto-generated from title">
                    <p class="admin-form-help">URL-friendly identifier. Leave blank to auto-generate.</p>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="short_description">Short Description</label>
                    <textarea id="short_description" name="short_description" class="admin-form-textarea" rows="3" placeholder="Brief description shown on cards"><?= e($form['short_description']) ?></textarea>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="full_description">Full Description</label>
                    <textarea id="full_description" name="full_description" class="admin-form-textarea" rows="6" placeholder="Detailed description for the service page"><?= e($form['full_description']) ?></textarea>
                </div>
            </div>

            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Icon</h2>
                <div class="admin-form-group mb-0">
                    <label class="admin-form-label" for="icon_svg_path">SVG Path Data</label>
                    <textarea id="icon_svg_path" name="icon_svg_path" class="admin-form-textarea font-mono text-xs" rows="3" placeholder="e.g. M3 12l2-2m0 0l7-7 7 7..."><?= e($form['icon_svg_path']) ?></textarea>
                    <p class="admin-form-help">The SVG path 'd' attribute for the service icon.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Images</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="image_url">Card Image URL</label>
                    <input type="url" id="image_url" name="image_url" value="<?= e($form['image_url']) ?>" class="admin-form-input" placeholder="https://...">
                    <?php if ($form['image_url']): ?>
                        <img src="<?= e($form['image_url']) ?>" alt="" class="mt-2 w-full h-32 object-cover rounded-lg">
                    <?php endif; ?>
                </div>

                <div class="admin-form-group mb-0">
                    <label class="admin-form-label" for="hero_image_url">Hero Image URL</label>
                    <input type="url" id="hero_image_url" name="hero_image_url" value="<?= e($form['hero_image_url']) ?>" class="admin-form-input" placeholder="https://...">
                    <?php if ($form['hero_image_url']): ?>
                        <img src="<?= e($form['hero_image_url']) ?>" alt="" class="mt-2 w-full h-32 object-cover rounded-lg">
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Status</h2>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= $form['is_active'] ? 'checked' : '' ?> class="w-4 h-4 rounded border-white/20 bg-dark-100 text-primary focus:ring-primary/30">
                    <span class="text-white/70 text-sm">Active (visible on website)</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="admin-btn admin-btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <?= $isEdit ? 'Update' : 'Create' ?> Service
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.value || slugField.dataset.auto === '1') {
        slugField.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-').replace(/^-|-$/g, '');
        slugField.dataset.auto = '1';
    }
});
document.getElementById('slug').addEventListener('input', function() {
    this.dataset.auto = '0';
});
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
