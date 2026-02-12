<?php
require_once 'includes/content-loader.php';

$pageTitle = 'Frequently Asked Questions | ' . SITE_NAME;
$pageDescription = 'Find answers to common questions about Masterlay Renovations services, pricing, process, and more.';
$currentPage = 'faq';
$heroTitle = 'Frequently Asked <span class="text-gradient">Questions</span>';
$heroSubtitle = 'Everything you need to know about our renovation services.';
$heroBg = IMG . '/hero/luxury-home-main.jpg';
$breadcrumbs = ['Home' => '/', 'FAQ' => ''];

$faqCategories = [
    'general' => 'General',
    'services' => 'Services',
    'pricing' => 'Pricing',
    'process' => 'Process',
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

    <!-- ============ FAQ TABS & CONTENT ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow">
            <!-- Category Tabs -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-12" data-animate="fade-up">
                <?php $first = true; ?>
                <?php foreach ($faqCategories as $key => $label): ?>
                    <button
                        type="button"
                        class="faq-tab px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 <?= $first ? 'bg-primary text-dark' : 'bg-white/5 text-white/60 hover:bg-white/10 hover:text-white' ?>"
                        data-faq-tab="<?= $key ?>"
                    >
                        <?= $label ?>
                    </button>
                <?php $first = false; ?>
                <?php endforeach; ?>
            </div>

            <!-- FAQ Sections -->
            <?php $firstSection = true; ?>
            <?php foreach ($faqCategories as $key => $label): ?>
                <div
                    class="faq-category-section <?= $firstSection ? '' : 'hidden' ?>"
                    data-faq-category="<?= $key ?>"
                >
                    <div class="text-center mb-8">
                        <h2 class="font-heading text-2xl font-bold text-white"><?= $label ?> Questions</h2>
                    </div>

                    <div class="space-y-3" data-animate="stagger-up">
                        <?php if (isset($faqs[$key])): ?>
                            <?php foreach ($faqs[$key] as $faqItem): ?>
                                <?php include 'includes/faq-accordion.php'; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php $firstSection = false; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============ STILL HAVE QUESTIONS ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-narrow text-center">
            <div data-animate="fade-up">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="font-heading text-3xl md:text-4xl font-800 text-white mb-4">Still Have Questions?</h2>
                <p class="text-white/60 mb-8 max-w-lg mx-auto">Can't find the answer you're looking for? Our team is happy to help. Reach out and we'll get back to you within 24 hours.</p>
                <a href="contact" class="btn btn-primary btn-lg">
                    Contact Us
                    <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
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
