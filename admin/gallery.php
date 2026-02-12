<?php
/**
 * CMS Admin - Gallery Management
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Gallery';
$adminCurrentPage = 'gallery';
$adminBreadcrumb = ['Gallery' => ''];

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax()) {
    require_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'reorder') {
        $ids = json_decode($_POST['ids'] ?? '[]', true);
        if (is_array($ids)) {
            $stmt = $pdo->prepare("UPDATE ml_cms_gallery SET sort_order = ? WHERE id = ?");
            foreach ($ids as $i => $id) {
                $stmt->execute([$i, (int)$id]);
            }
            json_response(['success' => true, 'message' => 'Order updated']);
        }
        json_response(['error' => 'Invalid data'], 400);
    }

    if ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $active = (int)($_POST['is_active'] ?? 0);
        $pdo->prepare("UPDATE ml_cms_gallery SET is_active = ? WHERE id = ?")->execute([$active, $id]);
        json_response(['success' => true, 'message' => $active ? 'Activated' : 'Deactivated']);
    }

    if ($action === 'toggle_featured') {
        $id = (int)($_POST['id'] ?? 0);
        $featured = (int)($_POST['is_featured'] ?? 0);
        $pdo->prepare("UPDATE ml_cms_gallery SET is_featured = ? WHERE id = ?")->execute([$featured, $id]);
        json_response(['success' => true, 'message' => $featured ? 'Marked as featured' : 'Unmarked']);
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        // Get the R2 key before deleting
        $item = $pdo->prepare("SELECT r2_key FROM ml_cms_gallery WHERE id = ?");
        $item->execute([$id]);
        $item = $item->fetch();

        // Delete from R2 if has key
        if ($item && !empty($item['r2_key'])) {
            require_once __DIR__ . '/includes/admin-r2.php';
            r2_delete_file($item['r2_key']);
        }

        $pdo->prepare("DELETE FROM ml_cms_gallery WHERE id = ?")->execute([$id]);
        json_response(['success' => true, 'message' => 'Gallery item deleted']);
    }
}

// Fetch gallery items with category filter
$filterCat = $_GET['category'] ?? '';
$categories = $pdo->query("SELECT * FROM ml_cms_gallery_categories ORDER BY sort_order ASC")->fetchAll();

if ($filterCat) {
    $stmt = $pdo->prepare("SELECT * FROM ml_cms_gallery WHERE category = ? ORDER BY sort_order ASC, id DESC");
    $stmt->execute([$filterCat]);
    $items = $stmt->fetchAll();
} else {
    $items = $pdo->query("SELECT * FROM ml_cms_gallery ORDER BY sort_order ASC, id DESC")->fetchAll();
}

$adminExtraJs = ['https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js'];
include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Gallery</h1>
        <p class="text-white/40 text-sm mt-1"><?= count($items) ?> items</p>
    </div>
    <a href="/admin/gallery-edit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Image
    </a>
</div>

<!-- Category Filter -->
<?php if (!empty($categories)): ?>
<div class="flex items-center gap-2 mb-6 flex-wrap">
    <a href="/admin/gallery" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= !$filterCat ? 'bg-primary text-dark' : 'bg-dark-300 text-white/50 hover:text-white' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
        <a href="/admin/gallery?category=<?= urlencode($cat['slug']) ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $filterCat === $cat['slug'] ? 'bg-primary text-dark' : 'bg-dark-300 text-white/50 hover:text-white' ?>"><?= e($cat['label']) ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($items)): ?>
    <div class="admin-card">
        <div class="admin-empty">
            <svg class="admin-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <h3 class="admin-empty-title">No gallery images yet</h3>
            <p class="admin-empty-text">Upload your first gallery image or run the seed script to import existing ones.</p>
            <a href="/admin/gallery-edit" class="admin-btn admin-btn-primary">Upload Image</a>
        </div>
    </div>
<?php else: ?>
    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="galleryGrid">
        <?php foreach ($items as $item): ?>
            <div class="gallery-admin-item group relative bg-dark-50 border border-white/5 rounded-xl overflow-hidden" data-id="<?= $item['id'] ?>">
                <!-- Drag Handle -->
                <div class="drag-handle absolute top-2 left-2 z-10 cursor-grab opacity-0 group-hover:opacity-100 transition bg-dark/80 rounded-lg p-1.5">
                    <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>

                <!-- Image -->
                <div class="aspect-square overflow-hidden">
                    <?php if ($item['image_url']): ?>
                        <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['alt_text'] ?? '') ?>" class="w-full h-full object-cover" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full bg-dark-300 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Overlay Actions -->
                <div class="absolute inset-0 bg-gradient-to-t from-dark/90 via-dark/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                    <div class="mb-2">
                        <?php if ($item['title']): ?>
                            <p class="text-white text-sm font-medium truncate"><?= e($item['title']) ?></p>
                        <?php endif; ?>
                        <?php if ($item['category']): ?>
                            <p class="text-white/50 text-xs"><?= e($item['category']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="/admin/gallery-edit?id=<?= $item['id'] ?>" class="flex-1 text-center text-xs bg-white/10 hover:bg-white/20 text-white rounded-lg py-1.5 transition">Edit</a>
                        <button onclick="toggleFeatured(<?= $item['id'] ?>, this)" class="p-1.5 rounded-lg transition <?= $item['is_featured'] ? 'bg-primary/20 text-primary' : 'bg-white/10 text-white/50' ?> hover:bg-primary/30" title="Featured">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                        <button onclick="deleteItem(<?= $item['id'] ?>)" class="p-1.5 rounded-lg bg-white/10 hover:bg-red-500/30 text-white/50 hover:text-red-400 transition" title="Delete">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Status indicators -->
                <?php if (!$item['is_active']): ?>
                    <div class="absolute top-2 right-2 bg-red-500/80 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Hidden</div>
                <?php endif; ?>
                <?php if ($item['is_featured']): ?>
                    <div class="absolute <?= !$item['is_active'] ? 'top-8' : 'top-2' ?> right-2 bg-primary/80 text-dark text-[10px] font-bold px-2 py-0.5 rounded-full">Featured</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('galleryGrid');
    if (grid && typeof Sortable !== 'undefined') {
        Sortable.create(grid, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                const ids = Array.from(grid.querySelectorAll('[data-id]')).map(el => el.dataset.id);
                const form = new FormData();
                form.append('action', 'reorder');
                form.append('ids', JSON.stringify(ids));
                form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
                fetch('/admin/gallery', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
                    .then(r => r.json())
                    .then(d => showToast(d.message || 'Order updated'))
                    .catch(() => showToast('Reorder failed', 'error'));
            }
        });
    }
});

function toggleFeatured(id, el) {
    const isFeatured = el.classList.contains('text-primary') ? 0 : 1;
    el.classList.toggle('bg-primary/20');
    el.classList.toggle('text-primary');
    el.classList.toggle('bg-white/10');
    el.classList.toggle('text-white/50');
    const form = new FormData();
    form.append('action', 'toggle_featured');
    form.append('id', id);
    form.append('is_featured', isFeatured);
    form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('/admin/gallery', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
        .then(r => r.json())
        .then(d => showToast(d.message))
        .catch(() => showToast('Update failed', 'error'));
}

function deleteItem(id) {
    showConfirm('Delete Image', 'Are you sure you want to delete this gallery image? This will also remove it from cloud storage.', function() {
        const form = new FormData();
        form.append('action', 'delete');
        form.append('id', id);
        form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        fetch('/admin/gallery', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
            .then(r => r.json())
            .then(d => { showToast(d.message); const el = document.querySelector('[data-id="'+id+'"]'); if(el) { el.style.opacity='0'; el.style.transform='scale(0.8)'; setTimeout(()=>el.remove(), 300); }})
            .catch(() => showToast('Delete failed', 'error'));
    });
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
