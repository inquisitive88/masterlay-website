<?php
require_once 'includes/config.php';

$pageTitle = 'Client Testimonials | ' . SITE_NAME;
$pageDescription = 'Read reviews from homeowners across the Greater Toronto Area who trust Masterlay Renovations for premium flooring, stairs, doors, trim, and bathroom renovations.';
$currentPage = 'testimonials';
$heroTitle = 'Client <span class="text-gradient">Testimonials</span>';
$heroSubtitle = 'Hear from homeowners we\'ve helped transform their spaces with precision craftsmanship.';
$heroBg = 'images/hero/luxury-home-main.jpg';
$breadcrumbs = ['Home' => '/', 'Testimonials' => ''];
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

    <!-- ============ AGGREGATE RATING ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow text-center">
            <div data-animate="scale-in">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg class="w-10 h-10 md:w-12 md:h-12 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    <?php endfor; ?>
                </div>
                <p class="font-heading text-4xl md:text-5xl font-800 text-white mb-2">5.0 <span class="text-white/50 text-2xl md:text-3xl font-500">out of 5</span></p>
                <p class="text-white/50 text-lg">Based on 500+ completed projects</p>
            </div>
        </div>
    </section>

    <!-- ============ TESTIMONIALS GRID ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Reviews</span>
                <h2 class="section-heading" data-animate="text-reveal">What Our Clients Say</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Real feedback from real homeowners across the Greater Toronto Area.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-animate="stagger-up">
                <?php foreach ($testimonials as $testimonial): ?>
                    <?php include 'includes/testimonial-card.php'; ?>
                <?php endforeach; ?>
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
