<?php
require_once 'includes/config.php';

$pageTitle = 'Flexible Financing Options | ' . SITE_NAME;
$pageDescription = 'Affordable renovation financing through Financeit. Low monthly payments, quick approval, and flexible terms to make your dream renovation a reality.';
$currentPage = 'financing';
$loadFaqJs = true;
$heroTitle = 'Flexible <span class="text-gradient">Financing</span> Options';
$heroSubtitle = 'Make your dream renovation affordable with low monthly payments and easy approval.';
$heroBg = 'images/hero/luxury-home-main.jpg';
$breadcrumbs = ['Home' => '/', 'Financing' => ''];
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

    <!-- ============ HOW IT WORKS ============ -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Simple Process</span>
                <h2 class="section-heading" data-animate="text-reveal">How It Works</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Getting financing for your renovation is quick and easy — just three simple steps.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative" data-animate="stagger-up">
                <!-- Connector line -->
                <div class="hidden md:block absolute top-[30px] left-[20%] right-[20%] h-[2px] bg-white/10"></div>

                <!-- Step 1 -->
                <div class="process-step">
                    <div class="process-number">1</div>
                    <div class="mb-4">
                        <svg class="w-8 h-8 text-primary mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">Apply Online</h3>
                    <p class="text-white/50 text-sm">Complete a quick application through our secure Financeit portal. It only takes a few minutes.</p>
                </div>

                <!-- Step 2 -->
                <div class="process-step">
                    <div class="process-number">2</div>
                    <div class="mb-4">
                        <svg class="w-8 h-8 text-primary mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">Get Approved</h3>
                    <p class="text-white/50 text-sm">Receive a fast decision — most applications are approved within minutes with competitive rates.</p>
                </div>

                <!-- Step 3 -->
                <div class="process-step">
                    <div class="process-number">3</div>
                    <div class="mb-4">
                        <svg class="w-8 h-8 text-primary mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">Start Your Project</h3>
                    <p class="text-white/50 text-sm">Once approved, we get started on your renovation right away. Enjoy your new space while paying over time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ BENEFITS GRID ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Why Finance With Us</span>
                <h2 class="section-heading" data-animate="text-reveal">Benefits of Financing</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Renovation financing designed to fit your budget and lifestyle.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-animate="stagger-up">
                <!-- Low Monthly Payments -->
                <div class="glass rounded-2xl p-8 text-center">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">Low Monthly Payments</h3>
                    <p class="text-white/50 text-sm">Spread the cost of your renovation into manageable monthly payments that fit your budget.</p>
                </div>

                <!-- Quick Approval -->
                <div class="glass rounded-2xl p-8 text-center">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">Quick Approval Process</h3>
                    <p class="text-white/50 text-sm">Most applications are reviewed and approved within minutes so you can get started sooner.</p>
                </div>

                <!-- Flexible Terms -->
                <div class="glass rounded-2xl p-8 text-center">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">Flexible Terms</h3>
                    <p class="text-white/50 text-sm">Choose from a variety of repayment terms to find the plan that works best for your financial situation.</p>
                </div>

                <!-- No Hidden Fees -->
                <div class="glass rounded-2xl p-8 text-center">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-lg font-bold mb-2">No Hidden Fees</h3>
                    <p class="text-white/50 text-sm">Transparent terms with no surprises. What you see is what you pay — simple and straightforward.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ PAYMENT EXAMPLES ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Sample Payments</span>
                <h2 class="section-heading" data-animate="text-reveal">Affordable Monthly Payments</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">See how easy it is to fit your renovation into your monthly budget.</p>
            </div>

            <div class="glass rounded-2xl overflow-hidden" data-animate="fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="px-6 py-5 font-heading font-bold text-white text-sm uppercase tracking-wider">Project Cost</th>
                                <th class="px-6 py-5 font-heading font-bold text-white text-sm uppercase tracking-wider text-center">Monthly Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-5">
                                    <span class="font-heading text-xl font-bold text-white">$5,000</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="font-heading text-2xl font-800 text-primary">~$50</span>
                                    <span class="text-white/40 text-sm">/mo</span>
                                </td>
                            </tr>
                            <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-5">
                                    <span class="font-heading text-xl font-bold text-white">$10,000</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="font-heading text-2xl font-800 text-primary">~$99</span>
                                    <span class="text-white/40 text-sm">/mo</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-5">
                                    <span class="font-heading text-xl font-bold text-white">$25,000</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="font-heading text-2xl font-800 text-primary">~$249</span>
                                    <span class="text-white/40 text-sm">/mo</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/[0.02] border-t border-white/5">
                    <p class="text-white/30 text-xs text-center">* Rates and terms vary. Subject to credit approval. Sample payments are estimates based on typical financing terms.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ APPLY CTA ============ -->
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <div class="rounded-3xl bg-dark-50 border border-white/5 p-8 md:p-12 lg:p-16 relative overflow-hidden">
                <!-- Decorative -->
                <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-primary/5 blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-primary/5 blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10 text-center max-w-2xl mx-auto">
                    <span class="section-label justify-center" data-animate="fade-up">Get Started</span>
                    <h2 class="section-heading mb-4" data-animate="text-reveal">Ready to Get Started?</h2>
                    <p class="text-white/60 mb-4" data-animate="fade-up">Apply through our secure Financeit portal and get pre-approved in minutes. Our team will guide you through every step of the financing process so you can focus on your dream renovation.</p>
                    <p class="text-white/40 text-sm mb-8" data-animate="fade-up">Powered by Financeit — Canada's leading home improvement financing platform.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-animate="fade-up">
                        <a href="https://www.financeit.ca/en/direct/payment-plan/YT0zMTAxMDAmbD0mcD1MdHdFaXZsMkFVeE0yb3ZCR2hDVGxnJnM9MTY2ODMmdj0x/apply?slug=JT_INA" target="_blank" rel="noopener" class="btn btn-primary btn-lg pulse-glow">
                            Apply for Financing
                            <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="tel:<?= PHONE ?>" class="btn btn-outline btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <?= PHONE_DISPLAY ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FINANCING FAQ ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow">
            <div class="text-center mb-14">
                <span class="section-label justify-center" data-animate="fade-up">Common Questions</span>
                <h2 class="section-heading" data-animate="text-reveal">Financing FAQs</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Answers to the most common questions about our financing options.</p>
            </div>

            <div class="space-y-3" data-animate="stagger-up">
                <?php foreach ($faqs['pricing'] as $faqItem): ?>
                    <?php include 'includes/faq-accordion.php'; ?>
                <?php endforeach; ?>
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
