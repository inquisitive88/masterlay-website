<?php
if (!defined('SITE_NAME')) {
    require_once 'includes/content-loader.php';
}
$pageTitle = 'Page Not Found | ' . SITE_NAME;
$pageDescription = 'The page you are looking for could not be found.';
$currentPage = '404';
$heroTitle = 'Page Not Found';
$heroSubtitle = 'The page you\'re looking for doesn\'t exist';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', '404' => ''];

if (!headers_sent()) {
    header('HTTP/1.1 404 Not Found');
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include 'includes/loader.php'; ?>
<?php include 'includes/header.php'; ?>

<main>
    <?php include 'includes/page-hero.php'; ?>

    <section class="section-padding bg-dark">
        <div class="container-wide text-center">
            <div class="max-w-lg mx-auto" data-animate="fade-up">
                <div class="text-8xl font-heading font-bold text-primary/20 mb-6">404</div>
                <h2 class="font-heading text-2xl font-bold mb-4">Oops! Page not found</h2>
                <p class="text-white/50 mb-8">The page you're looking for might have been moved, deleted, or doesn't exist. Let's get you back on track.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/" class="btn-primary">
                        <span>Back to Home</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </a>
                    <a href="/contact" class="btn-outline">
                        <span>Contact Us</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/cta-section.php'; ?>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
