<?php
/**
 * CMS Admin - Database Table Definitions
 * Creates all ml_cms_* tables if they don't exist
 * Also ensures ml_admin_users table exists for auth
 */

if (!isset($pdo)) {
    die('Database connection required.');
}

$cms_tables = [
    // Auth tables (shared with estimator portal)
    'ml_admin_users' => "
        CREATE TABLE IF NOT EXISTS ml_admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    'ml_admin_remember_tokens' => "
        CREATE TABLE IF NOT EXISTS ml_admin_remember_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            selector CHAR(24) NOT NULL,
            hashed_validator CHAR(64) NOT NULL,
            expires DATETIME NOT NULL,
            KEY selector (selector)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    'ml_admin_password_resets' => "
        CREATE TABLE IF NOT EXISTS ml_admin_password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token CHAR(64) NOT NULL,
            expires DATETIME NOT NULL,
            INDEX token (token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Services
    'ml_cms_services' => "
        CREATE TABLE IF NOT EXISTS ml_cms_services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(100) NOT NULL UNIQUE,
            title VARCHAR(200) NOT NULL,
            short_description TEXT,
            full_description TEXT,
            icon_svg_path TEXT,
            image_url VARCHAR(500),
            hero_image_url VARCHAR(500),
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_sort (sort_order),
            INDEX idx_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Service Detail Sections
    'ml_cms_service_detail_sections' => "
        CREATE TABLE IF NOT EXISTS ml_cms_service_detail_sections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            service_id INT NOT NULL,
            section_type ENUM('overview','features','process','gallery','faq') NOT NULL,
            title VARCHAR(300),
            subtitle VARCHAR(500),
            content LONGTEXT,
            sort_order INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY idx_service (service_id),
            KEY idx_sort (service_id, sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Testimonials
    'ml_cms_testimonials' => "
        CREATE TABLE IF NOT EXISTS ml_cms_testimonials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            client_name VARCHAR(200) NOT NULL,
            location VARCHAR(200),
            rating TINYINT DEFAULT 5,
            testimonial_text TEXT NOT NULL,
            project_type VARCHAR(200),
            is_featured TINYINT(1) DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            sort_order INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_featured (is_featured, is_active),
            INDEX idx_sort (sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS FAQ Categories
    'ml_cms_faq_categories' => "
        CREATE TABLE IF NOT EXISTS ml_cms_faq_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(50) NOT NULL UNIQUE,
            label VARCHAR(100) NOT NULL,
            sort_order INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS FAQs
    'ml_cms_faqs' => "
        CREATE TABLE IF NOT EXISTS ml_cms_faqs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(50) NOT NULL,
            question TEXT NOT NULL,
            answer TEXT NOT NULL,
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_category (category, sort_order),
            INDEX idx_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Gallery Categories
    'ml_cms_gallery_categories' => "
        CREATE TABLE IF NOT EXISTS ml_cms_gallery_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(50) NOT NULL UNIQUE,
            label VARCHAR(100) NOT NULL,
            sort_order INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Gallery
    'ml_cms_gallery' => "
        CREATE TABLE IF NOT EXISTS ml_cms_gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            image_url VARCHAR(500) NOT NULL,
            r2_key VARCHAR(500),
            title VARCHAR(300) NOT NULL,
            category VARCHAR(100) NOT NULL,
            type_label VARCHAR(100),
            alt_text VARCHAR(500),
            description TEXT,
            is_featured TINYINT(1) DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            sort_order INT DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_category (category, sort_order),
            INDEX idx_featured (is_featured, is_active),
            INDEX idx_sort (sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Blog Posts
    'ml_cms_blog_posts' => "
        CREATE TABLE IF NOT EXISTS ml_cms_blog_posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(200) NOT NULL UNIQUE,
            title VARCHAR(300) NOT NULL,
            excerpt TEXT,
            content LONGTEXT,
            featured_image_url VARCHAR(500),
            r2_key VARCHAR(500),
            category VARCHAR(100),
            author_name VARCHAR(200) DEFAULT 'Masterlay Renovations',
            status ENUM('draft','published','archived') DEFAULT 'draft',
            published_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_status (status, published_at),
            INDEX idx_slug (slug),
            INDEX idx_category (category)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS SEO
    'ml_cms_seo' => "
        CREATE TABLE IF NOT EXISTS ml_cms_seo (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page_identifier VARCHAR(100) NOT NULL UNIQUE,
            meta_title VARCHAR(300),
            meta_description VARCHAR(500),
            og_title VARCHAR(300),
            og_description VARCHAR(500),
            og_image_url VARCHAR(500),
            canonical_url VARCHAR(500),
            extra_meta TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Settings
    'ml_cms_settings' => "
        CREATE TABLE IF NOT EXISTS ml_cms_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            setting_group VARCHAR(50) DEFAULT 'general',
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_group (setting_group)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Pages (content blocks)
    'ml_cms_pages' => "
        CREATE TABLE IF NOT EXISTS ml_cms_pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page_identifier VARCHAR(100) NOT NULL,
            section_identifier VARCHAR(100) NOT NULL,
            title VARCHAR(300),
            subtitle VARCHAR(500),
            content LONGTEXT,
            image_url VARCHAR(500),
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE INDEX idx_page_section (page_identifier, section_identifier)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // CMS Navigation
    'ml_cms_navigation' => "
        CREATE TABLE IF NOT EXISTS ml_cms_navigation (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(100) NOT NULL,
            url VARCHAR(300) NOT NULL,
            page_identifier VARCHAR(100),
            has_dropdown TINYINT(1) DEFAULT 0,
            parent_id INT DEFAULT NULL,
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_sort (sort_order),
            INDEX idx_parent (parent_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",

    // Quote Pricing
    'ml_cms_quote_pricing' => "
        CREATE TABLE IF NOT EXISTS ml_cms_quote_pricing (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pricing_key VARCHAR(100) NOT NULL UNIQUE,
            pricing_value DECIMAL(10,2) NOT NULL,
            service_type VARCHAR(50) NOT NULL,
            label VARCHAR(200) NOT NULL,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",
];

// Create tables
foreach ($cms_tables as $name => $sql) {
    try {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?"
        );
        $check->execute([$name]);
        if (!$check->fetchColumn()) {
            $pdo->exec($sql);
        }
    } catch (PDOException $e) {
        error_log("CMS table creation failed for {$name}: " . $e->getMessage());
    }
}
