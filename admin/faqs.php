<?php
/**
 * CMS Admin - FAQs List
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'FAQs';
$adminCurrentPage = 'faqs';
$adminBreadcrumb = ['FAQs' => ''];

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax()) {
    require_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'reorder') {
        $ids = json_decode($_POST['ids'] ?? '[]', true);
        if (is_array($ids)) {
            $stmt = $pdo->prepare("UPDATE ml_cms_faqs SET sort_order = ? WHERE id = ?");
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
        $pdo->prepare("UPDATE ml_cms_faqs SET is_active = ? WHERE id = ?")->execute([$active, $id]);
        json_response(['success' => true, 'message' => $active ? 'Activated' : 'Deactivated']);
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare("DELETE FROM ml_cms_faqs WHERE id = ?")->execute([$id]);
        json_response(['success' => true, 'message' => 'FAQ deleted']);
    }
}

// Fetch FAQ categories and FAQs
$categories = $pdo->query("SELECT * FROM ml_cms_faq_categories ORDER BY sort_order ASC")->fetchAll();
$filterCat = $_GET['category'] ?? '';

if ($filterCat) {
    $stmt = $pdo->prepare("SELECT * FROM ml_cms_faqs WHERE category = ? ORDER BY sort_order ASC, id ASC");
    $stmt->execute([$filterCat]);
    $faqs = $stmt->fetchAll();
} else {
    $faqs = $pdo->query("SELECT * FROM ml_cms_faqs ORDER BY category ASC, sort_order ASC, id ASC")->fetchAll();
}

$adminExtraJs = ['https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js'];
include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">FAQs</h1>
        <p class="text-white/40 text-sm mt-1"><?= count($faqs) ?> questions</p>
    </div>
    <a href="/admin/faqs-edit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add FAQ
    </a>
</div>

<!-- Category Filter Tabs -->
<?php if (!empty($categories)): ?>
<div class="flex items-center gap-2 mb-6 flex-wrap">
    <a href="/admin/faqs" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= !$filterCat ? 'bg-primary text-dark' : 'bg-dark-300 text-white/50 hover:text-white' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
        <a href="/admin/faqs?category=<?= urlencode($cat['slug']) ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $filterCat === $cat['slug'] ? 'bg-primary text-dark' : 'bg-dark-300 text-white/50 hover:text-white' ?>"><?= e($cat['label']) ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($faqs)): ?>
    <div class="admin-card">
        <div class="admin-empty">
            <svg class="admin-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="admin-empty-title">No FAQs yet</h3>
            <p class="admin-empty-text">Add frequently asked questions or run the seed script to import existing ones.</p>
            <a href="/admin/faqs-edit" class="admin-btn admin-btn-primary">Add FAQ</a>
        </div>
    </div>
<?php else: ?>
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-10"></th>
                        <th>Question</th>
                        <th>Category</th>
                        <th class="w-20">Status</th>
                        <th class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="faqsTable">
                    <?php foreach ($faqs as $faq): ?>
                        <tr data-id="<?= $faq['id'] ?>" data-item>
                            <td>
                                <div class="drag-handle cursor-grab">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                </div>
                            </td>
                            <td>
                                <span class="text-white font-medium"><?= e($faq['question']) ?></span>
                                <p class="text-white/30 text-xs mt-0.5 line-clamp-1"><?= e(truncate($faq['answer'], 100)) ?></p>
                            </td>
                            <td>
                                <span class="admin-badge admin-badge-info"><?= e($faq['category']) ?></span>
                            </td>
                            <td>
                                <div class="admin-toggle <?= $faq['is_active'] ? 'active' : '' ?>"
                                     onclick="toggleFaq(<?= $faq['id'] ?>, this)"
                                     title="<?= $faq['is_active'] ? 'Active' : 'Inactive' ?>"></div>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/admin/faqs-edit?id=<?= $faq['id'] ?>" class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="deleteFaq(<?= $faq['id'] ?>)" class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon" title="Delete">
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
    const table = document.getElementById('faqsTable');
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
                fetch('/admin/faqs', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
                    .then(r => r.json())
                    .then(d => showToast(d.message || 'Order updated'))
                    .catch(() => showToast('Reorder failed', 'error'));
            }
        });
    }
});

function toggleFaq(id, el) {
    const isActive = el.classList.contains('active') ? 0 : 1;
    el.classList.toggle('active');
    const form = new FormData();
    form.append('action', 'toggle');
    form.append('id', id);
    form.append('is_active', isActive);
    form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('/admin/faqs', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
        .then(r => r.json())
        .then(d => showToast(d.message))
        .catch(() => { el.classList.toggle('active'); showToast('Update failed', 'error'); });
}

function deleteFaq(id) {
    showConfirm('Delete FAQ', 'Are you sure you want to delete this FAQ?', function() {
        const form = new FormData();
        form.append('action', 'delete');
        form.append('id', id);
        form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        fetch('/admin/faqs', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
            .then(r => r.json())
            .then(d => { showToast(d.message); const row = document.querySelector('tr[data-id="'+id+'"]'); if(row) { row.style.opacity='0'; setTimeout(()=>row.remove(), 300); }})
            .catch(() => showToast('Delete failed', 'error'));
    });
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
