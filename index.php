<?php
require_once 'includes/content-loader.php';
$pageTitle = SITE_NAME . ' | Premium Renovations in Brampton, ON';
$pageDescription = 'Masterlay Renovations offers premium flooring, custom stairs, door installation, trim work, and bathroom renovations in Brampton and the Greater Toronto Area.';
$currentPage = 'index';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include 'includes/loader.php'; ?>
<?php include 'includes/header.php'; ?>

<main>
    <!-- ============ HERO SECTION ============ -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="<?= IMG ?>/hero/luxury-home-main.jpg" alt="" class="w-full h-full object-cover ken-burns" aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-b from-dark/70 via-dark/50 to-dark"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container-wide text-center pt-24 pb-20">
            <div class="hero-label mb-6">
                <span class="section-label justify-center">Masterlay Renovations</span>
            </div>

            <h1 class="hero-heading font-heading text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-800 leading-[1.1] max-w-5xl mx-auto mb-6">
                Elevating Homes Through <span class="text-gradient">Precision Craftsmanship</span>
            </h1>

            <div class="hero-pills flex flex-wrap items-center justify-center gap-3 mb-10">
                <span class="badge">Flooring</span>
                <span class="badge">Stairs</span>
                <span class="badge">Doors</span>
                <span class="badge">Trim</span>
                <span class="badge">Bathrooms</span>
            </div>

            <div class="hero-buttons flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="contact" class="btn btn-primary btn-lg pulse-glow">
                    Get a Free Estimate
                    <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="gallery" class="btn btn-outline btn-lg">View Our Work</a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="hero-scroll absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40 flex flex-col items-center gap-2">
            <span class="text-xs uppercase tracking-widest">Scroll</span>
            <svg class="w-5 h-5 bounce-down" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    <!-- ============ TRUST BAR ============ -->
    <section class="py-6 border-y border-white/5 bg-dark-100">
        <div class="container-wide">
            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 text-sm text-white/40" data-animate="fade-in">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Licensed &amp; Insured
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    5-Star Rated
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    500+ Projects Completed
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Financeit Partner
                </div>
            </div>
        </div>
    </section>

    <!-- ============ SERVICES SECTION ============ -->
    <section class="section-padding bg-dark" id="services">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">What We Do</span>
                <h2 class="section-heading" data-animate="text-reveal">Our Expertise</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Comprehensive renovation services, from floor to ceiling — delivered with unmatched craftsmanship.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-animate="stagger-up">
                <?php foreach (array_slice($services, 0, 4) as $service): ?>
                    <?php include 'includes/service-card.php'; ?>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 max-w-5xl mx-auto" data-animate="stagger-up">
                <?php foreach (array_slice($services, 4) as $service): ?>
                    <?php include 'includes/service-card.php'; ?>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-10" data-animate="fade-up">
                <a href="services" class="btn btn-outline">
                    View All Services
                    <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ============ FEATURED PROJECTS ============ -->
    <section class="section-padding bg-dark-100" id="work">
        <div class="container-wide">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <span class="section-label" data-animate="fade-up">Portfolio</span>
                    <h2 class="section-heading" data-animate="text-reveal">Recent Transformations</h2>
                </div>
                <a href="gallery" class="btn btn-outline btn-sm" data-animate="fade-up">
                    View All
                    <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto no-scrollbar" data-animate="fade-in">
            <div class="flex gap-5 px-6 lg:px-[calc((100vw-1400px)/2+1.5rem)] pb-4" style="min-width: max-content;">
                <?php
                $projectImages = [
                    ['src' => IMG . '/general/modern-floor-home.jpg', 'title' => 'Modern Floor Installation', 'type' => 'Flooring'],
                    ['src' => IMG . '/gallery/custom-oak-staircase.jpg', 'title' => 'Custom Oak Staircase', 'type' => 'Stairs'],
                    ['src' => IMG . '/gallery/luxury-spa-bathroom.jpg', 'title' => 'Luxury Bathroom Remodel', 'type' => 'Bathroom'],
                    ['src' => IMG . '/gallery/hardwood-refinishing.jpg', 'title' => 'Hardwood Refinishing', 'type' => 'Refinishing'],
                    ['src' => IMG . '/gallery/french-door.jpg', 'title' => 'Entry Door Upgrade', 'type' => 'Doors'],
                ];
                foreach ($projectImages as $project):
                ?>
                    <a href="gallery" class="group relative block w-[350px] h-[450px] rounded-2xl overflow-hidden flex-shrink-0">
                        <img src="<?= $project['src'] ?>" alt="<?= $project['title'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <span class="badge mb-2"><?= $project['type'] ?></span>
                            <h3 class="font-heading text-lg font-bold text-white"><?= $project['title'] ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ WHY CHOOSE US ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Image -->
                <div class="relative" data-animate="slide-left">
                    <div class="rounded-3xl overflow-hidden">
                        <img src="<?= IMG ?>/general/home-craftsmanship.jpg" alt="Masterlay Renovations craftsmanship" class="w-full aspect-[4/5] object-cover">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-primary/20 -z-10"></div>
                </div>

                <!-- Content -->
                <div>
                    <span class="section-label" data-animate="fade-up">Why Us</span>
                    <h2 class="section-heading mb-6" data-animate="text-reveal">Why Homeowners Choose Masterlay</h2>
                    <p class="text-white/60 mb-10" data-animate="fade-up">We deliver more than renovations — we deliver confidence. Every project is handled with precision, transparency, and respect for your home.</p>

                    <div class="space-y-6" data-animate="stagger-fade">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-lg font-bold mb-1">Premium Materials</h3>
                                <p class="text-white/50 text-sm">Only top-tier materials from trusted suppliers. No shortcuts, no compromises.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-lg font-bold mb-1">Precision Craftsmanship</h3>
                                <p class="text-white/50 text-sm">Meticulous attention to every joint, seam, and finish. We obsess over the details.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-lg font-bold mb-1">Clean Worksite</h3>
                                <p class="text-white/50 text-sm">We leave your home spotless at the end of every work day. Your space, respected.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-heading text-lg font-bold mb-1">Transparent Pricing</h3>
                                <p class="text-white/50 text-sm">No hidden fees, no surprises. Detailed, itemized quotes upfront — and that's what you pay.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STATS COUNTER ============ -->
    <?php include 'includes/stats-counter.php'; ?>

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
                    <h3 class="font-heading text-lg font-bold mb-2">Design &amp; Quote</h3>
                    <p class="text-white/50 text-sm">We present material options, a detailed quote, and a clear project timeline.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">3</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Expert Execution</h3>
                    <p class="text-white/50 text-sm">Our skilled crew delivers precision installation with daily cleanup and communication.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">4</div>
                    <h3 class="font-heading text-lg font-bold mb-2">Final Walkthrough</h3>
                    <p class="text-white/50 text-sm">We walk through every detail together. You don't pay until you're 100% satisfied.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ TESTIMONIALS ============ -->
    <section class="section-padding bg-dark-100" id="testimonials">
        <div class="container-wide">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <span class="section-label" data-animate="fade-up">Testimonials</span>
                    <h2 class="section-heading" data-animate="text-reveal">What Our Clients Say</h2>
                </div>
                <a href="testimonials" class="btn btn-outline btn-sm" data-animate="fade-up">
                    Read More Reviews
                    <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-animate="stagger-up">
                <?php foreach (array_slice($testimonials, 0, 3) as $testimonial): ?>
                    <?php include 'includes/testimonial-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ FINANCING TEASER ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="rounded-3xl bg-dark-50 border border-white/5 p-8 md:p-12 lg:p-16 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-primary/5 blur-3xl -translate-y-1/2 translate-x-1/2"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center relative z-10">
                    <div>
                        <span class="section-label" data-animate="fade-up">Flexible Financing</span>
                        <h2 class="section-heading mb-4" data-animate="text-reveal">Make Your Dream Renovation Affordable</h2>
                        <p class="text-white/60 mb-8" data-animate="fade-up">We've partnered with Financeit to offer flexible payment plans. Get your renovation started today with low monthly payments and easy approval.</p>
                        <a href="financing" class="btn btn-primary" data-animate="fade-up">
                            Learn About Financing
                            <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                    <div class="flex justify-center lg:justify-end" data-animate="fade-up">
                        <div class="glass rounded-2xl p-8 text-center max-w-xs">
                            <p class="text-white/50 text-sm mb-2">Renovations starting at</p>
                            <p class="font-heading text-5xl font-800 text-primary mb-1">$99</p>
                            <p class="text-white/50 text-sm">/month with approved credit</p>
                            <div class="mt-4 pt-4 border-t border-white/10 text-xs text-white/30">Powered by Financeit</div>
                        </div>
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
