<?php
require_once 'includes/content-loader.php';
$pageTitle = 'Contact Us | ' . SITE_NAME;
$pageDescription = 'Get a free estimate for your renovation project. Contact Masterlay Renovations for flooring, stairs, doors, trim, and bathroom renovation services in Brampton and the GTA.';
$currentPage = 'contact';
$heroTitle = 'Get In Touch';
$heroSubtitle = 'Free estimates for any project';
$heroBg = IMG . '/general/contact-hero.jpg';
$breadcrumbs = ['Home' => '/', 'Contact' => ''];
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

    <!-- ============ CONTACT SECTION ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

                <!-- Contact Form -->
                <div class="lg:col-span-7" data-animate="fade-up">
                    <span class="section-label" data-animate="fade-up">Send Us a Message</span>
                    <h2 class="section-heading mb-2" data-animate="text-reveal">Tell Us About Your Project</h2>
                    <p class="section-subheading mb-10" data-animate="fade-up">Fill out the form below and we'll get back to you within 24 hours with a free, no-obligation estimate.</p>

                    <form id="contactForm" class="space-y-6" novalidate>
                        <!-- Name & Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name <span class="text-primary">*</span></label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required>
                                <span class="form-error">Please enter your full name</span>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address <span class="text-primary">*</span></label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="john@example.com" required>
                                <span class="form-error">Please enter a valid email address</span>
                            </div>
                        </div>

                        <!-- Phone & Service Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number <span class="text-primary">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="(416) 555-0123" required>
                                <span class="form-error">Please enter a valid phone number</span>
                            </div>
                            <div class="form-group">
                                <label for="service" class="form-label">Service Interested In</label>
                                <select id="service" name="service" class="form-select">
                                    <option value="">Select a service</option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= htmlspecialchars($service['slug']) ?>"><?= htmlspecialchars($service['title']) ?></option>
                                    <?php endforeach; ?>
                                    <option value="other">Other</option>
                                </select>
                                <span class="form-error"></span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="message" class="form-label">Project Description <span class="text-primary">*</span></label>
                            <textarea id="message" name="message" rows="5" class="form-input resize-none" placeholder="Tell us about your project, including any specific requirements, room dimensions, or materials you're interested in..." required></textarea>
                            <span class="form-error">Please describe your project</span>
                        </div>

                        <!-- Budget -->
                        <div class="form-group">
                            <label for="budget" class="form-label">Budget Range <span class="text-white/30 text-xs font-normal">(Optional)</span></label>
                            <select id="budget" name="budget" class="form-select">
                                <option value="">Prefer not to say</option>
                                <option value="under-5k">Under $5,000</option>
                                <option value="5k-10k">$5,000 - $10,000</option>
                                <option value="10k-25k">$10,000 - $25,000</option>
                                <option value="25k-50k">$25,000 - $50,000</option>
                                <option value="50k-plus">$50,000+</option>
                            </select>
                            <span class="form-error"></span>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg w-full sm:w-auto">
                                Send Message
                                <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Info Sidebar -->
                <div class="lg:col-span-5" data-animate="fade-up" data-delay="0.2">
                    <div class="glass rounded-2xl p-8 lg:p-10 sticky top-32">
                        <h3 class="font-heading text-xl font-bold mb-8">Contact Information</h3>

                        <div class="space-y-6">
                            <!-- Phone -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white/40 text-sm mb-1">Call Us</p>
                                    <a href="tel:<?= cms_setting('phone', PHONE) ?>" class="text-white font-medium hover:text-primary transition-colors"><?= cms_setting('phone_display', PHONE_DISPLAY) ?></a>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white/40 text-sm mb-1">Email Us</p>
                                    <a href="mailto:<?= cms_setting('email', EMAIL) ?>" class="text-white font-medium hover:text-primary transition-colors"><?= cms_setting('email', EMAIL) ?></a>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white/40 text-sm mb-1">Visit Us</p>
                                    <p class="text-white font-medium"><?= cms_setting('address', ADDRESS) ?></p>
                                </div>
                            </div>

                            <!-- Hours -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white/40 text-sm mb-1">Business Hours</p>
                                    <p class="text-white font-medium"><?= cms_setting('hours', HOURS) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-white/10 my-8"></div>

                        <!-- Social Media -->
                        <div>
                            <p class="text-white/40 text-sm mb-4">Follow Us</p>
                            <div class="flex items-center gap-3">
                                <a href="<?= cms_setting('instagram_url', INSTAGRAM) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all" aria-label="Instagram">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                                <a href="<?= cms_setting('facebook_url', FACEBOOK) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all" aria-label="Facebook">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="<?= cms_setting('tiktok_url', TIKTOK) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all" aria-label="TikTok">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Response Note -->
                        <div class="mt-8 p-4 rounded-xl bg-primary/5 border border-primary/10">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <p class="text-sm text-white/60">We typically respond within <span class="text-primary font-semibold">24 hours</span></p>
                            </div>
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
