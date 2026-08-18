<?php
/**
 * Shop — product detail page (/shop/item?slug=…)
 */
require_once __DIR__ . '/includes/shop-public-bootstrap.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$product = $slug !== '' ? shop_product_by_slug($pdo, $slug) : null;
if (!$product) {
    http_response_code(404);
    header('Location: /shop');
    exit;
}
$images = shop_product_images($pdo, (int) $product['id']);
$primary = $images[0]['image_url'] ?? '';

$pageTitle = $product['name'] . ' | Custom Woodworking | ' . SITE_NAME;
$pageDescription = $product['short_desc'] !== '' ? $product['short_desc']
    : 'Handcrafted ' . $product['name'] . ' — made to order by Masterlay Renovations in Brampton.';
$currentPage = 'shop';
$heroTitle = $product['name'];
$heroSubtitle = $product['category_name'] ? 'Custom ' . $product['category_name'] : 'Custom Woodworking';
$heroBg = IMG . '/hero/services-page.jpg';
$breadcrumbs = ['Home' => '/', 'Shop' => '/shop', $product['name'] => ''];
$root = dirname(__DIR__);
$basePath = '/'; // shop pages live one level deep — asset URLs must be root-absolute

// Product structured data for search engines
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product['name'],
    'description' => $pageDescription,
    'image' => array_values(array_map(fn($i) => $i['image_url'], $images)),
    'brand' => ['@type' => 'Brand', 'name' => SITE_NAME],
    'offers' => [
        '@type' => 'Offer',
        'price' => number_format((float) $product['price'], 2, '.', ''),
        'priceCurrency' => 'CAD',
        'availability' => 'https://schema.org/MadeToOrder',
        'url' => 'https://masterlayrenovations.ca/shop/item?slug=' . $product['slug'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<?php include $root . '/includes/head.php'; ?>
<body class="bg-dark text-white antialiased">
<?php include $root . '/includes/loader.php'; ?>
<?php include $root . '/includes/header.php'; ?>
<script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_SLASHES) ?></script>

<main>
    <?php include $root . '/includes/page-hero.php'; ?>

    <section class="section-padding bg-dark">
        <div class="container-wide">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                <!-- Gallery -->
                <div>
                    <div class="rounded-2xl overflow-hidden border border-white/10 bg-white/[0.02]" style="aspect-ratio:4/3;">
                        <?php if ($primary): ?>
                            <img id="mainImage" src="<?= htmlspecialchars($primary) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-white/20">Photos coming soon</div>
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                    <div class="grid grid-cols-5 gap-3 mt-3">
                        <?php foreach ($images as $img): ?>
                            <button type="button" class="thumb-btn rounded-xl overflow-hidden border border-white/10 hover:border-primary/50 transition" style="aspect-ratio:1;"
                                    onclick="document.getElementById('mainImage').src=this.querySelector('img').src">
                                <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="<?= htmlspecialchars($img['alt_text'] ?: $product['name']) ?>" class="w-full h-full object-cover" loading="lazy">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Details -->
                <div>
                    <?php if ($product['category_name']): ?><span class="badge"><?= htmlspecialchars($product['category_name']) ?></span><?php endif; ?>
                    <h1 class="font-heading text-3xl font-bold text-white mt-3"><?= htmlspecialchars($product['name']) ?></h1>
                    <?php
                    $depositAmt = (float) ($product['deposit_amount'] ?? 0);
                    $hasDeposit = $depositAmt > 0 && $depositAmt < (float) $product['price'];
                    ?>
                    <div class="flex items-baseline gap-3 mt-4">
                        <span class="text-primary font-bold text-3xl"><?= shop_money((float) $product['price']) ?></span>
                        <span class="text-white/40 text-sm">+ HST</span>
                    </div>
                    <?php if ($hasDeposit): ?>
                        <div class="mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1.5" style="background:rgba(250,164,22,0.1);border:1px solid rgba(250,164,22,0.3);">
                            <span class="text-primary text-sm font-semibold">Reserve with a <?= shop_money($depositAmt) ?> deposit</span>
                            <span class="text-white/40 text-xs">— balance due at delivery or pickup</span>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-center gap-2 mt-4 text-white/60 text-sm">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Made to order — ready in about <b class="text-white">&nbsp;<?= (int) $product['lead_time_weeks'] ?> weeks</b>
                    </div>
                    <?php if ((float) $product['delivery_fee'] > 0): ?>
                        <div class="flex items-center gap-2 mt-2 text-white/60 text-sm">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            Local delivery <?= shop_money((float) $product['delivery_fee']) ?> — or free pickup in Brampton
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2 mt-2 text-white/60 text-sm">Free local delivery or pickup in Brampton</div>
                    <?php endif; ?>

                    <div class="flex items-center gap-4 mt-8">
                        <div class="flex items-center border border-white/15 rounded-full overflow-hidden">
                            <button type="button" class="px-4 py-2.5 text-white/60 hover:text-white" onclick="var q=document.getElementById('qty');q.value=Math.max(1,parseInt(q.value)-1)">−</button>
                            <input id="qty" type="number" value="1" min="1" max="10" class="w-12 text-center bg-transparent text-white border-0 focus:outline-none" style="appearance:textfield;">
                            <button type="button" class="px-4 py-2.5 text-white/60 hover:text-white" onclick="var q=document.getElementById('qty');q.value=Math.min(10,parseInt(q.value)+1)">+</button>
                        </div>
                        <button type="button" id="addToCartBtn" class="btn-primary flex-1"
                                data-id="<?= (int) $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>">
                            Add to Cart
                        </button>
                    </div>
                    <p id="addedMsg" class="text-primary text-sm mt-3" style="display:none;">Added — <a href="/shop/cart" class="underline">view cart</a></p>

                    <?php if ($product['dimensions'] || $product['wood_finish']): ?>
                    <div class="mt-8 border-t border-white/10 pt-6 space-y-2 text-sm">
                        <?php if ($product['dimensions']): ?>
                            <div class="flex gap-3"><span class="text-white/40 w-28">Dimensions</span><span class="text-white/80"><?= htmlspecialchars($product['dimensions']) ?></span></div>
                        <?php endif; ?>
                        <?php if ($product['wood_finish']): ?>
                            <div class="flex gap-3"><span class="text-white/40 w-28">Wood & Finish</span><span class="text-white/80"><?= htmlspecialchars($product['wood_finish']) ?></span></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (trim((string) $product['long_desc']) !== ''): ?>
                    <div class="mt-6 text-white/60 text-sm leading-relaxed">
                        <?= nl2br(htmlspecialchars($product['long_desc'])) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include $root . '/includes/cta-section.php'; ?>
</main>

<?php include $root . '/includes/footer.php'; ?>
<?php include $root . '/includes/scripts.php'; ?>
<script src="/shop/assets/shop.js"></script>
<script>
document.getElementById('addToCartBtn').addEventListener('click', function () {
    var qty = parseInt(document.getElementById('qty').value) || 1;
    ShopCart.add(<?= (int) $product['id'] ?>, qty);
    document.getElementById('addedMsg').style.display = '';
});
</script>
</body>
</html>
