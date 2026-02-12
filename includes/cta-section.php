<?php
$ctaTitle = $ctaTitle ?? "Ready to Transform Your Space?";
$ctaText = $ctaText ?? "Get a free, no-obligation estimate for your renovation project. Let's bring your vision to life.";
$ctaBasePath = $basePath ?? '';
?>
<section class="relative overflow-hidden section-padding">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-dark to-dark"></div>
    <div class="absolute inset-0 noise-overlay"></div>

    <!-- Decorative circles -->
    <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-primary/5 blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-60 h-60 rounded-full bg-primary/5 blur-3xl"></div>

    <div class="container-wide relative z-10 text-center">
        <h2 class="section-heading text-white mb-4" data-animate="text-reveal"><?= $ctaTitle ?></h2>
        <p class="section-subheading mx-auto mb-8" data-animate="fade-up"><?= $ctaText ?></p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-animate="fade-up" data-delay="0.2">
            <a href="<?= $ctaBasePath ?>contact" class="btn btn-primary btn-lg pulse-glow">
                Get a Free Estimate
                <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="tel:<?= cms_setting('phone', PHONE) ?>" class="btn btn-outline btn-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <?= cms_setting('phone_display', PHONE_DISPLAY) ?>
            </a>
        </div>
    </div>
</section>
