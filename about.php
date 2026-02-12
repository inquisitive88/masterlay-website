<?php
require_once 'includes/content-loader.php';
$pageTitle = 'About Us | ' . SITE_NAME;
$pageDescription = 'Learn about Masterlay Renovations — Brampton\'s trusted renovation experts since 2018. Over 500 projects completed with precision craftsmanship and family values.';
$currentPage = 'about';
$heroTitle = 'About <span class="text-gradient">Masterlay Renovations</span>';
$heroSubtitle = 'Brampton\'s trusted renovation experts since ' . cms_setting('year_established', (string)YEAR_ESTABLISHED);
$heroBg = IMG . '/general/contact-hero.jpg';
$breadcrumbs = ['Home' => '/', 'About Us' => ''];
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

    <!-- ============ OUR STORY ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Image -->
                <div class="relative" data-animate="slide-left">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/general/team-at-work.jpg" alt="Masterlay Renovations team at work" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                    <!-- Experience badge -->
                    <div class="absolute -top-4 -left-4 glass rounded-2xl p-5 text-center">
                        <p class="font-heading text-3xl font-800 text-primary"><?= date('Y') - (int)cms_setting('year_established', (string)YEAR_ESTABLISHED) ?>+</p>
                        <p class="text-white/60 text-xs font-medium uppercase tracking-wider">Years</p>
                    </div>
                </div>

                <!-- Content -->
                <div data-animate="slide-right">
                    <span class="section-label" data-animate="fade-up">Our Story</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Built on Hard Work, Driven by Craft</h2>

                    <div class="space-y-5 text-white/60 leading-relaxed">
                        <p data-animate="fade-up">
                            <?= cms_setting('site_name', SITE_NAME) ?> was founded in <?= cms_setting('year_established', (string)YEAR_ESTABLISHED) ?> with a straightforward mission: deliver renovation work that homeowners can truly be proud of. What started as a small flooring operation in Brampton has grown into one of the GTA's most trusted full-service renovation companies.
                        </p>
                        <p data-animate="fade-up">
                            Our roots are in family values — honesty, reliability, and doing the job right the first time. We treat every home as if it were our own, bringing the same care and attention to a single-room floor install as we do to a complete bathroom overhaul.
                        </p>
                        <p data-animate="fade-up">
                            Over the years, we've completed more than <strong class="text-white">500 projects</strong> across the Greater Toronto Area. Each one has deepened our expertise, strengthened our reputation, and reinforced our belief that craftsmanship should never be rushed or compromised.
                        </p>
                        <p data-animate="fade-up">
                            Today, our team of skilled tradespeople handles everything from premium flooring and custom stairs to full bathroom renovations — always on time, always on budget, and always with a commitment to excellence that sets us apart.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8" data-animate="fade-up">
                        <a href="contact" class="btn btn-primary">
                            Get a Free Estimate
                            <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="gallery" class="btn btn-outline">View Our Work</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MISSION & VALUES ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Values</span>
                <h2 class="section-heading" data-animate="text-reveal">What We Stand For</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Three principles guide every project we take on — from the first consultation to the final walkthrough.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <!-- Craftsmanship -->
                <div class="glass rounded-2xl p-8 text-center group hover:border-primary/30 transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold mb-3">Craftsmanship</h3>
                    <p class="text-white/50 text-sm leading-relaxed">Every cut, every seam, every finish is executed with precision. We don't cut corners — we perfect them. Our work speaks through the details that others overlook.</p>
                </div>

                <!-- Integrity -->
                <div class="glass rounded-2xl p-8 text-center group hover:border-primary/30 transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold mb-3">Integrity</h3>
                    <p class="text-white/50 text-sm leading-relaxed">Transparent pricing, honest timelines, and no surprises. We say what we mean and deliver what we promise. Your trust is the foundation of our business.</p>
                </div>

                <!-- Customer-First -->
                <div class="glass rounded-2xl p-8 text-center group hover:border-primary/30 transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold mb-3">Customer-First</h3>
                    <p class="text-white/50 text-sm leading-relaxed">Your satisfaction drives every decision. We listen carefully, communicate constantly, and don't consider a project done until you're completely thrilled with the result.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STATS COUNTER ============ -->
    <?php include 'includes/stats-counter.php'; ?>

    <!-- ============ SERVICE AREAS ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Content -->
                <div>
                    <span class="section-label" data-animate="fade-up">Service Areas</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Proudly Serving Ontario</h2>
                    <p class="text-white/60 mb-10" data-animate="fade-up">From our home base in Brampton, we serve homeowners across the Greater Toronto Area and beyond. No matter where you are in the region, our team brings the same level of dedication and precision craftsmanship to your doorstep.</p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" data-animate="stagger-up">
                        <?php
                        $areas = ['Brampton', 'Mississauga', 'Toronto', 'Oakville', 'Hamilton', 'Burlington', 'Milton', 'Vaughan', 'GTA'];
                        foreach ($areas as $area):
                        ?>
                            <div class="flex items-center gap-2 py-2">
                                <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-white/70 text-sm font-medium"><?= $area ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-8" data-animate="fade-up">
                        <a href="contact" class="btn btn-primary">
                            Check If We Serve Your Area
                            <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Map / Decorative Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/general/home-renovation-gta.jpg" alt="Beautiful home renovation in the GTA" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                    <!-- Areas badge -->
                    <div class="absolute -bottom-4 -right-4 glass rounded-2xl p-5 text-center">
                        <p class="font-heading text-3xl font-800 text-primary">9+</p>
                        <p class="text-white/60 text-xs font-medium uppercase tracking-wider">Cities Served</p>
                    </div>
                </div>
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
