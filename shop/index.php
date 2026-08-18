<?php
/**
 * Shop — public catalog page (/shop/)
 */
require_once __DIR__ . '/includes/shop-public-bootstrap.php';

$pageTitle = 'Custom Woodworking Shop | ' . SITE_NAME;
$pageDescription = 'Handcrafted solid-wood furniture made to order in Brampton: beds, tables, nightstands, coffee tables, and shoe cabinets. Built by Masterlay Renovations.';
$currentPage = 'shop';
$heroTitle = 'Custom Woodworking';
$heroSubtitle = 'Handcrafted furniture, made to order in our Brampton shop';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Shop' => ''];

$categories = $pdo->query("
    SELECT c.* FROM shop_categories c
    WHERE EXISTS (SELECT 1 FROM shop_products p WHERE p.category_id = c.id AND p.status = 'active')
    ORDER BY c.sort_order ASC")->fetchAll();
$products = shop_active_products($pdo);
$root = dirname(__DIR__);
$basePath = '/'; // shop pages live one level deep — asset URLs must be root-absolute
?>
<!DOCTYPE html>
<html lang="en">
<?php include $root . '/includes/head.php'; ?>
<body class="bg-dark text-white antialiased">
<?php include $root . '/includes/loader.php'; ?>
<?php include $root . '/includes/header.php'; ?>

<main>
    <?php include $root . '/includes/page-hero.php'; ?>

    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="text-center mb-10">
                <span class="section-label justify-center" data-animate="fade-up">Made To Order</span>
                <h2 class="section-heading" data-animate="text-reveal">Solid Wood, Built For You</h2>
                <p class="section-subheading mx-auto" data-animate="fade-up">Every piece is built when you order it — choose your piece, and we'll craft it in our Brampton workshop.</p>
            </div>

            <?php if ($categories): ?>
            <div class="flex flex-wrap items-center justify-center gap-3 mb-12" id="shopFilters" data-animate="fade-up">
                <button class="shop-filter-btn px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 border bg-primary text-dark border-primary active" data-filter="all">All</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="shop-filter-btn px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 border bg-transparent text-white/60 border-white/10 hover:border-primary/30 hover:text-white" data-filter="<?= htmlspecialchars($cat['slug']) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!$products): ?>
                <p class="text-center text-white/40 py-20">New pieces are being added — check back soon, or <a href="/contact" class="text-primary">contact us</a> for a custom build.</p>
            <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="shopGrid" data-animate="stagger-up">
                <?php foreach ($products as $p): ?>
                <a href="/shop/item?slug=<?= urlencode($p['slug']) ?>"
                   class="shop-card group block rounded-2xl overflow-hidden border border-white/10 bg-white/[0.02] hover:border-primary/40 transition-all duration-300"
                   data-category="<?= htmlspecialchars($p['category_slug'] ?? '') ?>">
                    <div class="relative overflow-hidden" style="aspect-ratio:4/3;">
                        <?php if ($p['primary_image']): ?>
                            <img src="<?= htmlspecialchars($p['primary_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-white/20">Photo coming soon</div>
                        <?php endif; ?>
                        <?php if ($p['category_name']): ?>
                            <span class="badge absolute top-4 left-4"><?= htmlspecialchars($p['category_name']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading text-lg font-bold text-white group-hover:text-primary transition-colors"><?= htmlspecialchars($p['name']) ?></h3>
                        <?php if ($p['short_desc']): ?>
                            <p class="text-white/50 text-sm mt-1 line-clamp-2"><?= htmlspecialchars($p['short_desc']) ?></p>
                        <?php endif; ?>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-primary font-bold text-lg"><?= shop_money((float) $p['price']) ?></span>
                            <span class="text-white/40 text-xs">Ships in ~<?= (int) $p['lead_time_weeks'] ?> weeks</span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include $root . '/includes/cta-section.php'; ?>
</main>

<?php include $root . '/includes/footer.php'; ?>
<?php include $root . '/includes/scripts.php'; ?>
<script src="/shop/assets/shop.js"></script>
<script>
document.querySelectorAll('.shop-filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.shop-filter-btn').forEach(function (b) {
            b.classList.remove('bg-primary', 'text-dark', 'border-primary', 'active');
            b.classList.add('bg-transparent', 'text-white/60', 'border-white/10');
        });
        btn.classList.add('bg-primary', 'text-dark', 'border-primary', 'active');
        btn.classList.remove('bg-transparent', 'text-white/60', 'border-white/10');
        var f = btn.dataset.filter;
        document.querySelectorAll('.shop-card').forEach(function (card) {
            card.style.display = (f === 'all' || card.dataset.category === f) ? '' : 'none';
        });
    });
});
</script>
</body>
</html>
