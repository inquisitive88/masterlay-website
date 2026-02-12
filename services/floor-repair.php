<?php
require_once '../includes/content-loader.php';

$basePath = '../';
$currentSlug = 'floor-repair';

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
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Expert Solutions for Damaged Floors</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        Whether it is water damage, deep scratches, cracked tiles, or squeaky boards, our floor repair service addresses every issue with precision and care. We go beyond surface fixes to diagnose the root cause of the problem and deliver lasting solutions.
                    </p>
                    <p class="text-white/60 mb-8" data-animate="fade-up">
                        Our team carefully matches existing materials, textures, and colors so that repairs blend seamlessly with the rest of your floor. The goal is always a repair that is invisible to the eye and built to last.
                    </p>

                    <h3 class="font-heading text-lg font-bold text-white mb-4" data-animate="fade-up">What's Included</h3>
                    <ul class="space-y-3" data-animate="stagger-up">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Comprehensive damage assessment and diagnosis</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Material matching for seamless blending with existing flooring</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Structural repair of subfloor and support systems</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Surface restoration including sanding and refinishing</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Preventive recommendations to avoid future damage</span>
                        </li>
                    </ul>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/services/floor-repair.jpg" alt="Floor repair service" class="w-full aspect-[4/5] object-cover" loading="lazy">
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
                <h2 class="section-heading" data-animate="text-reveal">The Repair Process</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">A methodical approach that addresses both symptoms and root causes.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Diagnosis</h3>
                    <p class="text-white/50 text-sm">We thoroughly inspect the damaged area to understand the full extent of the issue, including any hidden subfloor or moisture problems beneath the surface.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Material Sourcing</h3>
                    <p class="text-white/50 text-sm">We locate and source matching materials, whether hardwood planks, vinyl, tile, or specialty products, to ensure a seamless repair that blends perfectly.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Repair</h3>
                    <p class="text-white/50 text-sm">Our team performs the repair with expert precision, whether it involves replacing boards, patching subfloor, fixing squeaks, or restoring the surface finish.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Quality Check</h3>
                    <p class="text-white/50 text-sm">We conduct a final inspection to verify the repair is structurally sound, visually seamless, and meets our rigorous quality standards before handover.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal">Floor Repair Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Seamless repairs that restore your floors to their original condition.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/gallery/floor-repair.jpg" alt="Repaired hardwood floor" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/floor-damage-restore.jpg" alt="Floor damage restoration" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/completed-repair.jpg" alt="Completed floor repair" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal">Common Questions About Floor Repair</h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php
                $serviceFaqs = [
                    ['q' => 'What types of floor damage can you fix?', 'a' => 'We handle a wide range of floor issues including water damage, warping, buckling, deep scratches, cracked or chipped tiles, squeaky boards, loose planks, gaps between boards, and subfloor deterioration. If the damage is isolated, we can often repair the affected section without replacing the entire floor.'],
                    ['q' => 'Can you match my existing flooring for a seamless repair?', 'a' => 'Yes, matching existing flooring is one of our specialties. We source identical or closely matching materials and use staining and finishing techniques to blend the repaired section with the surrounding floor. We bring samples to your home for comparison before proceeding.'],
                    ['q' => 'When should I repair versus replace my floors?', 'a' => 'If the damage is localized to a specific area and the rest of the floor is in good condition, repair is usually the more cost-effective choice. If damage is widespread, or if the flooring is very old and worn throughout, replacement may be the better long-term investment. We provide honest recommendations during our assessment.'],
                    ['q' => 'How long does a typical floor repair take?', 'a' => 'Most repairs are completed within one to two days. Simple fixes like eliminating squeaks or replacing a few boards can be done in a few hours. More extensive water damage or subfloor repairs may take longer. We provide a clear timeline after our initial assessment.'],
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
