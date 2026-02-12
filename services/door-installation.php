<?php
require_once '../includes/content-loader.php';

$basePath = '../';
$currentSlug = 'door-installation';

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
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Doors That Define Your Home</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        From a grand front entry that sets the tone for your entire home to interior doors that complete your design vision, every door installation we handle is executed with precision and care. We work with all door types including front entry doors, patio doors, French doors, barn doors, and interior passage doors.
                    </p>
                    <p class="text-white/60 mb-8" data-animate="fade-up">
                        Proper door installation goes beyond aesthetics. It affects energy efficiency, security, sound insulation, and the overall feel of your home. Our team ensures every door is perfectly plumb, level, and sealed for optimal performance.
                    </p>

                    <h3 class="font-heading text-lg font-bold text-white mb-4" data-animate="fade-up">What's Included</h3>
                    <ul class="space-y-3" data-animate="stagger-up">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Door selection guidance for style, material, and function</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Precise measurements to ensure a perfect fit</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Frame preparation, shimming, and leveling</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Hardware installation including handles, locks, and hinges</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Weather sealing and insulation for exterior doors</span>
                        </li>
                    </ul>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/services/door-installation.jpg" alt="Professional door installation" class="w-full aspect-[4/5] object-cover" loading="lazy">
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
                <h2 class="section-heading" data-animate="text-reveal">How We Install Your Doors</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Precision at every step for doors that look and perform flawlessly.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Consultation</h3>
                    <p class="text-white/50 text-sm">We discuss your needs, style preferences, and functional requirements. We help you choose the right door type, material, and hardware for each opening.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Measurement</h3>
                    <p class="text-white/50 text-sm">We take precise measurements of each opening, checking for square, plumb, and level. This ensures every door is ordered to the exact dimensions needed.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Installation</h3>
                    <p class="text-white/50 text-sm">Old doors are carefully removed, frames are prepared and shimmed to perfection, and new doors are hung with expert precision for smooth, gap-free operation.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Hardware &amp; Finishing</h3>
                    <p class="text-white/50 text-sm">We install all hardware, apply weather stripping to exterior doors, add casing trim, and ensure everything operates perfectly. A final walkthrough confirms your satisfaction.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal">Door Installation Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Entry doors, interior doors, and specialty installations from recent projects.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/front-entry-door.jpg" alt="Front entry door installation" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/interior-door-hardware.jpg" alt="Interior door with modern hardware" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/french-door-install.jpg" alt="French door installation" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal">Common Questions About Door Installation</h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php
                $serviceFaqs = [
                    ['q' => 'What types of doors do you install?', 'a' => 'We install all types of residential doors including front entry doors, interior passage and privacy doors, patio sliding doors, French doors, barn doors, bi-fold closet doors, and custom decorative doors. We work with wood, fiberglass, steel, and glass materials depending on the application.'],
                    ['q' => 'How do new doors improve home security?', 'a' => 'A properly installed exterior door with a quality deadbolt and reinforced strike plate significantly improves your home security. We install doors with multi-point locking systems, solid cores, and reinforced frames. We can also recommend smart lock options for added convenience and security.'],
                    ['q' => 'Will new doors help with energy efficiency?', 'a' => 'Absolutely. Old or poorly sealed doors are one of the biggest sources of energy loss in a home. We install energy-efficient doors with proper insulation cores, weather stripping, and threshold seals that dramatically reduce drafts and lower your heating and cooling costs.'],
                    ['q' => 'How long does it take to install a door?', 'a' => 'A single interior door replacement typically takes 1-2 hours. Exterior door installations take 3-5 hours depending on the type and whether frame modifications are needed. For whole-home door replacements, we can typically complete 4-6 interior doors per day.'],
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
