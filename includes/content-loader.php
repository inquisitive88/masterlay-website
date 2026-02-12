<?php
/**
 * Public Site - Content Loader
 * Drop-in replacement for config.php
 * Loads content from CMS database with static config.php as fallback
 *
 * Usage: Replace `require_once 'includes/config.php'` with `require_once 'includes/content-loader.php'`
 * All existing variables ($services, $testimonials, $faqs, $navigation) remain available
 */

// Always load static config first (defines constants, arrays, helper functions, mailer)
require_once __DIR__ . '/config.php';

// Try to connect to the database
require_once __DIR__ . '/db-connection.php';

// Site settings from DB (supplements constants, does not replace them)
$site_settings = [];

if ($pdo) {
    try {
        // ---- Load Settings ----
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM ml_cms_settings");
        if ($stmt) {
            foreach ($stmt->fetchAll() as $row) {
                $site_settings[$row['setting_key']] = $row['setting_value'];
            }
        }

        // ---- Load Services ----
        $stmt = $pdo->query("SELECT * FROM ml_cms_services WHERE is_active = 1 ORDER BY sort_order ASC");
        if ($stmt) {
            $db_services = $stmt->fetchAll();
            if (!empty($db_services)) {
                $services = array_map(function ($row) {
                    return [
                        'slug'        => $row['slug'],
                        'title'       => $row['title'],
                        'short'       => $row['short_description'],
                        'description' => $row['full_description'],
                        'icon'        => $row['icon_svg_path'],
                        'image'       => $row['image_url'],
                        'hero_image'  => $row['hero_image_url'],
                    ];
                }, $db_services);
            }
        }

        // ---- Load Testimonials ----
        $stmt = $pdo->query("SELECT * FROM ml_cms_testimonials WHERE is_active = 1 ORDER BY sort_order ASC");
        if ($stmt) {
            $db_testimonials = $stmt->fetchAll();
            if (!empty($db_testimonials)) {
                $testimonials = array_map(function ($row) {
                    return [
                        'name'    => $row['client_name'],
                        'location' => $row['location'],
                        'rating'  => (int)$row['rating'],
                        'text'    => $row['testimonial_text'],
                        'project' => $row['project_type'],
                    ];
                }, $db_testimonials);
            }
        }

        // ---- Load FAQs ----
        $stmt = $pdo->query("SELECT * FROM ml_cms_faqs WHERE is_active = 1 ORDER BY category, sort_order ASC");
        if ($stmt) {
            $db_faqs_raw = $stmt->fetchAll();
            if (!empty($db_faqs_raw)) {
                $faqs = [];
                foreach ($db_faqs_raw as $row) {
                    $faqs[$row['category']][] = [
                        'q' => $row['question'],
                        'a' => $row['answer'],
                    ];
                }
            }
        }

        // ---- Load Gallery ----
        $stmt = $pdo->query("SELECT * FROM ml_cms_gallery WHERE is_active = 1 ORDER BY sort_order ASC");
        if ($stmt) {
            $db_gallery = $stmt->fetchAll();
            if (!empty($db_gallery)) {
                $galleryItems = array_map(function ($row) {
                    return [
                        'src'      => $row['image_url'],
                        'title'    => $row['title'],
                        'category' => $row['category'],
                        'type'     => $row['type_label'],
                    ];
                }, $db_gallery);
            }
        }

        // ---- Load Gallery Categories ----
        $stmt = $pdo->query("SELECT * FROM ml_cms_gallery_categories ORDER BY sort_order ASC");
        if ($stmt) {
            $db_gallery_cats = $stmt->fetchAll();
            if (!empty($db_gallery_cats)) {
                $galleryCategories = array_merge(
                    [['slug' => 'all', 'label' => 'All']],
                    array_map(function ($row) {
                        return ['slug' => $row['slug'], 'label' => $row['label']];
                    }, $db_gallery_cats)
                );
            }
        }

        // ---- Load Navigation ----
        $stmt = $pdo->query("SELECT * FROM ml_cms_navigation WHERE is_active = 1 ORDER BY sort_order ASC");
        if ($stmt) {
            $db_nav = $stmt->fetchAll();
            if (!empty($db_nav)) {
                $navigation = array_map(function ($row) {
                    return [
                        'label'    => $row['label'],
                        'url'      => $row['url'],
                        'page'     => $row['page_identifier'],
                        'dropdown' => (bool)$row['has_dropdown'],
                    ];
                }, $db_nav);
            }
        }

        // ---- Load SEO for current page ----
        // Available to head.php via $cms_seo variable
        if (isset($currentPage)) {
            $seoStmt = $pdo->prepare("SELECT * FROM ml_cms_seo WHERE page_identifier = ? LIMIT 1");
            $seoStmt->execute([$currentPage]);
            $cms_seo = $seoStmt->fetch();
        }

    } catch (PDOException $e) {
        error_log('Content loader DB error: ' . $e->getMessage());
        // Silently fall back to static config data
    }
}

/**
 * Helper: Get a site setting with fallback
 * Usage: cms_setting('site_name', SITE_NAME)
 */
function cms_setting(string $key, string $fallback = ''): string {
    global $site_settings;
    return $site_settings[$key] ?? $fallback;
}
