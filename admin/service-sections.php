<?php
/**
 * CMS Admin - Service Detail Sections Editor
 * Manages the rich content sections for a service's detail page:
 * overview, features, process steps, gallery images, FAQs
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$serviceId = (int)($_GET['id'] ?? 0);
if (!$serviceId) {
    redirect('/admin/services', 'error', 'Service not found.');
}

// Load the service
$stmt = $pdo->prepare("SELECT * FROM ml_cms_services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch();
if (!$service) {
    redirect('/admin/services', 'error', 'Service not found.');
}

$adminPageTitle = 'Page Content: ' . $service['title'];
$adminCurrentPage = 'services';
$adminBreadcrumb = ['Services' => '/admin/services', 'Edit' => '/admin/services-edit?id=' . $serviceId, 'Page Content' => ''];

// ============ Handle AJAX POST actions ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax()) {
    require_csrf();

    $action = $_POST['action'] ?? '';

    if ($action === 'save_overview') {
        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        // Upsert: find existing or create
        $existing = $pdo->prepare("SELECT id FROM ml_cms_service_detail_sections WHERE service_id = ? AND section_type = 'overview' LIMIT 1");
        $existing->execute([$serviceId]);
        $row = $existing->fetch();

        if ($row) {
            $stmt = $pdo->prepare("UPDATE ml_cms_service_detail_sections SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $content, $row['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO ml_cms_service_detail_sections (service_id, section_type, title, content, sort_order) VALUES (?, 'overview', ?, ?, 0)");
            $stmt->execute([$serviceId, $title, $content]);
        }

        json_response(['success' => true, 'message' => 'Overview saved.']);
    }

    if ($action === 'save_features') {
        $title    = trim($_POST['title'] ?? "What's Included");
        $features = $_POST['features'] ?? [];
        $features = array_values(array_filter(array_map('trim', $features)));
        $content  = json_encode($features);

        $existing = $pdo->prepare("SELECT id FROM ml_cms_service_detail_sections WHERE service_id = ? AND section_type = 'features' LIMIT 1");
        $existing->execute([$serviceId]);
        $row = $existing->fetch();

        if ($row) {
            $stmt = $pdo->prepare("UPDATE ml_cms_service_detail_sections SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $content, $row['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO ml_cms_service_detail_sections (service_id, section_type, title, content, sort_order) VALUES (?, 'features', ?, ?, 1)");
            $stmt->execute([$serviceId, $title, $content]);
        }

        json_response(['success' => true, 'message' => 'Features saved.']);
    }

    if ($action === 'save_process') {
        $steps = $_POST['steps'] ?? [];

        // Delete existing process steps
        $pdo->prepare("DELETE FROM ml_cms_service_detail_sections WHERE service_id = ? AND section_type = 'process'")->execute([$serviceId]);

        // Insert new steps
        $stmt = $pdo->prepare("INSERT INTO ml_cms_service_detail_sections (service_id, section_type, title, content, sort_order) VALUES (?, 'process', ?, ?, ?)");
        foreach ($steps as $i => $step) {
            $stepTitle   = trim($step['title'] ?? '');
            $stepContent = trim($step['content'] ?? '');
            if ($stepTitle || $stepContent) {
                $stmt->execute([$serviceId, $stepTitle, $stepContent, $i]);
            }
        }

        json_response(['success' => true, 'message' => 'Process steps saved.']);
    }

    if ($action === 'save_gallery') {
        $title    = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $images   = $_POST['images'] ?? [];

        // Filter and build JSON array
        $imageList = [];
        foreach ($images as $img) {
            $src = trim($img['src'] ?? '');
            $alt = trim($img['alt'] ?? '');
            if ($src) {
                $imageList[] = ['src' => $src, 'alt' => $alt];
            }
        }

        $content = json_encode($imageList);

        $existing = $pdo->prepare("SELECT id FROM ml_cms_service_detail_sections WHERE service_id = ? AND section_type = 'gallery' LIMIT 1");
        $existing->execute([$serviceId]);
        $row = $existing->fetch();

        if ($row) {
            $stmt = $pdo->prepare("UPDATE ml_cms_service_detail_sections SET title = ?, subtitle = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $subtitle, $content, $row['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO ml_cms_service_detail_sections (service_id, section_type, title, subtitle, content, sort_order) VALUES (?, 'gallery', ?, ?, ?, 3)");
            $stmt->execute([$serviceId, $title, $subtitle, $content]);
        }

        json_response(['success' => true, 'message' => 'Gallery saved.']);
    }

    if ($action === 'save_faqs') {
        $title = trim($_POST['title'] ?? '');
        $faqs  = $_POST['faqs'] ?? [];

        $faqList = [];
        foreach ($faqs as $faq) {
            $q = trim($faq['q'] ?? '');
            $a = trim($faq['a'] ?? '');
            if ($q && $a) {
                $faqList[] = ['q' => $q, 'a' => $a];
            }
        }

        $content = json_encode($faqList);

        $existing = $pdo->prepare("SELECT id FROM ml_cms_service_detail_sections WHERE service_id = ? AND section_type = 'faq' LIMIT 1");
        $existing->execute([$serviceId]);
        $row = $existing->fetch();

        if ($row) {
            $stmt = $pdo->prepare("UPDATE ml_cms_service_detail_sections SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $content, $row['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO ml_cms_service_detail_sections (service_id, section_type, title, content, sort_order) VALUES (?, 'faq', ?, ?, 4)");
            $stmt->execute([$serviceId, $title, $content]);
        }

        json_response(['success' => true, 'message' => 'FAQs saved.']);
    }

    json_response(['error' => 'Unknown action.'], 400);
}

// ============ Load existing sections ============
$allSections = $pdo->prepare("SELECT * FROM ml_cms_service_detail_sections WHERE service_id = ? ORDER BY sort_order ASC");
$allSections->execute([$serviceId]);
$allSections = $allSections->fetchAll(PDO::FETCH_ASSOC);

$sectionsByType = [];
foreach ($allSections as $sec) {
    $sectionsByType[$sec['section_type']][] = $sec;
}

// Prepare data for the form
$overviewData = $sectionsByType['overview'][0] ?? ['title' => '', 'content' => ''];
$featuresData = $sectionsByType['features'][0] ?? ['title' => "What's Included", 'content' => '[]'];
$featuresList = json_decode($featuresData['content'] ?: '[]', true) ?: [];

$processSteps = $sectionsByType['process'] ?? [];

$galleryData = $sectionsByType['gallery'][0] ?? ['title' => '', 'subtitle' => '', 'content' => '[]'];
$galleryImages = json_decode($galleryData['content'] ?: '[]', true) ?: [];

$faqData = $sectionsByType['faq'][0] ?? ['title' => '', 'content' => '[]'];
$faqList = json_decode($faqData['content'] ?: '[]', true) ?: [];

// Check if static file exists
$hasStaticFile = file_exists(dirname(__DIR__) . '/services/' . basename($service['slug']) . '.php');

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Page Content: <?= e($service['title']) ?></h1>
        <p class="text-white/40 text-sm mt-1">
            Manage the detail page sections shown when visitors click on this service.
            <?php if ($hasStaticFile): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-xs ml-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Legacy static file exists — it takes priority until removed
                </span>
            <?php endif; ?>
        </p>
    </div>
    <div class="flex items-center gap-3">
        <a href="/services/<?= e($service['slug']) ?>" target="_blank" class="admin-btn admin-btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Preview
        </a>
        <a href="/admin/services-edit?id=<?= $serviceId ?>" class="admin-btn admin-btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Service
        </a>
    </div>
</div>

<!-- Section Tabs -->
<div class="flex gap-2 mb-6 overflow-x-auto pb-2" id="sectionTabs">
    <button class="section-tab active" data-tab="overview">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Overview
    </button>
    <button class="section-tab" data-tab="features">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Features
    </button>
    <button class="section-tab" data-tab="process">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Process Steps
    </button>
    <button class="section-tab" data-tab="gallery">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Gallery
    </button>
    <button class="section-tab" data-tab="faq">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        FAQs
    </button>
</div>

<!-- ============ OVERVIEW TAB ============ -->
<div class="section-panel active" id="panel-overview">
    <div class="admin-card">
        <h2 class="admin-card-title mb-5">Service Overview</h2>
        <p class="text-white/40 text-sm mb-6">The main heading and descriptive text for the overview section.</p>

        <div class="admin-form-group">
            <label class="admin-form-label" for="overview-title">Section Title</label>
            <input type="text" id="overview-title" class="admin-form-input" value="<?= e($overviewData['title']) ?>" placeholder="e.g. Premium Vinyl Plank Flooring, Expertly Installed">
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label" for="overview-content">Content</label>
            <textarea id="overview-content" class="admin-form-textarea" rows="8" placeholder="Overview paragraphs. Use blank lines to separate paragraphs."><?= e($overviewData['content'] ?? '') ?></textarea>
            <p class="admin-form-help">Separate paragraphs with blank lines. This text appears next to the service image.</p>
        </div>

        <button type="button" class="admin-btn admin-btn-primary" onclick="saveOverview()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Overview
        </button>
    </div>
</div>

<!-- ============ FEATURES TAB ============ -->
<div class="section-panel" id="panel-features">
    <div class="admin-card">
        <h2 class="admin-card-title mb-5">Features / What's Included</h2>
        <p class="text-white/40 text-sm mb-6">A checklist of features shown below the overview text with checkmark icons.</p>

        <div class="admin-form-group">
            <label class="admin-form-label" for="features-title">Section Title</label>
            <input type="text" id="features-title" class="admin-form-input" value="<?= e($featuresData['title'] ?? "What's Included") ?>" placeholder="What's Included">
        </div>

        <div id="featuresList" class="space-y-3 mb-4">
            <?php if (!empty($featuresList)): ?>
                <?php foreach ($featuresList as $feature): ?>
                    <div class="feature-row flex items-center gap-3">
                        <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <input type="text" class="admin-form-input flex-1 feature-input" value="<?= e(is_array($feature) ? $feature['text'] : $feature) ?>" placeholder="Feature description">
                        <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.feature-row').remove()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="admin-btn admin-btn-secondary mb-6" onclick="addFeature()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Feature
        </button>

        <div>
            <button type="button" class="admin-btn admin-btn-primary" onclick="saveFeatures()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Features
            </button>
        </div>
    </div>
</div>

<!-- ============ PROCESS TAB ============ -->
<div class="section-panel" id="panel-process">
    <div class="admin-card">
        <h2 class="admin-card-title mb-5">Process Steps</h2>
        <p class="text-white/40 text-sm mb-6">Numbered steps showing your approach. Typically 3-4 steps.</p>

        <div id="processList" class="space-y-4 mb-4">
            <?php if (!empty($processSteps)): ?>
                <?php foreach ($processSteps as $i => $step): ?>
                    <div class="process-row admin-card bg-dark-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-primary font-heading font-bold text-sm">Step <?= $i + 1 ?></span>
                            <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.process-row').remove(); renumberSteps()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        <div class="admin-form-group mb-3">
                            <label class="admin-form-label text-xs">Title</label>
                            <input type="text" class="admin-form-input step-title" value="<?= e($step['title']) ?>" placeholder="e.g. Assessment">
                        </div>
                        <div class="admin-form-group mb-0">
                            <label class="admin-form-label text-xs">Description</label>
                            <textarea class="admin-form-textarea step-content" rows="2" placeholder="What happens in this step"><?= e($step['content']) ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="admin-btn admin-btn-secondary mb-6" onclick="addProcessStep()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Step
        </button>

        <div>
            <button type="button" class="admin-btn admin-btn-primary" onclick="saveProcess()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Process Steps
            </button>
        </div>
    </div>
</div>

<!-- ============ GALLERY TAB ============ -->
<div class="section-panel" id="panel-gallery">
    <div class="admin-card">
        <h2 class="admin-card-title mb-5">Service Gallery</h2>
        <p class="text-white/40 text-sm mb-6">Images showcasing this service. Typically 3 images displayed in a grid.</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="admin-form-group">
                <label class="admin-form-label" for="gallery-title">Section Title</label>
                <input type="text" id="gallery-title" class="admin-form-input" value="<?= e($galleryData['title'] ?? '') ?>" placeholder="e.g. Floor Installation Gallery">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label" for="gallery-subtitle">Section Subtitle</label>
                <input type="text" id="gallery-subtitle" class="admin-form-input" value="<?= e($galleryData['subtitle'] ?? '') ?>" placeholder="e.g. See our recent work">
            </div>
        </div>

        <div id="galleryList" class="space-y-3 mb-4">
            <?php if (!empty($galleryImages)): ?>
                <?php foreach ($galleryImages as $img): ?>
                    <div class="gallery-row flex items-center gap-3 bg-dark-200 rounded-xl p-3">
                        <img src="<?= e($img['src']) ?>" alt="" class="w-16 h-12 object-cover rounded-lg flex-shrink-0 gallery-preview">
                        <input type="text" class="admin-form-input flex-1 gallery-src" value="<?= e($img['src']) ?>" placeholder="Image URL" oninput="updateGalleryPreview(this)">
                        <input type="text" class="admin-form-input w-40 gallery-alt" value="<?= e($img['alt'] ?? '') ?>" placeholder="Alt text">
                        <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.gallery-row').remove()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="admin-btn admin-btn-secondary mb-6" onclick="addGalleryImage()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Image
        </button>

        <div>
            <button type="button" class="admin-btn admin-btn-primary" onclick="saveGallery()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Gallery
            </button>
        </div>
    </div>
</div>

<!-- ============ FAQ TAB ============ -->
<div class="section-panel" id="panel-faq">
    <div class="admin-card">
        <h2 class="admin-card-title mb-5">Service FAQs</h2>
        <p class="text-white/40 text-sm mb-6">Common questions specific to this service, shown in an accordion.</p>

        <div class="admin-form-group">
            <label class="admin-form-label" for="faq-title">Section Title</label>
            <input type="text" id="faq-title" class="admin-form-input" value="<?= e($faqData['title'] ?? '') ?>" placeholder="e.g. Common Questions About Floor Installation">
        </div>

        <div id="faqList" class="space-y-4 mb-4">
            <?php if (!empty($faqList)): ?>
                <?php foreach ($faqList as $faq): ?>
                    <div class="faq-row admin-card bg-dark-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-primary font-heading font-bold text-xs uppercase tracking-wider">Q&A</span>
                            <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.faq-row').remove()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        <div class="admin-form-group mb-3">
                            <label class="admin-form-label text-xs">Question</label>
                            <input type="text" class="admin-form-input faq-question" value="<?= e($faq['q']) ?>" placeholder="e.g. How long does installation take?">
                        </div>
                        <div class="admin-form-group mb-0">
                            <label class="admin-form-label text-xs">Answer</label>
                            <textarea class="admin-form-textarea faq-answer" rows="3" placeholder="Answer to the question"><?= e($faq['a']) ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="admin-btn admin-btn-secondary mb-6" onclick="addFaq()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add FAQ
        </button>

        <div>
            <button type="button" class="admin-btn admin-btn-primary" onclick="saveFaqs()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save FAQs
            </button>
        </div>
    </div>
</div>

<style>
    .section-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: rgba(255,255,255,0.5);
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .section-tab:hover {
        color: rgba(255,255,255,0.8);
        border-color: rgba(255,255,255,0.1);
    }
    .section-tab.active {
        background: rgba(250,164,22,0.1);
        border-color: rgba(250,164,22,0.3);
        color: #FAA416;
    }
    .section-panel {
        display: none;
    }
    .section-panel.active {
        display: block;
    }
</style>

<script>
// Tab switching
document.querySelectorAll('.section-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.section-panel').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById('panel-' + tab.dataset.tab).classList.add('active');
    });
});

// Toast notification
function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-xl text-sm font-medium shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500/20 border border-green-500/30 text-green-400' : 'bg-red-500/20 border border-red-500/30 text-red-400'
    }`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// AJAX helper
async function postAction(data) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const formData = new FormData();
    formData.append('csrf_token', csrfToken);
    for (const [key, val] of Object.entries(data)) {
        if (Array.isArray(val)) {
            val.forEach((item, i) => {
                if (typeof item === 'object') {
                    Object.entries(item).forEach(([k, v]) => formData.append(`${key}[${i}][${k}]`, v));
                } else {
                    formData.append(`${key}[${i}]`, item);
                }
            });
        } else {
            formData.append(key, val);
        }
    }

    const resp = await fetch(window.location.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
    });
    return resp.json();
}

// ---- Overview ----
async function saveOverview() {
    const res = await postAction({
        action: 'save_overview',
        title: document.getElementById('overview-title').value,
        content: document.getElementById('overview-content').value,
    });
    showToast(res.message || res.error, res.success ? 'success' : 'error');
}

// ---- Features ----
function addFeature() {
    const html = `
        <div class="feature-row flex items-center gap-3">
            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <input type="text" class="admin-form-input flex-1 feature-input" value="" placeholder="Feature description">
            <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.feature-row').remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>`;
    document.getElementById('featuresList').insertAdjacentHTML('beforeend', html);
    document.getElementById('featuresList').lastElementChild.querySelector('input').focus();
}

async function saveFeatures() {
    const inputs = document.querySelectorAll('#featuresList .feature-input');
    const features = [];
    inputs.forEach(inp => { if (inp.value.trim()) features.push(inp.value.trim()); });
    const res = await postAction({
        action: 'save_features',
        title: document.getElementById('features-title').value,
        features: features,
    });
    showToast(res.message || res.error, res.success ? 'success' : 'error');
}

// ---- Process Steps ----
function addProcessStep() {
    const count = document.querySelectorAll('#processList .process-row').length + 1;
    const html = `
        <div class="process-row admin-card bg-dark-200 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-primary font-heading font-bold text-sm">Step ${count}</span>
                <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.process-row').remove(); renumberSteps()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            <div class="admin-form-group mb-3">
                <label class="admin-form-label text-xs">Title</label>
                <input type="text" class="admin-form-input step-title" value="" placeholder="e.g. Assessment">
            </div>
            <div class="admin-form-group mb-0">
                <label class="admin-form-label text-xs">Description</label>
                <textarea class="admin-form-textarea step-content" rows="2" placeholder="What happens in this step"></textarea>
            </div>
        </div>`;
    document.getElementById('processList').insertAdjacentHTML('beforeend', html);
}

function renumberSteps() {
    document.querySelectorAll('#processList .process-row').forEach((row, i) => {
        row.querySelector('.text-primary').textContent = 'Step ' + (i + 1);
    });
}

async function saveProcess() {
    const rows = document.querySelectorAll('#processList .process-row');
    const steps = [];
    rows.forEach(row => {
        steps.push({
            title: row.querySelector('.step-title').value,
            content: row.querySelector('.step-content').value,
        });
    });
    const res = await postAction({ action: 'save_process', steps: steps });
    showToast(res.message || res.error, res.success ? 'success' : 'error');
}

// ---- Gallery Images ----
function addGalleryImage() {
    const html = `
        <div class="gallery-row flex items-center gap-3 bg-dark-200 rounded-xl p-3">
            <div class="w-16 h-12 object-cover rounded-lg flex-shrink-0 bg-dark-300 gallery-preview"></div>
            <input type="text" class="admin-form-input flex-1 gallery-src" value="" placeholder="Image URL" oninput="updateGalleryPreview(this)">
            <input type="text" class="admin-form-input w-40 gallery-alt" value="" placeholder="Alt text">
            <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.gallery-row').remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>`;
    document.getElementById('galleryList').insertAdjacentHTML('beforeend', html);
}

function updateGalleryPreview(input) {
    const row = input.closest('.gallery-row');
    const preview = row.querySelector('.gallery-preview');
    const url = input.value.trim();
    if (url && preview.tagName !== 'IMG') {
        const img = document.createElement('img');
        img.src = url;
        img.alt = '';
        img.className = 'w-16 h-12 object-cover rounded-lg flex-shrink-0 gallery-preview';
        preview.replaceWith(img);
    } else if (preview.tagName === 'IMG') {
        preview.src = url;
    }
}

async function saveGallery() {
    const rows = document.querySelectorAll('#galleryList .gallery-row');
    const images = [];
    rows.forEach(row => {
        images.push({
            src: row.querySelector('.gallery-src').value,
            alt: row.querySelector('.gallery-alt').value,
        });
    });
    const res = await postAction({
        action: 'save_gallery',
        title: document.getElementById('gallery-title').value,
        subtitle: document.getElementById('gallery-subtitle').value,
        images: images,
    });
    showToast(res.message || res.error, res.success ? 'success' : 'error');
}

// ---- FAQs ----
function addFaq() {
    const html = `
        <div class="faq-row admin-card bg-dark-200 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-primary font-heading font-bold text-xs uppercase tracking-wider">Q&A</span>
                <button type="button" class="text-red-400/60 hover:text-red-400 transition" onclick="this.closest('.faq-row').remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            <div class="admin-form-group mb-3">
                <label class="admin-form-label text-xs">Question</label>
                <input type="text" class="admin-form-input faq-question" value="" placeholder="e.g. How long does installation take?">
            </div>
            <div class="admin-form-group mb-0">
                <label class="admin-form-label text-xs">Answer</label>
                <textarea class="admin-form-textarea faq-answer" rows="3" placeholder="Answer to the question"></textarea>
            </div>
        </div>`;
    document.getElementById('faqList').insertAdjacentHTML('beforeend', html);
}

async function saveFaqs() {
    const rows = document.querySelectorAll('#faqList .faq-row');
    const faqs = [];
    rows.forEach(row => {
        faqs.push({
            q: row.querySelector('.faq-question').value,
            a: row.querySelector('.faq-answer').value,
        });
    });
    const res = await postAction({
        action: 'save_faqs',
        title: document.getElementById('faq-title').value,
        faqs: faqs,
    });
    showToast(res.message || res.error, res.success ? 'success' : 'error');
}
</script>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
