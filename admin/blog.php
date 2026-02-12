<?php
/**
 * CMS Admin - Blog Posts List
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Blog Posts';
$adminCurrentPage = 'blog';
$adminBreadcrumb = ['Blog Posts' => ''];

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax()) {
    require_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare("DELETE FROM ml_cms_blog_posts WHERE id = ?")->execute([$id]);
        json_response(['success' => true, 'message' => 'Post deleted']);
    }
}

// Fetch posts
$status = $_GET['status'] ?? '';
$where = '';
$params = [];
if ($status && in_array($status, ['draft', 'published', 'archived'])) {
    $where = " WHERE status = ?";
    $params = [$status];
}

$posts = $pdo->prepare("SELECT * FROM ml_cms_blog_posts{$where} ORDER BY created_at DESC");
$posts->execute($params);
$posts = $posts->fetchAll();

// Counts by status
$counts = [];
foreach (['', 'draft', 'published', 'archived'] as $s) {
    if ($s === '') {
        $counts['all'] = (int)$pdo->query("SELECT COUNT(*) FROM ml_cms_blog_posts")->fetchColumn();
    } else {
        $counts[$s] = (int)$pdo->query("SELECT COUNT(*) FROM ml_cms_blog_posts WHERE status = '{$s}'")->fetchColumn();
    }
}

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Blog Posts</h1>
        <p class="text-white/40 text-sm mt-1"><?= $counts['all'] ?> total posts</p>
    </div>
    <a href="/admin/blog-edit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Post
    </a>
</div>

<!-- Status Tabs -->
<div class="flex items-center gap-2 mb-6">
    <a href="/admin/blog" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= !$status ? 'bg-primary text-dark' : 'bg-dark-300 text-white/50 hover:text-white' ?>">All (<?= $counts['all'] ?>)</a>
    <a href="/admin/blog?status=published" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $status === 'published' ? 'bg-green-500/20 text-green-400' : 'bg-dark-300 text-white/50 hover:text-white' ?>">Published (<?= $counts['published'] ?>)</a>
    <a href="/admin/blog?status=draft" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $status === 'draft' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-dark-300 text-white/50 hover:text-white' ?>">Drafts (<?= $counts['draft'] ?>)</a>
    <a href="/admin/blog?status=archived" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $status === 'archived' ? 'bg-white/10 text-white/50' : 'bg-dark-300 text-white/50 hover:text-white' ?>">Archived (<?= $counts['archived'] ?>)</a>
</div>

<?php if (empty($posts)): ?>
    <div class="admin-card">
        <div class="admin-empty">
            <svg class="admin-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <h3 class="admin-empty-title">No blog posts yet</h3>
            <p class="admin-empty-text">Start writing your first blog post to share renovation tips and insights.</p>
            <a href="/admin/blog-edit" class="admin-btn admin-btn-primary">Write First Post</a>
        </div>
    </div>
<?php else: ?>
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="w-32 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr data-id="<?= $post['id'] ?>" data-item>
                            <td>
                                <a href="/admin/blog-edit?id=<?= $post['id'] ?>" class="text-white font-medium hover:text-primary transition"><?= e($post['title']) ?></a>
                                <p class="text-white/30 text-xs mt-0.5">/blog/<?= e($post['slug']) ?></p>
                            </td>
                            <td>
                                <?php if ($post['category']): ?>
                                    <span class="admin-badge admin-badge-info"><?= e($post['category']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusClass = match($post['status']) {
                                    'published' => 'admin-badge-success',
                                    'draft'     => 'admin-badge-warning',
                                    'archived'  => 'admin-badge-danger',
                                    default     => 'admin-badge-info',
                                };
                                ?>
                                <span class="admin-badge <?= $statusClass ?>"><?= ucfirst($post['status']) ?></span>
                            </td>
                            <td class="text-white/40 text-sm">
                                <?= $post['published_at'] ? date('M j, Y', strtotime($post['published_at'])) : date('M j, Y', strtotime($post['created_at'])) ?>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <?php if ($post['status'] === 'published'): ?>
                                        <a href="/blog/<?= e($post['slug']) ?>" target="_blank" class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    <a href="/admin/blog-edit?id=<?= $post['id'] ?>" class="admin-btn admin-btn-secondary admin-btn-sm admin-btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="deletePost(<?= $post['id'] ?>, '<?= e($post['title']) ?>')" class="admin-btn admin-btn-danger admin-btn-sm admin-btn-icon" title="Delete">
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
function deletePost(id, name) {
    showConfirm('Delete Post', 'Are you sure you want to delete <strong>' + name + '</strong>?', function() {
        const form = new FormData();
        form.append('action', 'delete'); form.append('id', id);
        form.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        fetch('/admin/blog', { method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: form })
            .then(r => r.json())
            .then(d => { showToast(d.message); const row = document.querySelector('tr[data-id="'+id+'"]'); if(row) { row.style.opacity='0'; setTimeout(()=>row.remove(), 300); }})
            .catch(() => showToast('Delete failed', 'error'));
    });
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
