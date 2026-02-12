<?php
require_once '../includes/content-loader.php';

$basePath = '../';
$currentSlug = 'basement-renovation';

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
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Unlock Your Home's Full Potential</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">
                        Your basement is one of the largest untapped spaces in your home. Whether it's completely unfinished or just outdated, a professional basement renovation can add hundreds of square feet of functional living space — increasing both your comfort and your property value.
                    </p>
                    <p class="text-white/60 mb-8" data-animate="fade-up">
                        At Masterlay, we handle every aspect of the basement renovation from start to finish. Our team manages framing, insulation, electrical coordination, plumbing coordination, drywall, flooring, trim, lighting, and final finishes. Whether you envision a home theatre, a guest suite, a home office, a rec room, or a rental unit, we bring your vision to life with precision craftsmanship.
                    </p>

                    <h3 class="font-heading text-lg font-bold text-white mb-4" data-animate="fade-up">What's Included</h3>
                    <ul class="space-y-3" data-animate="stagger-up">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Full framing, insulation, and vapour barrier installation</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Drywall installation, taping, and finishing</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Flooring installation (vinyl plank, tile, laminate, or carpet)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Pot light and recessed lighting design and installation</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Basement bathroom and kitchenette rough-ins and builds</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-white/70">Trim, doors, paint, and final finishing touches</span>
                        </li>
                    </ul>
                </div>

                <!-- Image -->
                <div class="relative" data-animate="slide-right">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/services/basement-renovation.jpg" alt="Finished basement renovation" class="w-full aspect-[4/5] object-cover" loading="lazy">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WHAT WE CAN BUILD ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Image -->
                <div class="relative order-2 lg:order-1" data-animate="slide-left">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/general/modern-basement-living.jpg" alt="Modern basement living space" class="w-full aspect-[4/3] object-cover" loading="lazy">
                    </div>
                </div>

                <!-- Content -->
                <div class="order-1 lg:order-2" data-animate="slide-right">
                    <span class="section-label" data-animate="fade-up">Possibilities</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">What We Can Build</h2>
                    <p class="text-white/60 mb-6" data-animate="fade-up">
                        Every basement is unique, and so is every client's vision. We design and build custom layouts tailored to how you want to use the space.
                    </p>

                    <div class="space-y-5" data-animate="stagger-up">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Home Theatre & Entertainment</h3>
                                <p class="text-white/50 text-sm">Dedicated media rooms with soundproofing, recessed lighting, built-in speaker wiring, and theatre-style seating layouts.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Guest Suite & In-Law Unit</h3>
                                <p class="text-white/50 text-sm">Self-contained living spaces with private bedrooms, full bathrooms, kitchenettes, and separate entrances for family or rental income.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Home Office & Study</h3>
                                <p class="text-white/50 text-sm">Quiet, well-lit workspaces with built-in desks, proper electrical for equipment, soundproofing, and climate control for year-round productivity.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-base font-bold mb-1">Rec Room & Play Area</h3>
                                <p class="text-white/50 text-sm">Open-concept recreation rooms with durable flooring, game areas, wet bars, and flexible layouts for the whole family to enjoy.</p>
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
                <p class="section-subheading mx-auto" data-animate="fade-up">A structured four-phase approach that turns your unfinished basement into a polished living space.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative" data-animate="stagger-up">
                <div class="hidden lg:block absolute top-[30px] left-[15%] right-[15%] h-[2px] bg-white/10"></div>

                <div class="process-step">
                    <div class="process-number">1</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Design & Planning</h3>
                    <p class="text-white/50 text-sm">We visit your home to assess the basement space, discuss your goals, and create a custom layout. We handle permit applications and provide a detailed proposal with a clear timeline and transparent pricing.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">2</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Framing & Rough-In</h3>
                    <p class="text-white/50 text-sm">The structural framework goes up, including partition walls, bulkheads, and doorways. Electrical wiring, plumbing rough-ins, and HVAC ducting are completed and inspected before we close the walls.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Insulation & Drywall</h3>
                    <p class="text-white/50 text-sm">Insulation and vapour barriers are installed for comfort and moisture control. Drywall is hung, taped, mudded, and sanded smooth. Ceilings are finished with drywall or drop ceiling depending on your preference.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Finishing & Handover</h3>
                    <p class="text-white/50 text-sm">Flooring is installed, trim and doors go in, painting is completed, and fixtures are connected. We do a thorough cleanup and a final walkthrough to ensure every detail meets your expectations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Our Work</span>
                <h2 class="section-heading" data-animate="text-reveal">Basement Renovation Gallery</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Complete basement transformations from our recent projects across the GTA.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-animate="stagger-up">
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/finished-basement.jpg" alt="Finished basement living room" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/modern-basement-living.jpg" alt="Basement home theatre" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="rounded-2xl overflow-hidden">
                    <img src="<?= IMG ?>/general/vinyl-plank-living.jpg" alt="Modern basement bar area" class="w-full aspect-[4/3] object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">FAQ</span>
                <h2 class="section-heading" data-animate="text-reveal">Common Questions About Basement Renovations</h2>
            </div>

            <div class="space-y-4" data-animate="stagger-fade">
                <?php
                $serviceFaqs = [
                    ['q' => 'How long does a basement renovation take?', 'a' => 'A typical basement renovation takes 4-8 weeks depending on the size and complexity. A basic open-concept finish may take 4-5 weeks, while a full build with a bathroom, kitchenette, and multiple rooms can take 6-8 weeks. We provide a detailed schedule during the planning phase so you know exactly what to expect.'],
                    ['q' => 'Do I need a permit for a basement renovation?', 'a' => 'Yes, most basement renovations in Ontario require a building permit, especially if you are adding bedrooms, bathrooms, a kitchen, or making structural changes. We handle all permit applications, inspections, and code compliance on your behalf so you do not have to worry about the process.'],
                    ['q' => 'What about moisture and waterproofing?', 'a' => 'Moisture control is a critical part of every basement renovation. We assess your basement for signs of water intrusion before starting. Our builds include proper vapour barriers, insulation rated for below-grade applications, and drainage solutions where needed. If significant waterproofing is required, we coordinate with specialists before finishing the space.'],
                    ['q' => 'How much does a basement renovation cost?', 'a' => 'Costs depend on the size of the space, the scope of work, and your material selections. A basic open-concept finish is a different investment than a full build with a bathroom, bedroom, and kitchenette. We provide detailed, transparent quotes during the consultation so you understand exactly what is included. We also offer financing through our Financeit partnership for flexible monthly payments.'],
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
    $ctaTitle = "Ready to Transform Your Basement?";
    $ctaText = "Get a free, no-obligation estimate for your basement renovation. Let's turn that unused space into something extraordinary.";
    include '../includes/cta-section.php';
    ?>
</main>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>
