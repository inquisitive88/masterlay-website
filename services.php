<?php
require_once 'includes/config.php';
$pageTitle = 'Our Services | ' . SITE_NAME;
$pageDescription = 'Explore our full range of renovation services — flooring installation, floor refinishing, custom stairs, door installation, trim work, and bathroom renovations in Brampton and the GTA.';
$currentPage = 'services';
$heroTitle = 'Our <span class="text-gradient">Services</span>';
$heroSubtitle = 'From floors to full renovations, we do it all';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Services' => ''];
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

    <!-- ============ SERVICES GRID (BENTO LAYOUT) ============ -->
    <section class="section-padding bg-dark" id="all-services">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">What We Offer</span>
                <h2 class="section-heading" data-animate="text-reveal">Comprehensive Renovation Solutions</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Every service is delivered with the same precision, premium materials, and attention to detail that defines Masterlay.</p>
            </div>

            <!-- Bento / Asymmetric Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-animate="stagger-up">
                <?php foreach ($services as $index => $service): ?>
                    <?php if ($service['slug'] === 'bathroom-renovations'): ?>
                        <!-- Bathroom Renovations - Featured tall card -->
                        <div class="lg:row-span-2">
                            <a href="services/<?= $service['slug'] ?>" class="service-card service-card-featured block h-full">
                                <div class="card-image card-image-featured">
                                    <img src="<?= $service['image'] ?>" alt="<?= htmlspecialchars($service['title']) ?>" loading="lazy">
                                </div>
                                <div class="card-body">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $service['icon'] ?>"/>
                                            </svg>
                                        </div>
                                        <span class="badge">Most Popular</span>
                                    </div>
                                    <h3 class="card-title"><?= $service['title'] ?></h3>
                                    <p class="card-text"><?= $service['description'] ?></p>
                                    <span class="card-link mt-4">
                                        Learn More
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php else: ?>
                        <?php include 'includes/service-card.php'; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ WHY MASTERLAY FOR YOUR PROJECT ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Image -->
                <div class="relative" data-animate="slide-left">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/general/craftsmanship.jpg" alt="Masterlay renovation craftsmanship" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                </div>

                <!-- Content -->
                <div data-animate="slide-right">
                    <span class="section-label" data-animate="fade-up">Why Masterlay</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">The Right Team for Every Job</h2>
                    <p class="text-white/60 mb-8" data-animate="fade-up">No matter the scope, every project gets the same level of dedication, quality materials, and expert execution.</p>

                    <div class="space-y-5" data-animate="stagger-up">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Licensed &amp; Fully Insured</h3>
                                <p class="text-white/50 text-sm">Complete liability coverage and WSIB compliance on every project for your peace of mind.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Premium Materials Only</h3>
                                <p class="text-white/50 text-sm">We source from trusted suppliers and never cut corners on product quality.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Workmanship Warranty</h3>
                                <p class="text-white/50 text-sm">Every installation is backed by our warranty, so you can trust the work for years to come.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Flexible Financing Available</h3>
                                <p class="text-white/50 text-sm">Start your project today with affordable monthly payments through our Financeit partnership.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ PROCESS SECTION ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Process</span>
                <h2 class="section-heading" data-animate="text-reveal">How It Works</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">From first call to final walkthrough — a seamless experience designed around you.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Free Consultation</h3>
                    <p class="text-white/50 text-sm">We visit your home, discuss your vision, take measurements, and understand your needs.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Design &amp; Planning</h3>
                    <p class="text-white/50 text-sm">We present material options, a detailed quote, and a clear project timeline tailored to your goals.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Expert Execution</h3>
                    <p class="text-white/50 text-sm">Our skilled crew delivers precision installation with daily cleanup and constant communication.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Final Walkthrough</h3>
                    <p class="text-white/50 text-sm">We walk through every detail together. You don't pay until you're 100% satisfied with the work.</p>
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
