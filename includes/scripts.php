<?php $basePath = $basePath ?? ''; ?>

<!-- GSAP + Plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

<!-- Lenis Smooth Scroll -->
<script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>

<!-- SplitType -->
<script src="https://unpkg.com/split-type@0.3.4/umd/index.min.js"></script>

<!-- Custom Scripts -->
<script src="<?= $basePath ?>assets/js/smooth-scroll.js"></script>
<script src="<?= $basePath ?>assets/js/loader.js"></script>
<script src="<?= $basePath ?>assets/js/navigation.js"></script>
<script src="<?= $basePath ?>assets/js/animations.js"></script>
<script src="<?= $basePath ?>assets/js/counter.js"></script>

<!-- Page-specific scripts (loaded conditionally) -->
<?php if (isset($currentPage) && $currentPage === 'contact'): ?>
<script src="<?= $basePath ?>assets/js/forms.js"></script>
<?php endif; ?>
<?php if (isset($currentPage) && $currentPage === 'gallery'): ?>
<script src="<?= $basePath ?>assets/js/gallery.js"></script>
<?php endif; ?>
<?php if (isset($currentPage) && ($currentPage === 'faq' || isset($loadFaqJs))): ?>
<script src="<?= $basePath ?>assets/js/faq.js"></script>
<?php endif; ?>

<!-- App Init (always last) -->
<script src="<?= $basePath ?>assets/js/app.js"></script>
