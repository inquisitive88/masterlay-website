<?php
require_once '../includes/config.php';

$basePath = '../';
$currentSlug = 'bathroom-renovations';

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
                    <span class="section-label" data-animate="fade-up">
                        <span class="badge mr-2">Most Popular</span>
                        Service Overview
                    </span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Complete Bathroom Transformations From Concept to Completion</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        A bathroom renovation is one of the most impactful upgrades you can make to your home. It improves daily comfort, increases property value, and creates a personal sanctuary in your own home. At Masterlay, we deliver complete bathroom overhauls that combine thoughtful design with expert construction.
                    </p>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        From the initial design consultation through demolition, construction, tiling, plumbing, and final finishing, we manage every detail of the process. Our goal is to create a space that feels like a private spa, built with the quality materials and precision craftsmanship that define every Masterlay project.
                    </p>
                    <p class="text-white/60 mb-8" data-animate="fade-up">
                        Whether you are updating a powder room, renovating a family bathroom, or creating a luxury ensuite, we bring the same level of dedication and attention to every project, regardless of size.
                    </p>

                    <h3 class="font-heading text-lg font-bold text-white mb-4" data-animate="fade-up">What's Included</h3>
                    <ul class="space-y-3" data-animate="stagger-up">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Complete demolition and disposal of existing fixtures and finishes</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Flooring and wall tiling with premium tile selection</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Custom vanity and cabinetry design and installation</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Fixture upgrades including toilets, faucets, shower heads, and tubs</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Modern lighting design and ventilation upgrades</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Plumbing coordination and code-compliant rough-in work</span>
                        </li>
                    </ul>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="../images/services/bathroom-renovations.jpg" alt="Luxury bathroom renovation" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHY BATHROOM RENOVATIONS MATTER ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Image -->
                <div class="relative order-2 lg:order-1" data-animate="slide-left">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="../images/general/modern-bathroom-design.jpg" alt="Modern bathroom design" class="w-full aspect-[4/3] object-cover" loading="lazy">
                    </div>
                </div>

                <!-- Content -->
                <div class="order-1 lg:order-2" data-animate="slide-right">
                    <span class="section-label" data-animate="fade-up">Why Renovate</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">The Impact of a Bathroom Renovation</h2>
                    <p class="text-white/60 mb-6" data-animate="fade-up">
                        A well-executed bathroom renovation delivers benefits that go far beyond aesthetics. It is one of the highest-return investments you can make in your home.
                    </p>

                    <div class="space-y-5" data-animate="stagger-up">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Increased Home Value</h3>
                                <p class="text-white/50 text-sm">Bathroom renovations consistently rank among the top home improvements for return on investment, often recouping 60-70% of costs at resale.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Daily Comfort &amp; Wellness</h3>
                                <p class="text-white/50 text-sm">Start and end every day in a space designed for relaxation. Modern fixtures, heated floors, and spa-like features transform your daily routine.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Safety &amp; Accessibility</h3>
                                <p class="text-white/50 text-sm">Address mold, water damage, and outdated plumbing while upgrading to slip-resistant surfaces, grab bars, and accessible design features.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Energy Efficiency</h3>
                                <p class="text-white/50 text-sm">Low-flow toilets, efficient faucets, LED lighting, and proper ventilation reduce water and energy consumption while lowering your utility bills.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ OUR APPROACH ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Approach</span>
                <h2 class="section-heading" data-animate="text-reveal">The Renovation Process</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">A comprehensive four-phase approach that keeps your project on track and stress-free.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Design Consultation</h3>
                    <p class="text-white/50 text-sm">We meet at your home to understand your vision, assess the existing space, and discuss layout options, material preferences, fixtures, and budget. We present a detailed proposal with a clear timeline and transparent pricing.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Demolition &amp; Prep</h3>
                    <p class="text-white/50 text-sm">We carefully demolish and dispose of all existing fixtures, tile, and finishes. We inspect and address any underlying issues like water damage, mold, or structural concerns. Plumbing and electrical rough-in work is completed.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Construction &amp; Tiling</h3>
                    <p class="text-white/50 text-sm">Waterproofing membranes are applied, followed by precision tile installation on floors and walls. The vanity, cabinetry, and shower enclosures are installed. Every surface is finished with expert care.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Fixtures &amp; Finishing</h3>
                    <p class="text-white/50 text-sm">All fixtures are connected and tested, lighting and ventilation are installed, mirrors and accessories are mounted, and a thorough cleaning is performed. We do a final walkthrough to ensure every detail is perfect.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal">Bathroom Renovation Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Complete bathroom transformations that showcase our design and construction capabilities.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <div class="rounded-2xl overflow-hidden">
                    <img src="../images/general/freestanding-tub.jpg" alt="Modern bathroom with freestanding tub" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="../images/general/walk-in-shower.jpg" alt="Walk-in shower with tile work" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="../images/general/custom-vanity.jpg" alt="Custom vanity and mirror" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal">Common Questions About Bathroom Renovations</h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php
                $serviceFaqs = [
                    ['q' => 'How long does a bathroom renovation typically take?', 'a' => 'A standard bathroom renovation takes 2-4 weeks depending on the scope. A simple cosmetic update with new fixtures and paint can be completed in under 2 weeks. A full gut renovation involving layout changes, new plumbing, and custom tile work typically takes 3-4 weeks. We provide a detailed schedule at the start of every project so you know exactly what to expect.'],
                    ['q' => 'Do I need permits for a bathroom renovation?', 'a' => 'Permits are typically required when the renovation involves plumbing changes, electrical work, or structural modifications. Simple cosmetic updates like replacing fixtures and re-tiling usually do not require permits. We handle all permit applications and coordinate inspections on your behalf so you do not have to deal with the bureaucracy.'],
                    ['q' => 'How do I use the bathroom during the renovation?', 'a' => 'If you have a second bathroom in your home, we recommend using that during the renovation. For homes with a single bathroom, we prioritize scheduling to minimize downtime and can often maintain basic functionality during certain phases. We discuss logistics during the planning stage so there are no surprises.'],
                    ['q' => 'What does a bathroom renovation cost?', 'a' => 'Costs vary widely based on the scope, size, and material selections. A basic cosmetic refresh may start in the range of several thousand dollars, while a full custom renovation with premium materials can be a significant investment. We provide detailed, transparent quotes during the consultation so you understand exactly what is included. We also offer financing through our Financeit partnership for flexible monthly payments.'],
                ];
                foreach ($serviceFaqs as $faqItem):
                    include '../includes/faq-accordion.php';
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- ============ RELATED SERVICES ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Explore More</span>
                <h2 class="section-heading" data-animate="text-reveal">Related Services</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Complete your renovation with our complementary services.</p>
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
    <?php
    $ctaTitle = "Ready to Transform Your Bathroom?";
    $ctaText = "Get a free, no-obligation estimate for your bathroom renovation. Let's create the spa-like space you've always wanted.";
    include '../includes/cta-section.php';
    ?>
</main>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>
