<?php
/**
 * CMS Admin - Blog Post Create/Edit
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$id = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$post = null;

if ($isEdit) {
    $post = $pdo->prepare("SELECT * FROM ml_cms_blog_posts WHERE id = ?");
    $post->execute([$id]);
    $post = $post->fetch();
    if (!$post) redirect('/admin/blog', 'error', 'Post not found.');
}

$adminPageTitle = $isEdit ? 'Edit Post' : 'New Post';
$adminCurrentPage = 'blog';
$adminBreadcrumb = ['Blog Posts' => '/admin/blog', $adminPageTitle => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $data = [
        'title'              => trim($_POST['title'] ?? ''),
        'slug'               => slugify(trim($_POST['slug'] ?? '')),
        'excerpt'            => trim($_POST['excerpt'] ?? ''),
        'content'            => $_POST['content'] ?? '',
        'featured_image_url' => trim($_POST['featured_image_url'] ?? ''),
        'category'           => trim($_POST['category'] ?? ''),
        'author_name'        => trim($_POST['author_name'] ?? 'Masterlay Renovations'),
        'status'             => in_array($_POST['status'] ?? '', ['draft', 'published', 'archived']) ? $_POST['status'] : 'draft',
    ];

    if (empty($data['slug'])) $data['slug'] = slugify($data['title']);

    // Set published_at when publishing
    $publishedAt = null;
    if ($data['status'] === 'published') {
        $publishedAt = $isEdit && $post['published_at'] ? $post['published_at'] : date('Y-m-d H:i:s');
    }

    if (empty($data['title'])) {
        set_flash('error', 'Title is required.');
    } else {
        // Check slug uniqueness
        $slugCheck = $pdo->prepare("SELECT id FROM ml_cms_blog_posts WHERE slug = ? AND id != ?");
        $slugCheck->execute([$data['slug'], $id]);
        if ($slugCheck->fetch()) {
            set_flash('error', 'A post with this slug already exists.');
        } else {
            if ($isEdit) {
                $stmt = $pdo->prepare("UPDATE ml_cms_blog_posts SET title=?, slug=?, excerpt=?, content=?, featured_image_url=?, category=?, author_name=?, status=?, published_at=? WHERE id=?");
                $stmt->execute([$data['title'], $data['slug'], $data['excerpt'], $data['content'], $data['featured_image_url'], $data['category'], $data['author_name'], $data['status'], $publishedAt, $id]);
                redirect('/admin/blog', 'success', 'Post updated.');
            } else {
                $stmt = $pdo->prepare("INSERT INTO ml_cms_blog_posts (title, slug, excerpt, content, featured_image_url, category, author_name, status, published_at) VALUES (?,?,?,?,?,?,?,?,?)");
                $stmt->execute([$data['title'], $data['slug'], $data['excerpt'], $data['content'], $data['featured_image_url'], $data['category'], $data['author_name'], $data['status'], $publishedAt]);
                redirect('/admin/blog', 'success', 'Post created.');
            }
        }
    }
}

$form = $post ?? [
    'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '',
    'featured_image_url' => '', 'category' => '', 'author_name' => 'Masterlay Renovations',
    'status' => 'draft', 'published_at' => null,
];

include __DIR__ . '/includes/admin-layout-top.php';
?>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div class="flex items-center justify-between mb-6">
    <h1 class="font-heading text-2xl font-bold text-white"><?= $isEdit ? 'Edit Post' : 'New Post' ?></h1>
    <a href="/admin/blog" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<form method="POST" action="/admin/blog-edit<?= $isEdit ? '?id='.$id : '' ?>">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <div class="admin-form-group">
                    <label class="admin-form-label" for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?= e($form['title']) ?>" class="admin-form-input text-lg" required placeholder="Enter post title">
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" class="admin-form-input" placeholder="auto-generated-from-title">
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="admin-form-textarea" rows="3" placeholder="Brief summary shown in listings"><?= e($form['excerpt']) ?></textarea>
                </div>

                <div class="admin-form-group mb-0">
                    <label class="admin-form-label">Content</label>
                    <textarea id="editor" name="content" class="admin-form-textarea" rows="20"><?= e($form['content']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Publish</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label">Status</label>
                    <select name="status" class="admin-form-select">
                        <option value="draft" <?= $form['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $form['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $form['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>

                <?php if ($form['published_at']): ?>
                    <p class="text-white/40 text-xs">Published: <?= date('M j, Y g:i A', strtotime($form['published_at'])) ?></p>
                <?php endif; ?>
            </div>

            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Details</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label">Category</label>
                    <input type="text" name="category" value="<?= e($form['category']) ?>" class="admin-form-input" placeholder="e.g. Flooring, Tips">
                </div>

                <div class="admin-form-group mb-0">
                    <label class="admin-form-label">Author</label>
                    <input type="text" name="author_name" value="<?= e($form['author_name']) ?>" class="admin-form-input">
                </div>
            </div>

            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Featured Image</h2>
                <div class="admin-form-group mb-0">
                    <input type="url" name="featured_image_url" value="<?= e($form['featured_image_url']) ?>" class="admin-form-input" placeholder="https://..." id="featuredImage">
                    <div id="featuredPreview" class="mt-2 <?= $form['featured_image_url'] ? '' : 'hidden' ?>">
                        <img src="<?= e($form['featured_image_url']) ?>" alt="" class="w-full h-40 object-cover rounded-lg" id="featuredImg">
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="admin-btn admin-btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <?= $isEdit ? 'Update' : 'Create' ?> Post
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// Auto-slug
document.getElementById('title').addEventListener('input', function() {
    const slugField = document.getElementById('slug');
    if (!slugField.value || slugField.dataset.auto === '1') {
        slugField.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-').replace(/^-|-$/g, '');
        slugField.dataset.auto = '1';
    }
});
document.getElementById('slug').addEventListener('input', function() { this.dataset.auto = '0'; });

// Featured image preview
document.getElementById('featuredImage').addEventListener('change', function() {
    const preview = document.getElementById('featuredPreview');
    const img = document.getElementById('featuredImg');
    if (this.value) {
        img.src = this.value;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});

// TinyMCE init
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#editor',
            height: 500,
            menubar: false,
            skin: 'oxide-dark',
            content_css: 'dark',
            plugins: 'lists link image code table hr wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | hr | code',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 15px; color: #e0e0e0; background: #141414; }',
            branding: false,
            promotion: false,
        });
    }
});
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
