<?php $basePath = $basePath ?? ''; ?>

<footer class="bg-dark-100 border-t border-white/5">
    <!-- Decorative top border -->
    <div class="h-px bg-gradient-to-r from-transparent via-primary to-transparent"></div>

    <div class="container-wide section-padding">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <!-- Column 1: Company Info -->
            <div>
                <a href="/" class="inline-block mb-5">
                    <img src="<?= IMG ?>/logos/logo-full.png" alt="<?= cms_setting('site_name', SITE_NAME) ?>" class="h-20 w-auto">
                </a>
                <p class="text-white/50 text-sm leading-relaxed mb-6">
                    Elevating homes through precision craftsmanship since <?= cms_setting('year_established', (string)YEAR_ESTABLISHED) ?>. Premium flooring, stairs, doors, and renovation services across Ontario.
                </p>
                <div class="flex items-center gap-3">
                    <a href="<?= cms_setting('instagram_url', INSTAGRAM) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="<?= cms_setting('facebook_url', FACEBOOK) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="<?= cms_setting('tiktok_url', TIKTOK) ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h4 class="font-heading text-base font-bold text-white mb-5">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="/" class="text-white/50 text-sm hover:text-primary transition-colors">Home</a></li>
                    <li><a href="<?= $basePath ?>about" class="text-white/50 text-sm hover:text-primary transition-colors">About Us</a></li>
                    <li><a href="<?= $basePath ?>services" class="text-white/50 text-sm hover:text-primary transition-colors">Services</a></li>
                    <li><a href="<?= $basePath ?>gallery" class="text-white/50 text-sm hover:text-primary transition-colors">Gallery</a></li>
                    <li><a href="<?= $basePath ?>financing" class="text-white/50 text-sm hover:text-primary transition-colors">Financing</a></li>
                    <li><a href="<?= $basePath ?>testimonials" class="text-white/50 text-sm hover:text-primary transition-colors">Testimonials</a></li>
                    <li><a href="<?= $basePath ?>contact" class="text-white/50 text-sm hover:text-primary transition-colors">Contact</a></li>
                </ul>
            </div>

            <!-- Column 3: Services -->
            <div>
                <h4 class="font-heading text-base font-bold text-white mb-5">Our Services</h4>
                <ul class="space-y-3">
                    <?php foreach ($services as $service): ?>
                        <li>
                            <a href="<?= $basePath ?>services/<?= $service['slug'] ?>" class="text-white/50 text-sm hover:text-primary transition-colors">
                                <?= $service['title'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Column 4: Contact Info -->
            <div>
                <h4 class="font-heading text-base font-bold text-white mb-5">Get In Touch</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:<?= cms_setting('phone', PHONE) ?>" class="text-white/50 text-sm hover:text-primary transition-colors"><?= cms_setting('phone_display', PHONE_DISPLAY) ?></a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:<?= cms_setting('email', EMAIL) ?>" class="text-white/50 text-sm hover:text-primary transition-colors"><?= cms_setting('email', EMAIL) ?></a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-white/50 text-sm"><?= cms_setting('address', ADDRESS) ?></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-white/50 text-sm"><?= cms_setting('hours', HOURS) ?></span>
                    </li>
                </ul>
                <a href="<?= $basePath ?>contact" class="btn btn-primary btn-sm mt-6 w-full">
                    Request Free Estimate
                </a>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="mt-12 pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-white/30 text-sm">
                &copy; <?= date('Y') ?> <?= cms_setting('site_name', SITE_NAME) ?> All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="<?= $basePath ?>faq" class="text-white/30 text-sm hover:text-primary transition-colors">FAQ</a>
                <a href="<?= $basePath ?>blog" class="text-white/30 text-sm hover:text-primary transition-colors">Blog</a>
            </div>
        </div>
    </div>
</footer>
