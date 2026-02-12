<?php
require_once '../includes/content-loader.php';

$basePath = '../';
$currentSlug = 'floor-refinishing';

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
$heroBg = $currentService['hero_image'];
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
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Breathe New Life Into Your Hardwood Floors</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        Years of foot traffic, furniture scratches, and sun exposure can leave hardwood floors looking tired and worn. Our refinishing service restores them to their original beauty, or transforms them with a completely new look through custom staining.
                    </p>
                    <p class="text-white/60 mb-8" data-animate="fade-up">
                        Using advanced dustless sanding technology, we minimize mess and disruption to your household. Our multi-coat finishing process delivers a surface that is not only visually stunning but also protected against daily wear for years to come.
                    </p>

                    <h3 class="font-heading text-lg font-bold text-white mb-4" data-animate="fade-up">What's Included</h3>
                    <ul class="space-y-3" data-animate="stagger-up">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Thorough floor assessment and condition report</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Dustless sanding with advanced containment systems</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Custom stain color matching to your design vision</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Multiple coats of premium polyurethane for lasting protection</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Final buffing, inspection, and walkthrough</span>
                        </li>
                    </ul>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/services/floor-refinishing.jpg" alt="Hardwood floor refinishing" class="w-full aspect-[4/5] object-cover" loading="lazy">
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
                <h2 class="section-heading" data-animate="text-reveal">The Refinishing Process</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Four precise stages that take your floors from worn to stunning.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Inspection</h3>
                    <p class="text-white/50 text-sm">We evaluate your floors for structural integrity, damage, and finish condition. We identify any repairs needed before sanding begins.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Sanding</h3>
                    <p class="text-white/50 text-sm">Using dustless sanding equipment, we strip away old finish, scratches, and imperfections to reveal fresh, clean wood beneath.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Staining</h3>
                    <p class="text-white/50 text-sm">We apply your chosen stain color evenly across the entire floor. We test colors on-site first to ensure it matches your vision perfectly.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Sealing</h3>
                    <p class="text-white/50 text-sm">Multiple coats of high-grade polyurethane are applied, sanded between coats, and finished with a final buff for a flawless, protective surface.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal">Floor Refinishing Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Before and after transformations that showcase the power of professional refinishing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/refinished-hardwood.jpg" alt="Refinished hardwood floor" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/stained-hardwood.jpg" alt="Stained hardwood floor detail" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/polished-hardwood.jpg" alt="Polished hardwood flooring" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal">Common Questions About Floor Refinishing</h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php
                $serviceFaqs = [
                    ['q' => 'What is dustless sanding and how does it work?', 'a' => 'Dustless sanding uses specialized equipment with built-in vacuum systems that capture up to 99% of the dust generated during the sanding process. This means far less mess in your home, better air quality, and a cleaner result overall. It is especially beneficial for families with allergies or respiratory sensitivities.'],
                    ['q' => 'How long does the finish take to fully cure?', 'a' => 'Water-based polyurethane is dry to the touch within 2-4 hours between coats. After the final coat, light foot traffic is typically safe within 24 hours, but we recommend waiting 3-5 days before placing furniture and 7 days before placing area rugs to allow the finish to fully harden.'],
                    ['q' => 'How many coats of polyurethane do you apply?', 'a' => 'We apply a minimum of three coats of polyurethane for optimal protection and appearance. Between each coat, we lightly sand the surface to ensure perfect adhesion. For high-traffic areas, we may recommend an additional coat for extra durability.'],
                    ['q' => 'Can you change the color of my existing hardwood floors?', 'a' => 'Absolutely. Once the old finish is sanded away, we can apply any stain color you choose, from light natural tones to rich dark espressos. We always test the stain on an inconspicuous area first so you can approve the color before we proceed with the full floor.'],
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
