<?php
require_once 'includes/config.php';
$pageTitle = 'Gallery | ' . SITE_NAME;
$pageDescription = 'Browse our portfolio of completed renovation projects including flooring, custom stairs, doors, trim, and bathroom renovations across the Greater Toronto Area.';
$currentPage = 'gallery';
$heroTitle = 'Our Work';
$heroSubtitle = 'Browse our portfolio of completed projects';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Gallery' => ''];

// Gallery items
$galleryItems = [
    [
        'src' => IMG . '/gallery/modern-vinyl-plank.jpg',
        'title' => 'Modern Vinyl Plank Installation',
        'category' => 'flooring',
        'type' => 'Flooring',
    ],
    [
        'src' => IMG . '/gallery/custom-oak-staircase.jpg',
        'title' => 'Custom Oak Staircase',
        'category' => 'stairs',
        'type' => 'Stairs',
    ],
    [
        'src' => IMG . '/gallery/luxury-spa-bathroom.jpg',
        'title' => 'Luxury Spa Bathroom',
        'category' => 'bathrooms',
        'type' => 'Bathrooms',
    ],
    [
        'src' => IMG . '/gallery/grand-entry-door.jpg',
        'title' => 'Grand Entry Door',
        'category' => 'doors',
        'type' => 'Doors',
    ],
    [
        'src' => IMG . '/gallery/crown-molding.jpg',
        'title' => 'Crown Molding Detail',
        'category' => 'trim',
        'type' => 'Trim',
    ],
    [
        'src' => IMG . '/gallery/crown-molding-1.jpg',
        'title' => 'Crown Molding Detail',
        'category' => 'trim',
        'type' => 'Trim',
    ],
    [
        'src' => IMG . '/gallery/hardwood-refinishing.jpg',
        'title' => 'Hardwood Floor Refinishing',
        'category' => 'flooring',
        'type' => 'Flooring',
    ],
    [
        'src' => IMG . '/gallery/french-door.jpg',
        'title' => 'French Door Installation',
        'category' => 'doors',
        'type' => 'Doors',
    ],
    [
        'src' => IMG . '/gallery/floating-staircase.jpg',
        'title' => 'Floating Staircase Design',
        'category' => 'stairs',
        'type' => 'Stairs',
    ],
    [
        'src' => IMG . '/gallery/modern-bathroom.jpg',
        'title' => 'Modern Bathroom Renovation',
        'category' => 'bathrooms',
        'type' => 'Bathrooms',
    ],
    [
        'src' => IMG . '/gallery/wainscoting.jpg',
        'title' => 'Wainscoting & Trim Work',
        'category' => 'trim',
        'type' => 'Trim',
    ],
    [
        'src' => IMG . '/gallery/open-concept-floor.jpg',
        'title' => 'Open Concept Floor Installation',
        'category' => 'flooring',
        'type' => 'Flooring',
    ],
    [
        'src' => IMG . '/gallery/contemporary-staircase.jpg',
        'title' => 'Contemporary Staircase Railing',
        'category' => 'stairs',
        'type' => 'Stairs',
    ],
];

$categories = [
    ['slug' => 'all', 'label' => 'All'],
    ['slug' => 'flooring', 'label' => 'Flooring'],
    ['slug' => 'stairs', 'label' => 'Stairs'],
    ['slug' => 'doors', 'label' => 'Doors'],
    ['slug' => 'trim', 'label' => 'Trim'],
    ['slug' => 'bathrooms', 'label' => 'Bathrooms'],
];
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

    <!-- ============ GALLERY SECTION ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <!-- Section Header -->
            <div class="text-center mb-10">
                <span class="section-label justify-center" data-animate="fade-up">Portfolio</span>
                <h2 class="section-heading" data-animate="text-reveal">Project Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Every project is a testament to our commitment to quality. Explore our recent transformations.</p>
            </div>

            <!-- Filter Bar -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-12" id="galleryFilters" data-animate="fade-up">
                <?php foreach ($categories as $cat): ?>
                    <button
                        class="gallery-filter-btn px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 border <?= $cat['slug'] === 'all' ? 'bg-primary text-dark border-primary active' : 'bg-transparent text-white/60 border-white/10 hover:border-primary/30 hover:text-white' ?>"
                        data-filter="<?= $cat['slug'] ?>"
                    >
                        <?= $cat['label'] ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Gallery Grid (Masonry-like with CSS columns) -->
            <div class="gallery-grid columns-1 sm:columns-2 lg:columns-3 gap-5 space-y-5" id="galleryGrid" data-animate="stagger-up">
                <?php foreach ($galleryItems as $index => $item): ?>
                    <div class="gallery-item break-inside-avoid" data-category="<?= $item['category'] ?>">
                        <div class="group relative rounded-2xl overflow-hidden cursor-pointer gallery-image-trigger" data-index="<?= $index ?>">
                            <img
                                src="<?= $item['src'] ?>"
                                alt="<?= htmlspecialchars($item['title']) ?>"
                                class="w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-dark/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400 flex items-end">
                                <div class="p-6 w-full">
                                    <span class="badge mb-2"><?= $item['type'] ?></span>
                                    <h3 class="font-heading text-lg font-bold text-white"><?= htmlspecialchars($item['title']) ?></h3>
                                </div>
                            </div>
                            <!-- Zoom Icon -->
                            <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- No Results Message (hidden by default) -->
            <div class="gallery-no-results hidden text-center py-20" id="galleryNoResults">
                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-white/40 text-lg">No projects found in this category.</p>
            </div>
        </div>
    </section>

    <!-- ============ LIGHTBOX ============ -->
    <div id="galleryLightbox" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true" aria-label="Image lightbox">
        <!-- Backdrop -->
        <div class="lightbox-backdrop absolute inset-0 bg-dark/95 backdrop-blur-sm"></div>

        <!-- Close Button -->
        <button class="lightbox-close absolute top-6 right-6 z-10 w-12 h-12 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-white/20 transition-all" aria-label="Close lightbox">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Navigation Arrows -->
        <button class="lightbox-prev absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-white/20 transition-all" aria-label="Previous image">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button class="lightbox-next absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-white/20 transition-all" aria-label="Next image">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Image Container -->
        <div class="absolute inset-0 flex items-center justify-center p-8 md:p-16">
            <div class="lightbox-content relative max-w-5xl w-full">
                <img id="lightboxImage" src="" alt="" class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
                <div class="mt-4 text-center">
                    <p id="lightboxTitle" class="font-heading text-lg font-bold text-white"></p>
                    <p id="lightboxType" class="text-primary text-sm mt-1"></p>
                    <p id="lightboxCounter" class="text-white/30 text-sm mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ CTA SECTION ============ -->
    <?php include 'includes/cta-section.php'; ?>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
