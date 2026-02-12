<?php
// Usage: Set these variables before including:
// $heroTitle, $heroSubtitle (optional), $heroBg (optional)
$heroTitle = $heroTitle ?? 'Page Title';
$heroSubtitle = $heroSubtitle ?? '';
$heroBasePath = $basePath ?? '';
$heroBg = $heroBg ?? $heroBasePath . 'images/hero/luxury-home-main.jpg';
?>
<section class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0">
        <img src="<?= $heroBg ?>" alt="" class="w-full h-full object-cover" aria-hidden="true">
        <div class="absolute inset-0 bg-gradient-to-b from-dark/80 via-dark/70 to-dark"></div>
    </div>

    <div class="container-wide relative z-10">
        <?php if (isset($breadcrumbs)): ?>
            <?php include ($heroBasePath ? $heroBasePath : '') . 'includes/breadcrumb.php'; ?>
        <?php endif; ?>

        <h1 class="hero-heading font-heading text-4xl md:text-5xl lg:text-6xl font-800 leading-[1.15] max-w-4xl mb-4">
            <?= $heroTitle ?>
        </h1>

        <?php if ($heroSubtitle): ?>
            <p class="text-lg text-white/60 max-w-2xl" data-animate="fade-up"><?= $heroSubtitle ?></p>
        <?php endif; ?>
    </div>

    <!-- Bottom diagonal -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full" preserveAspectRatio="none">
            <path d="M0 60V30C360 0 1080 0 1440 30V60H0Z" fill="#0A0A0A"/>
        </svg>
    </div>
</section>
