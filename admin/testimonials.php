<?php
/**
 * CMS Admin - Testimonials List
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Testimonials';
$adminCurrentPage = 'testimonials';
$adminBreadcrumb = ['Testimonials' => ''];

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax()) {
    require_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'reorder') {
        $ids = json_decode($_POST['ids'] ?? '[]', true);
        if (is_array($ids)) {
            $stmt = $pdo->prepare("UPDATE ml_cms_testimonials SET sort_order = ? WHERE id = ?");
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
        $pdo->prepare("UPDATE ml_cms_testimonials SET is_active = ? WHERE id = ?")->execute([$active, $id]);
        json_response(['success' => true, 'message' => $active ? 'Activated' : 'Deactivated']);
    }

    if ($action === 'toggle_featured') {
        $id = (int)($_POST['id'] ?? 0);
        $featured = (int)($_POST['is_featured'] ?? 0);
        $pdo->prepare("UPDATE ml_cms_testimonials SET is_featured = ? WHERE id = ?")->execute([$featured, $id]);
        json_response(['success' => true, 'message' => $featured ? 'Marked as featured' : 'Unmarked as featured']);
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare("DELETE FROM ml_cms_testimonials WHERE id = ?")->execute([$id]);
        json_response(['success' => true, 'message' => 'Testimonial deleted']);
    }
}

// Fetch testimonials
$testimonials = $pdo->query("SELECT * FROM ml_cms_testimonials ORDER BY sort_order ASC, id DESC")->fetchAll();

$adminExtraJs = ['https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js'];
include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Testimonials</h1>
        <p class="text-white/40 text-sm mt-1"><?= count($testimonials) ?> testimonials</p>
    </div>
    <a href="/admin/testimonials-edit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Testimonial
    </a>
</div>

<?php if (empty($testimonials)): ?>
    <div class="admin-card">
        <div class="admin-empty">
            <svg class="admin-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <h3 class="admin-empty-title">No testimonials yet</h3>
            <p class="admin-empty-text">Add client testimonials or run the seed script to import existing ones.</p>
            <a href="/admin/testimonials-edit" class="admin-btn admin-btn-primary">Add Testimonial</a>
        </div>
    </div>
<?php else: ?>
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-10"></th>
                        <th>Client</th>
                        <th>Testimonial</th>
                        <th>Rating</th>
                        <th class="w-20">Featured</th>
                        <th class="w-20">Status</th>
                        <th class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="testimonialsTable">
                    <?php foreach ($testimonials as $testimonial): ?>
                        <tr data-id="<?= $testimonial['id'] ?>" data-item>
                            <td>
                                <div class="drag-handle cursor-grab">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                </div>
                            </td>
                            <td>
                                <span class="text-white font-medium"><?= e($testimonial['client_name']) ?></span>
                                <p class="text-white/30 text-xs mt-0.5"><?= e($testimonial['location'] ?? '') ?> &middot; <?= e($testimonial['project_type'] ?? '') ?></p>
                            </td>
                            <td>
                                <p class="text-white/50 text-sm line-clamp-2 max-w-md"><?= e(truncate($testimonial['testimonial_text'], 120)) ?></p>
                            </td>
                            <td>
                                <div class="flex items-center gap-0.5">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-3.5 h-3.5 <?= $i <= $testimonial['rating'] ? 'text-primary' : 'text-white/10' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td>
                                <button onclick="toggleFeatured(<?= $testimonial['id'] ?>, this)" class="<?= $testimonial['is_featured'] ? 'text-primary' : 'text-white/20' ?> hover:text-primary transition" title="<?= $testimonial['is_featured'] ? 'Featured' : 'Not featured' ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </td>
                            <td>
                                <div class="admin-toggle <?= $testimonial['is_active'] ? 'active' : '' ?>"
                                     onclick="toggleActive(<?= $testimonial['id'] ?>, this)"
                                     title="<?= $testimonial['is_active'] ? 'Active' : 'Inactive' ?>"></div>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/admin/testimonials-edit?id=<?= $testimonial['id'] ?>" class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="deleteTestimonial(<?= $testimonial['id'] ?>, '<?= e($testimonial['client_name']) ?>')" class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('testimonialsTable');
    if (table && typeof Sortable !== 'undefined') {
        Sortable.create(table, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                const ids = Array.from(table.querySelectorAll('tr[data-id]')).map(r => r.dataset.id);
                const form = new FormData();
                form.append('action', 'reorder');
                form.append('ids', JSON.stringify(ids));
                form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
                fetch('/admin/testimonials', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
                    .then(r => r.json())
                    .then(d => showToast(d.message || 'Order updated'))
                    .catch(() => showToast('Reorder failed', 'error'));
            }
        });
    }
});

function toggleActive(id, el) {
    const isActive = el.classList.contains('active') ? 0 : 1;
    el.classList.toggle('active');
    const form = new FormData();
    form.append('action', 'toggle');
    form.append('id', id);
    form.append('is_active', isActive);
    form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('/admin/testimonials', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
        .then(r => r.json())
        .then(d => showToast(d.message))
        .catch(() => { el.classList.toggle('active'); showToast('Update failed', 'error'); });
}

function toggleFeatured(id, el) {
    const isFeatured = el.classList.contains('text-primary') ? 0 : 1;
    el.classList.toggle('text-primary');
    el.classList.toggle('text-white/20');
    const form = new FormData();
    form.append('action', 'toggle_featured');
    form.append('id', id);
    form.append('is_featured', isFeatured);
    form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('/admin/testimonials', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
        .then(r => r.json())
        .then(d => showToast(d.message))
        .catch(() => { el.classList.toggle('text-primary'); el.classList.toggle('text-white/20'); showToast('Update failed', 'error'); });
}

function deleteTestimonial(id, name) {
    showConfirm('Delete Testimonial', 'Are you sure you want to delete the testimonial from <strong>' + name + '</strong>?', function() {
        const form = new FormData();
        form.append('action', 'delete');
        form.append('id', id);
        form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        fetch('/admin/testimonials', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
            .then(r => r.json())
            .then(d => { showToast(d.message); const row = document.querySelector('tr[data-id="'+id+'"]'); if(row) { row.style.opacity='0'; setTimeout(()=>row.remove(), 300); }})
            .catch(() => showToast('Delete failed', 'error'));
    });
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
