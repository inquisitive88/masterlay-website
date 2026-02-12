<?php
/**
 * CMS Admin - Seed Script
 * Migrates hardcoded data from config.php into the CMS database tables
 * Safe to run multiple times (uses INSERT IGNORE / ON DUPLICATE KEY)
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Seed Data';
$adminBreadcrumb = ['Dashboard' => '/admin', 'Seed Data' => ''];

$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    // Load config.php to get hardcoded data
    // We need a temporary scope to load the config arrays
    $configPath = dirname(__DIR__) . '/includes/config.php';
    if (!file_exists($configPath)) {
        set_flash('error', 'config.php not found.');
        redirect('/admin/seed');
    }

    // config.php defines constants and $services, $testimonials, $faqs, $navigation
    require_once $configPath;

    $sections = $_POST['sections'] ?? [];

    // ---- Seed Settings ----
    if (in_array('settings', $sections)) {
        $settings = [
            ['site_name', SITE_NAME, 'company'],
            ['site_tagline', SITE_TAGLINE, 'company'],
            ['site_url', SITE_URL, 'company'],
            ['phone', PHONE, 'company'],
            ['phone_display', PHONE_DISPLAY, 'company'],
            ['email', EMAIL, 'company'],
            ['address', ADDRESS, 'company'],
            ['hours', HOURS, 'company'],
            ['year_established', (string)YEAR_ESTABLISHED, 'company'],
            ['instagram_url', INSTAGRAM, 'social'],
            ['facebook_url', FACEBOOK, 'social'],
            ['tiktok_url', TIKTOK, 'social'],
        ];

        $stmt = $pdo->prepare("INSERT INTO ml_cms_settings (setting_key, setting_value, setting_group) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        $count = 0;
        foreach ($settings as $s) {
            $stmt->execute($s);
            $count++;
        }
        $results[] = "Settings: {$count} items seeded.";
    }

    // ---- Seed Services ----
    if (in_array('services', $sections)) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO ml_cms_services (slug, title, short_description, full_description, icon_svg_path, image_url, hero_image_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $count = 0;
        foreach ($services as $i => $s) {
            $stmt->execute([
                $s['slug'],
                $s['title'],
                $s['short'],
                $s['description'],
                $s['icon'],
                $s['image'],
                $s['hero_image'],
                $i,
            ]);
            $count++;
        }
        $results[] = "Services: {$count} items seeded.";
    }

    // ---- Seed Testimonials ----
    if (in_array('testimonials', $sections)) {
        $stmt = $pdo->prepare("INSERT INTO ml_cms_testimonials (client_name, location, rating, testimonial_text, project_type, sort_order) SELECT ?, ?, ?, ?, ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM ml_cms_testimonials WHERE client_name = ? AND testimonial_text = ?)");
        $count = 0;
        foreach ($testimonials as $i => $t) {
            $stmt->execute([
                $t['name'],
                $t['location'],
                $t['rating'],
                $t['text'],
                $t['project'],
                $i,
                $t['name'],
                $t['text'],
            ]);
            $count++;
        }
        $results[] = "Testimonials: {$count} items processed.";
    }

    // ---- Seed FAQs ----
    if (in_array('faqs', $sections)) {
        // Seed FAQ categories
        $catStmt = $pdo->prepare("INSERT IGNORE INTO ml_cms_faq_categories (slug, label, sort_order) VALUES (?, ?, ?)");
        $faqCategories = [
            ['general', 'General', 0],
            ['services', 'Services', 1],
            ['pricing', 'Pricing', 2],
            ['process', 'Process', 3],
        ];
        foreach ($faqCategories as $c) {
            $catStmt->execute($c);
        }

        // Seed FAQ items
        $faqStmt = $pdo->prepare("INSERT INTO ml_cms_faqs (category, question, answer, sort_order) SELECT ?, ?, ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM ml_cms_faqs WHERE category = ? AND question = ?)");
        $count = 0;
        foreach ($faqs as $category => $items) {
            foreach ($items as $i => $faq) {
                $faqStmt->execute([
                    $category,
                    $faq['q'],
                    $faq['a'],
                    $i,
                    $category,
                    $faq['q'],
                ]);
                $count++;
            }
        }
        $results[] = "FAQs: {$count} items processed (4 categories).";
    }

    // ---- Seed Gallery ----
    if (in_array('gallery', $sections)) {
        // Gallery categories
        $galCatStmt = $pdo->prepare("INSERT IGNORE INTO ml_cms_gallery_categories (slug, label, sort_order) VALUES (?, ?, ?)");
        $galCategories = [
            ['flooring', 'Flooring', 0],
            ['stairs', 'Stairs', 1],
            ['doors', 'Doors', 2],
            ['trim', 'Trim', 3],
            ['bathrooms', 'Bathrooms', 4],
        ];
        foreach ($galCategories as $c) {
            $galCatStmt->execute($c);
        }

        // Gallery items (from gallery.php hardcoded data)
        $galleryItems = [
            ['src' => CMS_IMG . '/gallery/modern-vinyl-plank.jpg', 'title' => 'Modern Vinyl Plank Installation', 'category' => 'flooring', 'type' => 'Flooring'],
            ['src' => CMS_IMG . '/gallery/custom-oak-staircase.jpg', 'title' => 'Custom Oak Staircase', 'category' => 'stairs', 'type' => 'Stairs'],
            ['src' => CMS_IMG . '/gallery/luxury-spa-bathroom.jpg', 'title' => 'Luxury Spa Bathroom', 'category' => 'bathrooms', 'type' => 'Bathrooms'],
            ['src' => CMS_IMG . '/gallery/grand-entry-door.jpg', 'title' => 'Grand Entry Door', 'category' => 'doors', 'type' => 'Doors'],
            ['src' => CMS_IMG . '/gallery/crown-molding.jpg', 'title' => 'Crown Molding Detail', 'category' => 'trim', 'type' => 'Trim'],
            ['src' => CMS_IMG . '/gallery/crown-molding-1.jpg', 'title' => 'Crown Molding Detail', 'category' => 'trim', 'type' => 'Trim'],
            ['src' => CMS_IMG . '/gallery/hardwood-refinishing.jpg', 'title' => 'Hardwood Floor Refinishing', 'category' => 'flooring', 'type' => 'Flooring'],
            ['src' => CMS_IMG . '/gallery/french-door.jpg', 'title' => 'French Door Installation', 'category' => 'doors', 'type' => 'Doors'],
            ['src' => CMS_IMG . '/gallery/floating-staircase.jpg', 'title' => 'Floating Staircase Design', 'category' => 'stairs', 'type' => 'Stairs'],
            ['src' => CMS_IMG . '/gallery/modern-bathroom.jpg', 'title' => 'Modern Bathroom Renovation', 'category' => 'bathrooms', 'type' => 'Bathrooms'],
            ['src' => CMS_IMG . '/gallery/wainscoting.jpg', 'title' => 'Wainscoting & Trim Work', 'category' => 'trim', 'type' => 'Trim'],
            ['src' => CMS_IMG . '/gallery/open-concept-floor.jpg', 'title' => 'Open Concept Floor Installation', 'category' => 'flooring', 'type' => 'Flooring'],
            ['src' => CMS_IMG . '/gallery/contemporary-staircase.jpg', 'title' => 'Contemporary Staircase Railing', 'category' => 'stairs', 'type' => 'Stairs'],
        ];

        $galStmt = $pdo->prepare("INSERT INTO ml_cms_gallery (image_url, title, category, type_label, alt_text, sort_order) SELECT ?, ?, ?, ?, ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM ml_cms_gallery WHERE image_url = ?)");
        $count = 0;
        foreach ($galleryItems as $i => $item) {
            $galStmt->execute([
                $item['src'],
                $item['title'],
                $item['category'],
                $item['type'],
                $item['title'],
                $i,
                $item['src'],
            ]);
            $count++;
        }
        $results[] = "Gallery: {$count} items processed (5 categories).";
    }

    // ---- Seed Navigation ----
    if (in_array('navigation', $sections)) {
        $navStmt = $pdo->prepare("INSERT IGNORE INTO ml_cms_navigation (label, url, page_identifier, has_dropdown, sort_order) VALUES (?, ?, ?, ?, ?)");
        $count = 0;
        foreach ($navigation as $i => $nav) {
            $navStmt->execute([
                $nav['label'],
                $nav['url'],
                $nav['page'],
                !empty($nav['dropdown']) ? 1 : 0,
                $i,
            ]);
            $count++;
        }
        $results[] = "Navigation: {$count} items seeded.";
    }

    // ---- Seed SEO ----
    if (in_array('seo', $sections)) {
        $seoData = [
            ['index', 'Premium Renovation Services - ' . SITE_NAME, 'Transform your home with expert flooring, custom stairs, doors, trim, and bathroom renovations across the Greater Toronto Area.'],
            ['about', 'About Us - ' . SITE_NAME, 'Learn about Masterlay Renovations — our story, values, and commitment to transforming homes across the Greater Toronto Area since 2018.'],
            ['services', 'Our Services - ' . SITE_NAME, 'Explore our full range of renovation services including flooring installation, custom stairs, door installation, trim work, basement and bathroom renovations.'],
            ['gallery', 'Gallery - ' . SITE_NAME, 'Browse our portfolio of completed renovation projects including flooring, custom stairs, doors, trim, and bathroom renovations across the Greater Toronto Area.'],
            ['contact', 'Contact Us - ' . SITE_NAME, 'Get in touch with Masterlay Renovations for a free consultation on your next flooring, stairs, doors, or bathroom renovation project in the GTA.'],
            ['financing', 'Financing Options - ' . SITE_NAME, 'Make your renovation affordable with flexible financing options through Financeit. Apply online for instant approval and start your project today.'],
            ['testimonials', 'Client Testimonials - ' . SITE_NAME, 'Read reviews from homeowners across the Greater Toronto Area who trust Masterlay Renovations for premium flooring, stairs, doors, trim, and bathroom renovations.'],
            ['blog', 'Renovation Insights & Blog - ' . SITE_NAME, 'Tips, trends, and project inspiration from Masterlay Renovations.'],
            ['faq', 'FAQ - ' . SITE_NAME, 'Find answers to frequently asked questions about our renovation services, pricing, process, and more.'],
        ];

        $seoStmt = $pdo->prepare("INSERT INTO ml_cms_seo (page_identifier, meta_title, meta_description) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE meta_title = VALUES(meta_title), meta_description = VALUES(meta_description)");
        $count = 0;
        foreach ($seoData as $s) {
            $seoStmt->execute($s);
            $count++;
        }
        $results[] = "SEO: {$count} pages seeded.";
    }

    if (!empty($results)) {
        set_flash('success', 'Seed completed: ' . implode(' | ', $results));
    } else {
        set_flash('warning', 'No sections selected.');
    }
    redirect('/admin/seed');
}

// Get current counts
$counts = [];
$tables = ['ml_cms_settings', 'ml_cms_services', 'ml_cms_testimonials', 'ml_cms_faqs', 'ml_cms_gallery', 'ml_cms_navigation', 'ml_cms_seo'];
foreach ($tables as $table) {
    try {
        $counts[$table] = (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    } catch (PDOException $e) {
        $counts[$table] = 0;
    }
}

include __DIR__ . '/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Seed Database</h1>
        <p class="text-white/40 text-sm mt-1">Migrate hardcoded content from config.php into the CMS database</p>
    </div>
    <a href="/admin" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Dashboard
    </a>
</div>

<!-- Current Counts -->
<div class="admin-card mb-6">
    <h2 class="admin-card-title mb-4">Current Database State</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
        <?php
        $tableLabels = [
            'ml_cms_settings' => 'Settings',
            'ml_cms_services' => 'Services',
            'ml_cms_testimonials' => 'Testimonials',
            'ml_cms_faqs' => 'FAQs',
            'ml_cms_gallery' => 'Gallery',
            'ml_cms_navigation' => 'Navigation',
            'ml_cms_seo' => 'SEO Pages',
        ];
        foreach ($tableLabels as $table => $label): ?>
            <div class="bg-dark-100 rounded-lg p-3 text-center">
                <div class="font-heading text-xl font-bold <?= $counts[$table] > 0 ? 'text-green-400' : 'text-white/30' ?>"><?= $counts[$table] ?></div>
                <div class="text-white/40 text-xs mt-1"><?= $label ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Seed Form -->
<form method="POST" action="/admin/seed">
    <?= csrf_field() ?>

    <div class="admin-card mb-6">
        <h2 class="admin-card-title mb-4">Select Data to Seed</h2>
        <p class="text-white/40 text-sm mb-5">Choose which content to migrate from the hardcoded config to the database. Existing records will not be duplicated.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            $seedOptions = [
                'settings'     => ['label' => 'Settings', 'desc' => 'Company info, social links', 'count' => $counts['ml_cms_settings']],
                'services'     => ['label' => 'Services', 'desc' => '7 services with details', 'count' => $counts['ml_cms_services']],
                'testimonials' => ['label' => 'Testimonials', 'desc' => '6 client reviews', 'count' => $counts['ml_cms_testimonials']],
                'faqs'         => ['label' => 'FAQs', 'desc' => '16 FAQs in 4 categories', 'count' => $counts['ml_cms_faqs']],
                'gallery'      => ['label' => 'Gallery', 'desc' => '13 images in 5 categories', 'count' => $counts['ml_cms_gallery']],
                'navigation'   => ['label' => 'Navigation', 'desc' => '7 menu items', 'count' => $counts['ml_cms_navigation']],
                'seo'          => ['label' => 'SEO', 'desc' => '9 pages with meta data', 'count' => $counts['ml_cms_seo']],
            ];
            foreach ($seedOptions as $key => $opt): ?>
                <label class="flex items-start gap-3 p-4 rounded-xl border border-white/5 hover:border-primary/20 cursor-pointer transition bg-dark-100">
                    <input type="checkbox" name="sections[]" value="<?= $key ?>" <?= $opt['count'] === 0 ? 'checked' : '' ?> class="mt-1 w-4 h-4 rounded border-white/20 bg-dark text-primary focus:ring-primary/30">
                    <div>
                        <div class="text-sm font-medium text-white"><?= $opt['label'] ?></div>
                        <div class="text-xs text-white/40 mt-0.5"><?= $opt['desc'] ?></div>
                        <?php if ($opt['count'] > 0): ?>
                            <div class="text-xs text-green-400/70 mt-1"><?= $opt['count'] ?> records exist</div>
                        <?php endif; ?>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Run Seed Migration
    </button>
</form>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
