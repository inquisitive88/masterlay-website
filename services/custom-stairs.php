<?php
require_once '../includes/config.php';

$basePath = '../';
$currentSlug = 'custom-stairs';

// Find current service from config
$currentService = null;
foreach ($services as $s) {
    if ($s['slug'] === $currentSlug) {
        $currentService = $s;
        break;
    }
}

$pageTitle = $currentService['title'] . ' | ' . SITE_NAME;
$pageDescription = $currentService['description'];
$currentPage = 'services';
$loadFaqJs = true;
$heroTitle = $currentService['title'];
$heroSubtitle = $currentService['short'];
$heroBg = $basePath . $currentService['hero_image'];
$breadcrumbs = ['Home' => '/', 'Services' => '/services', $currentService['title'] => ''];
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include '../includes/loader.php'; ?>
<?php include '../includes/header.php'; ?>

<main>
    <!-- ============ PAGE HERO ============ -->
    <?php include '../includes/page-hero.php'; ?>

    <!-- ============ SERVICE OVERVIEW ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Text Content -->
                <div data-animate="slide-left">
                    <span class="section-label" data-animate="fade-up">Service Overview</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Staircases That Make a Statement</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        Your staircase is one of the most prominent architectural features in your home. Whether you envision a grand traditional hardwood staircase, sleek modern floating treads, or a contemporary mix of metal and glass, we design and build each system with meticulous precision.
                    </p>
                    <p class="text-white/60 mb-8" data-animate="fade-up">
                        Every project begins with a detailed design consultation where we understand your vision, lifestyle, and home architecture. From material selection to final finishing, we handle every detail to deliver a staircase that becomes the centerpiece of your home.
                    </p>

                    <h3 class="font-heading text-lg font-bold text-white mb-4" data-animate="fade-up">What's Included</h3>
                    <ul class="space-y-3" data-animate="stagger-up">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Comprehensive design consultation and 3D visualization</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Material selection including hardwood, metal, and glass options</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Custom fabrication of treads, risers, and stringers</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Professional installation with structural reinforcement</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Railing systems including wood, metal, cable, and glass</span>
                        </li>
                    </ul>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="../images/services/custom-stairs.jpg" alt="Custom staircase design" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ OUR APPROACH ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Approach</span>
                <h2 class="section-heading" data-animate="text-reveal">From Concept to Completion</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">A collaborative process that turns your staircase vision into reality.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Design</h3>
                    <p class="text-white/50 text-sm">We collaborate on style, materials, and layout. We take precise measurements and present design options that complement your home's architecture and your personal taste.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Fabrication</h3>
                    <p class="text-white/50 text-sm">Each component is custom-built to exact specifications. Treads, risers, stringers, and railing parts are crafted with precision to ensure a perfect fit during installation.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Installation</h3>
                    <p class="text-white/50 text-sm">Our experienced crew handles structural preparation, assembly, and secure mounting. Every joint is tight, every tread is level, and every railing is solid and safe.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Finishing</h3>
                    <p class="text-white/50 text-sm">We apply stains, sealants, and protective finishes to bring out the natural beauty of the materials. A final walkthrough ensures every detail meets our exacting standards.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal">Custom Stairs Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Staircases we have designed and built for homes across the GTA.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <div class="rounded-2xl overflow-hidden">
                    <img src="../images/general/modern-staircase.jpg" alt="Modern custom staircase" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="../images/general/wood-metal-stairs.jpg" alt="Wood and metal staircase" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="../images/general/traditional-hardwood-stairs.jpg" alt="Traditional hardwood staircase" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal">Common Questions About Custom Stairs</h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php
                $serviceFaqs = [
                    ['q' => 'What staircase styles do you offer?', 'a' => 'We build a full range of staircase styles including traditional closed-riser designs, open-riser contemporary stairs, floating staircases, L-shaped and U-shaped configurations, and spiral staircases. We work with hardwood, metal, glass, and cable railing systems to achieve the exact look you want.'],
                    ['q' => 'Do custom stairs need to meet building code requirements?', 'a' => 'Yes, all staircases must comply with Ontario Building Code requirements for safety. This includes specifications for tread depth, riser height, railing height, and baluster spacing. We design every staircase to meet or exceed these codes, and we coordinate any required inspections.'],
                    ['q' => 'How long does a custom staircase project take?', 'a' => 'A typical custom staircase project takes 3-6 weeks from design approval to completion. This includes 1-2 weeks for design finalization, 1-2 weeks for fabrication and material sourcing, and 1-2 weeks for installation and finishing. Complex designs with specialty materials may require additional time.'],
                    ['q' => 'Can you renovate my existing staircase instead of building a new one?', 'a' => 'Absolutely. If your existing staircase is structurally sound, we can transform it with new treads, risers, railings, and finishes. This is often a cost-effective way to dramatically update the look of your stairs without a full rebuild. We assess the structure first to determine the best approach.'],
                ];
                foreach ($serviceFaqs as $faqItem):
                    include '../includes/faq-accordion.php';
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- ============ RELATED SERVICES ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Explore More</span>
                <h2 class="section-heading" data-animate="text-reveal">Related Services</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Discover other ways we can transform your home.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <?php
                $relatedCount = 0;
                foreach ($services as $service):
                    if ($service['slug'] === $currentSlug) continue;
                    if ($relatedCount >= 3) break;
                    include '../includes/service-card.php';
                    $relatedCount++;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- ============ CTA SECTION ============ -->
    <?php include '../includes/cta-section.php'; ?>
</main>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>
