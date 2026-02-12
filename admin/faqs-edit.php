<?php
/**
 * CMS Admin - FAQ Create/Edit
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$id = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$faq = null;

if ($isEdit) {
    $faq = $pdo->prepare("SELECT * FROM ml_cms_faqs WHERE id = ?");
    $faq->execute([$id]);
    $faq = $faq->fetch();
    if (!$faq) {
        redirect('/admin/faqs', 'error', 'FAQ not found.');
    }
}

$adminPageTitle = $isEdit ? 'Edit FAQ' : 'Add FAQ';
$adminCurrentPage = 'faqs';
$adminBreadcrumb = ['FAQs' => '/admin/faqs', $adminPageTitle => ''];

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM ml_cms_faq_categories ORDER BY sort_order ASC")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $data = [
        'category'  => trim($_POST['category'] ?? ''),
        'question'  => trim($_POST['question'] ?? ''),
        'answer'    => trim($_POST['answer'] ?? ''),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];

    // Validation
    $errors = [];
    if (empty($data['question'])) $errors[] = 'Question is required.';
    if (empty($data['answer'])) $errors[] = 'Answer is required.';
    if (empty($data['category'])) $errors[] = 'Category is required.';

    if (!empty($errors)) {
        set_flash('error', implode(' ', $errors));
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE ml_cms_faqs SET category=?, question=?, answer=?, is_active=? WHERE id=?");
            $stmt->execute([$data['category'], $data['question'], $data['answer'], $data['is_active'], $id]);
            redirect('/admin/faqs', 'success', 'FAQ updated successfully.');
        } else {
            $maxOrder = (int)$pdo->query("SELECT MAX(sort_order) FROM ml_cms_faqs")->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO ml_cms_faqs (category, question, answer, is_active, sort_order) VALUES (?,?,?,?,?)");
            $stmt->execute([$data['category'], $data['question'], $data['answer'], $data['is_active'], $maxOrder + 1]);
            redirect('/admin/faqs', 'success', 'FAQ created successfully.');
        }
    }
}

// Use POST data on error, or existing data
$form = $faq ?? [
    'category' => $_GET['category'] ?? '', 'question' => '', 'answer' => '', 'is_active' => 1,
];

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white"><?= $isEdit ? 'Edit FAQ' : 'Add FAQ' ?></h1>
    </div>
    <a href="/admin/faqs" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to FAQs
    </a>
</div>

<form method="POST" action="/admin/faqs-edit<?= $isEdit ? '?id=' . $id : '' ?>">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">FAQ Details</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="category">Category *</label>
                    <select id="category" name="category" class="admin-form-select" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat['slug']) ?>" <?= $form['category'] === $cat['slug'] ? 'selected' : '' ?>><?= e($cat['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="admin-form-help">Categories can be managed via the seed script or directly in the database.</p>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="question">Question *</label>
                    <input type="text" id="question" name="question" value="<?= e($form['question']) ?>" class="admin-form-input" required placeholder="e.g. How long does floor installation take?">
                </div>

                <div class="admin-form-group mb-0">
                    <label class="admin-form-label" for="answer">Answer *</label>
                    <textarea id="answer" name="answer" class="admin-form-textarea" rows="8" required placeholder="Provide a detailed answer..."><?= e($form['answer']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
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
                    <?= $isEdit ? 'Update' : 'Create' ?> FAQ
                </button>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
