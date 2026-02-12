<?php
/**
 * Public - Dynamic Service Detail Page
 * Accessed via /services/{slug} (nginx rewrite)
 * If a legacy static PHP file exists for this slug, it is included directly.
 * Otherwise, the dynamic template renders the page from CMS database content.
 */
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    require_once 'includes/content-loader.php';
    header('HTTP/1.1 404 Not Found');
    include '404.php';
    exit;
}

// Check if a legacy static service file exists (e.g., services/floor-installation.php)
$staticFile = __DIR__ . '/services/' . basename($slug) . '.php';
if (file_exists($staticFile)) {
    // Change working directory so relative paths (../includes/) resolve correctly
    chdir(__DIR__ . '/services');
    include $staticFile;
    exit;
}

// No static file — load dynamic template from CMS
require_once 'includes/content-loader.php';

// Find current service from loaded services array (already DB-loaded via content-loader)
$currentService = null;
$currentServiceId = null;
foreach ($services as $s) {
    if ($s['slug'] === $slug) {
        $currentService = $s;
        break;
    }
}

// If not found in services array, try direct DB lookup (inactive or edge case)
if (!$currentService && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM ml_cms_services WHERE slug = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$slug]);
    $dbService = $stmt->fetch();
    if ($dbService) {
        $currentService = [
            'slug'        => $dbService['slug'],
            'title'       => $dbService['title'],
            'short'       => $dbService['short_description'],
            'description' => $dbService['full_description'],
            'icon'        => $dbService['icon_svg_path'],
            'image'       => $dbService['image_url'],
            'hero_image'  => $dbService['hero_image_url'],
        ];
        $currentServiceId = $dbService['id'];
    }
}

if (!$currentService) {
    header('HTTP/1.1 404 Not Found');
    include '404.php';
    exit;
}

// Get the service ID from DB for loading detail sections
if (!$currentServiceId && $pdo) {
    $stmt = $pdo->prepare("SELECT id FROM ml_cms_services WHERE slug = ? LIMIT 1");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    $currentServiceId = $row ? (int)$row['id'] : null;
}

// Load detail sections from DB
$sections = [];
if ($currentServiceId && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM ml_cms_service_detail_sections WHERE service_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$currentServiceId]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Group sections by type for easy access
$sectionsByType = [];
foreach ($sections as $sec) {
    $sectionsByType[$sec['section_type']][] = $sec;
}

// Page setup — use absolute basePath since browser URL is /services/{slug}
$basePath = '/';
$pageTitle = $currentService['title'] . ' | ' . cms_setting('site_name', SITE_NAME);
$pageDescription = $currentService['description'] ?: $currentService['short'];
$currentPage = 'services';
$loadFaqJs = true;
$heroTitle = $currentService['title'];
$heroSubtitle = $currentService['short'];
$heroBg = $currentService['hero_image'] ?: IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Services' => '/services', $currentService['title'] => ''];
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include 'includes/loader.php'; ?>
<?php include 'includes/header.php'; ?>

<main>
    <!-- ============ PAGE HERO ============ -->
    <?php include 'includes/page-hero.php'; ?>

    <!-- ============ SERVICE OVERVIEW ============ -->
    <?php
    $overviewSection = $sectionsByType['overview'][0] ?? null;
    $featuresSection = $sectionsByType['features'][0] ?? null;

    // Parse features from JSON content
    $featuresList = [];
    if ($featuresSection && $featuresSection['content']) {
        $decoded = json_decode($featuresSection['content'], true);
        if (is_array($decoded)) {
            $featuresList = $decoded;
        } else {
            // Treat as newline-separated list
            $featuresList = array_filter(array_map('trim', explode("\n", $featuresSection['content'])));
        }
    }
    ?>
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Text Content -->
                <div data-animate="slide-left">
                    <span class="section-label" data-animate="fade-up">Service Overview</span>
                    <?php if ($overviewSection): ?>
                        <h2 class="section-heading mb-6" data-animate="text-reveal"><?= htmlspecialchars($overviewSection['title']) ?></h2>
                        <?php
                        // Split content into paragraphs
                        $paragraphs = array_filter(array_map('trim', explode("\n\n", $overviewSection['content'])));
                        foreach ($paragraphs as $p): ?>
                            <p class="text-white/60 mb-4" data-animate="fade-up"><?= nl2br(htmlspecialchars($p)) ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h2 class="section-heading mb-6" data-animate="text-reveal"><?= htmlspecialchars($currentService['title']) ?></h2>
                        <?php if ($currentService['description']): ?>
                            <p class="text-white/60 mb-4" data-animate="fade-up"><?= nl2br(htmlspecialchars($currentService['description'])) ?></p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!empty($featuresList)): ?>
                        <h3 class="font-heading text-lg font-bold text-white mb-4 mt-8" data-animate="fade-up">
                            <?= htmlspecialchars($featuresSection['title'] ?? "What's Included") ?>
                        </h3>
                        <ul class="space-y-3" data-animate="stagger-up">
                            <?php foreach ($featuresList as $feature): ?>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-white/70"><?= htmlspecialchars(is_array($feature) ? $feature['text'] : $feature) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= htmlspecialchars($currentService['image'] ?: $currentService['hero_image'] ?: IMG . '/hero/services-page.jpg') ?>" alt="<?= htmlspecialchars($currentService['title']) ?>" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ OUR APPROACH / PROCESS ============ -->
    <?php
    $processSteps = [];
    if (!empty($sectionsByType['process'])) {
        foreach ($sectionsByType['process'] as $proc) {
            // Each process section row = one step
            $processSteps[] = [
                'title'   => $proc['title'],
                'content' => $proc['content'],
            ];
        }
    }
    ?>
    <?php if (!empty($processSteps)): ?>
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Approach</span>
                <h2 class="section-heading" data-animate="text-reveal">Our Process</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">A proven step-by-step process that ensures a flawless result every time.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?= min(count($processSteps), 4) ?> gap-8 relative" data-animate="stagger-up">
                <?php if (count($processSteps) > 1): ?>
                    <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>
                <?php endif; ?>

                <?php foreach ($processSteps as $i => $step): ?>
                    <div class="process-step">
                        <div class="process-number"><?= $i + 1 ?></div>
                        <h3 class="font-heading text-lg font-bold mb-2"><?= htmlspecialchars($step['title']) ?></h3>
                        <p class="text-white/50 text-sm"><?= htmlspecialchars($step['content']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============ GALLERY ============ -->
    <?php
    $galleryImages = [];
    if (!empty($sectionsByType['gallery'])) {
        foreach ($sectionsByType['gallery'] as $gal) {
            // Content stores JSON array of image objects: [{src, alt}]
            $decoded = json_decode($gal['content'], true);
            if (is_array($decoded)) {
                $galleryImages = array_merge($galleryImages, $decoded);
            } else if ($gal['content']) {
                // Treat as newline-separated URLs
                $urls = array_filter(array_map('trim', explode("\n", $gal['content'])));
                foreach ($urls as $url) {
                    $galleryImages[] = ['src' => $url, 'alt' => $gal['title'] ?? $currentService['title']];
                }
            }
        }
    }
    ?>
    <?php if (!empty($galleryImages)): ?>
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal"><?= htmlspecialchars($sectionsByType['gallery'][0]['title'] ?? $currentService['title'] . ' Gallery') ?></h2>
                <?php if (!empty($sectionsByType['gallery'][0]['subtitle'])): ?>
                    <p class="section-subheading mx-auto" data-animate="fade-up"><?= htmlspecialchars($sectionsByType['gallery'][0]['subtitle']) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <?php foreach ($galleryImages as $img): ?>
                    <div class="rounded-2xl overflow-hidden">
                        <img src="<?= htmlspecialchars(is_array($img) ? $img['src'] : $img) ?>" alt="<?= htmlspecialchars(is_array($img) ? ($img['alt'] ?? $currentService['title']) : $currentService['title']) ?>" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============ FAQ ============ -->
    <?php
    $serviceFaqs = [];
    if (!empty($sectionsByType['faq'])) {
        foreach ($sectionsByType['faq'] as $faqSec) {
            // Content stores JSON array of FAQ objects: [{q, a}]
            $decoded = json_decode($faqSec['content'], true);
            if (is_array($decoded)) {
                $serviceFaqs = array_merge($serviceFaqs, $decoded);
            }
        }
    }
    ?>
    <?php if (!empty($serviceFaqs)): ?>
    <section class="section-padding bg-dark-100">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal"><?= htmlspecialchars($sectionsByType['faq'][0]['title'] ?? 'Common Questions About ' . $currentService['title']) ?></h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php foreach ($serviceFaqs as $faqItem):
                    include 'includes/faq-accordion.php';
                endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============ RELATED SERVICES ============ -->
    <section class="section-padding <?= !empty($serviceFaqs) ? 'bg-dark' : 'bg-dark-100' ?>">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Explore More</span>
                <h2 class="section-heading" data-animate="text-reveal">Related Services</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Discover other ways we can transform your home.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <?php
                $relatedCount = 0;
                foreach ($services as $service):
                    if ($service['slug'] === $slug) continue;
                    if ($relatedCount >= 3) break;
                    include 'includes/service-card.php';
                    $relatedCount++;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- ============ CTA SECTION ============ -->
    <?php include 'includes/cta-section.php'; ?>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
