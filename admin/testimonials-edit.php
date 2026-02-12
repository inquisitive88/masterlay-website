<?php
/**
 * CMS Admin - Testimonial Create/Edit
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$id = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;
$testimonial = null;

if ($isEdit) {
    $testimonial = $pdo->prepare("SELECT * FROM ml_cms_testimonials WHERE id = ?");
    $testimonial->execute([$id]);
    $testimonial = $testimonial->fetch();
    if (!$testimonial) {
        redirect('/admin/testimonials', 'error', 'Testimonial not found.');
    }
}

$adminPageTitle = $isEdit ? 'Edit Testimonial' : 'Add Testimonial';
$adminCurrentPage = 'testimonials';
$adminBreadcrumb = ['Testimonials' => '/admin/testimonials', $adminPageTitle => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $data = [
        'client_name'      => trim($_POST['client_name'] ?? ''),
        'location'         => trim($_POST['location'] ?? ''),
        'rating'           => max(1, min(5, (int)($_POST['rating'] ?? 5))),
        'testimonial_text' => trim($_POST['testimonial_text'] ?? ''),
        'project_type'     => trim($_POST['project_type'] ?? ''),
        'is_featured'      => isset($_POST['is_featured']) ? 1 : 0,
        'is_active'        => isset($_POST['is_active']) ? 1 : 0,
    ];

    // Validation
    $errors = [];
    if (empty($data['client_name'])) $errors[] = 'Client name is required.';
    if (empty($data['testimonial_text'])) $errors[] = 'Testimonial text is required.';

    if (!empty($errors)) {
        set_flash('error', implode(' ', $errors));
    } else {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE ml_cms_testimonials SET client_name=?, location=?, rating=?, testimonial_text=?, project_type=?, is_featured=?, is_active=? WHERE id=?");
            $stmt->execute([
                $data['client_name'], $data['location'], $data['rating'], $data['testimonial_text'],
                $data['project_type'], $data['is_featured'], $data['is_active'], $id
            ]);
            redirect('/admin/testimonials', 'success', 'Testimonial updated successfully.');
        } else {
            $maxOrder = (int)$pdo->query("SELECT MAX(sort_order) FROM ml_cms_testimonials")->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO ml_cms_testimonials (client_name, location, rating, testimonial_text, project_type, is_featured, is_active, sort_order) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $data['client_name'], $data['location'], $data['rating'], $data['testimonial_text'],
                $data['project_type'], $data['is_featured'], $data['is_active'], $maxOrder + 1
            ]);
            redirect('/admin/testimonials', 'success', 'Testimonial created successfully.');
        }
    }
}

// Use POST data on error, or existing data
$form = $testimonial ?? [
    'client_name' => '', 'location' => '', 'rating' => 5, 'testimonial_text' => '',
    'project_type' => '', 'is_featured' => 0, 'is_active' => 1,
];

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white"><?= $isEdit ? 'Edit Testimonial' : 'Add Testimonial' ?></h1>
    </div>
    <a href="/admin/testimonials" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Testimonials
    </a>
</div>

<form method="POST" action="/admin/testimonials-edit<?= $isEdit ? '?id=' . $id : '' ?>">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Testimonial Details</h2>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="client_name">Client Name *</label>
                    <input type="text" id="client_name" name="client_name" value="<?= e($form['client_name']) ?>" class="admin-form-input" required placeholder="e.g. John Smith">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?= e($form['location']) ?>" class="admin-form-input" placeholder="e.g. Toronto, ON">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label" for="project_type">Project Type</label>
                        <input type="text" id="project_type" name="project_type" value="<?= e($form['project_type']) ?>" class="admin-form-input" placeholder="e.g. Floor Installation">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="testimonial_text">Testimonial Text *</label>
                    <textarea id="testimonial_text" name="testimonial_text" class="admin-form-textarea" rows="6" required placeholder="What the client said about your work..."><?= e($form['testimonial_text']) ?></textarea>
                </div>

                <div class="admin-form-group mb-0">
                    <label class="admin-form-label">Rating</label>
                    <div class="flex items-center gap-2" id="ratingStars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button type="button" onclick="setRating(<?= $i ?>)" class="star-btn p-1 transition hover:scale-110 <?= $i <= $form['rating'] ? 'text-primary' : 'text-white/20' ?>">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="<?= (int)$form['rating'] ?>">
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
                        <span class="text-white/70 text-sm">Active (visible on website)</span>
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
                    <?= $isEdit ? 'Update' : 'Create' ?> Testimonial
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function setRating(val) {
    document.getElementById('ratingInput').value = val;
    const stars = document.querySelectorAll('#ratingStars .star-btn');
    stars.forEach((star, i) => {
        if (i < val) {
            star.classList.remove('text-white/20');
            star.classList.add('text-primary');
        } else {
            star.classList.remove('text-primary');
            star.classList.add('text-white/20');
        }
    });
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
