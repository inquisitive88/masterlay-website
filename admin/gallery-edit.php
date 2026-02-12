<?php
/**
 * CMS Admin - Gallery Item Create/Edit
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';
require_once __DIR__ . '/includes/admin-r2.php';

$id = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$item = null;

if ($isEdit) {
    $item = $pdo->prepare("SELECT * FROM ml_cms_gallery WHERE id = ?");
    $item->execute([$id]);
    $item = $item->fetch();
    if (!$item) {
        redirect('/admin/gallery', 'error', 'Gallery item not found.');
    }
}

$adminPageTitle = $isEdit ? 'Edit Gallery Item' : 'Add Gallery Item';
$adminCurrentPage = 'gallery';
$adminBreadcrumb = ['Gallery' => '/admin/gallery', $adminPageTitle => ''];

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM ml_cms_gallery_categories ORDER BY sort_order ASC")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $data = [
        'title'       => trim($_POST['title'] ?? ''),
        'category'    => trim($_POST['category'] ?? ''),
        'type_label'  => trim($_POST['type_label'] ?? ''),
        'alt_text'    => trim($_POST['alt_text'] ?? ''),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active'   => isset($_POST['is_active']) ? 1 : 0,
    ];

    $imageUrl = $isEdit ? $item['image_url'] : '';
    $r2Key = $isEdit ? ($item['r2_key'] ?? '') : '';

    // Handle file upload to R2
    if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = r2_upload_file($_FILES['image']);
        if ($upload['success']) {
            // Delete old R2 file if replacing
            if ($isEdit && !empty($item['r2_key'])) {
                r2_delete_file($item['r2_key']);
            }
            $imageUrl = $upload['url'];
            $r2Key = $upload['key'];
        } else {
            set_flash('error', 'Image upload failed: ' . $upload['error']);
        }
    } elseif (!empty($_POST['image_url'])) {
        // Allow manual URL input as fallback
        $imageUrl = trim($_POST['image_url']);
        $r2Key = '';
    }

    $data['image_url'] = $imageUrl;
    $data['r2_key'] = $r2Key;

    if (empty($data['image_url']) && !$isEdit) {
        set_flash('error', 'An image is required.');
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE ml_cms_gallery SET title=?, category=?, type_label=?, alt_text=?, image_url=?, r2_key=?, is_featured=?, is_active=? WHERE id=?");
            $stmt->execute([
                $data['title'], $data['category'], $data['type_label'], $data['alt_text'],
                $data['image_url'], $data['r2_key'], $data['is_featured'], $data['is_active'], $id
            ]);
            redirect('/admin/gallery', 'success', 'Gallery item updated.');
        } else {
            $maxOrder = (int)$pdo->query("SELECT MAX(sort_order) FROM ml_cms_gallery")->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO ml_cms_gallery (title, category, type_label, alt_text, image_url, r2_key, is_featured, is_active, sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $data['title'], $data['category'], $data['type_label'], $data['alt_text'],
                $data['image_url'], $data['r2_key'], $data['is_featured'], $data['is_active'], $maxOrder + 1
            ]);
            redirect('/admin/gallery', 'success', 'Gallery item created.');
        }
    }
}

// Use POST data on error, or existing data
$form = $item ?? [
    'title' => '', 'category' => '', 'type_label' => '', 'alt_text' => '',
    'image_url' => '', 'r2_key' => '', 'is_featured' => 0, 'is_active' => 1,
];

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white"><?= $isEdit ? 'Edit Gallery Item' : 'Add Gallery Item' ?></h1>
    </div>
    <a href="/admin/gallery" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Gallery
    </a>
</div>

<form method="POST" action="/admin/gallery-edit<?= $isEdit ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Image Upload -->
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Image</h2>

                <!-- Current Image Preview -->
                <?php if ($form['image_url']): ?>
                    <div class="mb-4">
                        <img src="<?= e($form['image_url']) ?>" alt="<?= e($form['alt_text'] ?? '') ?>" class="w-full max-h-80 object-contain rounded-xl bg-dark-300" id="currentImage">
                        <?php if (!empty($form['r2_key'])): ?>
                            <p class="text-white/30 text-xs mt-2">R2 Key: <?= e($form['r2_key']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Upload Area -->
                <div class="admin-upload-area" id="uploadArea">
                    <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/webp" class="hidden">
                    <svg class="w-10 h-10 text-white/20 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-white/40 text-sm mb-1"><?= $isEdit ? 'Drop a new image to replace' : 'Drop an image here or click to upload' ?></p>
                    <p class="text-white/20 text-xs">JPG, PNG, or WebP &mdash; Max 10MB</p>
                    <button type="button" onclick="document.getElementById('imageInput').click()" class="admin-btn admin-btn-secondary admin-btn-sm mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Choose File
                    </button>
                    <div id="uploadPreview" class="hidden mt-4">
                        <img id="previewImg" src="" alt="" class="max-h-48 rounded-lg mx-auto">
                        <p id="previewName" class="text-white/50 text-xs text-center mt-2"></p>
                    </div>
                </div>

                <!-- Manual URL fallback -->
                <div class="mt-4">
                    <details class="group">
                        <summary class="text-white/30 text-xs cursor-pointer hover:text-white/50 transition">Or enter image URL manually</summary>
                        <div class="mt-2">
                            <input type="url" name="image_url" value="<?= e($form['image_url']) ?>" class="admin-form-input" placeholder="https://...">
                        </div>
                    </details>
                </div>
            </div>

            <!-- Details -->
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Details</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" class="admin-form-input" placeholder="e.g. Modern Kitchen Renovation">
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="alt_text">Alt Text</label>
                    <input type="text" id="alt_text" name="alt_text" value="<?= e($form['alt_text']) ?>" class="admin-form-input" placeholder="Describe the image for accessibility">
                    <p class="admin-form-help">Important for SEO and accessibility.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-form-group mb-0">
                        <label class="admin-form-label" for="category">Category</label>
                        <select id="category" name="category" class="admin-form-select">
                            <option value="">No category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= e($cat['slug']) ?>" <?= ($form['category'] ?? '') === $cat['slug'] ? 'selected' : '' ?>><?= e($cat['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-form-group mb-0">
                        <label class="admin-form-label" for="type_label">Type Label</label>
                        <input type="text" id="type_label" name="type_label" value="<?= e($form['type_label']) ?>" class="admin-form-input" placeholder="e.g. Before & After">
                        <p class="admin-form-help">Optional label shown on the image overlay.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Options</h2>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?= $form['is_active'] ? 'checked' : '' ?> class="w-4 h-4 rounded border-white/20 bg-dark-100 text-primary focus:ring-primary/30">
                        <span class="text-white/70 text-sm">Active (visible in gallery)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" <?= $form['is_featured'] ? 'checked' : '' ?> class="w-4 h-4 rounded border-white/20 bg-dark-100 text-primary focus:ring-primary/30">
                        <span class="text-white/70 text-sm">Featured (shown on homepage)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="admin-btn admin-btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <?= $isEdit ? 'Update' : 'Upload' ?> Image
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// File input preview
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const preview = document.getElementById('uploadPreview');
        const img = document.getElementById('previewImg');
        const name = document.getElementById('previewName');
        const reader = new FileReader();
        reader.onload = function(ev) {
            img.src = ev.target.result;
            name.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Drag and drop
const uploadArea = document.getElementById('uploadArea');
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evtName => {
    uploadArea.addEventListener(evtName, e => { e.preventDefault(); e.stopPropagation(); });
});
['dragenter', 'dragover'].forEach(evtName => {
    uploadArea.addEventListener(evtName, () => uploadArea.classList.add('border-primary'));
});
['dragleave', 'drop'].forEach(evtName => {
    uploadArea.addEventListener(evtName, () => uploadArea.classList.remove('border-primary'));
});
uploadArea.addEventListener('drop', function(e) {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('imageInput').files = files;
        document.getElementById('imageInput').dispatchEvent(new Event('change'));
    }
});
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
