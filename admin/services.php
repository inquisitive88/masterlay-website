<?php
/**
 * CMS Admin - Services List
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Services';
$adminCurrentPage = 'services';
$adminBreadcrumb = ['Services' => ''];

// Handle AJAX reorder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax()) {
    require_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'reorder') {
        $ids = json_decode($_POST['ids'] ?? '[]', true);
        if (is_array($ids)) {
            $stmt = $pdo->prepare("UPDATE ml_cms_services SET sort_order = ? WHERE id = ?");
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
        $pdo->prepare("UPDATE ml_cms_services SET is_active = ? WHERE id = ?")->execute([$active, $id]);
        json_response(['success' => true, 'message' => $active ? 'Activated' : 'Deactivated']);
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare("DELETE FROM ml_cms_services WHERE id = ?")->execute([$id]);
        json_response(['success' => true, 'message' => 'Service deleted']);
    }
}

// Fetch services
$services = $pdo->query("SELECT * FROM ml_cms_services ORDER BY sort_order ASC")->fetchAll();

$adminExtraJs = ['https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js'];
include __DIR__ . '/includes/admin-layout-top.php';
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Services</h1>
        <p class="text-white/40 text-sm mt-1"><?= count($services) ?> services</p>
    </div>
    <a href="/admin/services-edit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Service
    </a>
</div>

<?php if (empty($services)): ?>
    <div class="admin-card">
        <div class="admin-empty">
            <svg class="admin-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <h3 class="admin-empty-title">No services yet</h3>
            <p class="admin-empty-text">Add your first service or run the seed script to import existing data.</p>
            <a href="/admin/services-edit" class="admin-btn admin-btn-primary">Add Service</a>
        </div>
    </div>
<?php else: ?>
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-10"></th>
                        <th class="w-16">Image</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th class="w-20">Status</th>
                        <th class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="servicesTable">
                    <?php foreach ($services as $service): ?>
                        <tr data-id="<?= $service['id'] ?>" data-item>
                            <td>
                                <div class="drag-handle cursor-grab">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                </div>
                            </td>
                            <td>
                                <?php if ($service['image_url']): ?>
                                    <img src="<?= e($service['image_url']) ?>" alt="" class="w-12 h-12 rounded-lg object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-lg bg-dark-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-white font-medium"><?= e($service['title']) ?></span>
                                <p class="text-white/30 text-xs mt-0.5 line-clamp-1"><?= e(truncate($service['short_description'] ?? '', 80)) ?></p>
                            </td>
                            <td><code class="text-xs text-white/40 bg-dark-300 px-2 py-0.5 rounded"><?= e($service['slug']) ?></code></td>
                            <td>
                                <div class="admin-toggle <?= $service['is_active'] ? 'active' : '' ?>"
                                     onclick="toggleService(<?= $service['id'] ?>, this)"
                                     title="<?= $service['is_active'] ? 'Active' : 'Inactive' ?>"></div>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/admin/service-sections?id=<?= $service['id'] ?>" class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon" title="Page Content">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <a href="/admin/services-edit?id=<?= $service['id'] ?>" class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="deleteService(<?= $service['id'] ?>, '<?= e($service['title']) ?>')" class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon" title="Delete">
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
    const table = document.getElementById('servicesTable');
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
                fetch('/admin/services', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
                    .then(r => r.json())
                    .then(d => showToast(d.message || 'Order updated'))
                    .catch(() => showToast('Reorder failed', 'error'));
            }
        });
    }
});

function toggleService(id, el) {
    const isActive = el.classList.contains('active') ? 0 : 1;
    el.classList.toggle('active');
    const form = new FormData();
    form.append('action', 'toggle');
    form.append('id', id);
    form.append('is_active', isActive);
    form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('/admin/services', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
        .then(r => r.json())
        .then(d => showToast(d.message))
        .catch(() => { el.classList.toggle('active'); showToast('Update failed', 'error'); });
}

function deleteService(id, name) {
    showConfirm('Delete Service', 'Are you sure you want to delete <strong>' + name + '</strong>?', function() {
        const form = new FormData();
        form.append('action', 'delete');
        form.append('id', id);
        form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        fetch('/admin/services', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
            .then(r => r.json())
            .then(d => { showToast(d.message); const row = document.querySelector('tr[data-id="'+id+'"]'); if(row) { row.style.opacity='0'; setTimeout(()=>row.remove(), 300); }})
            .catch(() => showToast('Delete failed', 'error'));
    });
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
