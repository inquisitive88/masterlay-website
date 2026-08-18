<?php
/**
 * Shop Admin — Products list
 */
require_once dirname(__DIR__) . '/includes/shop-admin-bootstrap.php';

$adminPageTitle = 'Shop Products';
$adminCurrentPage = 'shop-products';
$adminBreadcrumb = ['Shop Products' => ''];

$categoryFilter = (int) ($_GET['category'] ?? 0);
$categories = $pdo->query("SELECT * FROM shop_categories ORDER BY sort_order ASC, name ASC")->fetchAll();

$where = $categoryFilter > 0 ? "WHERE p.category_id = " . $categoryFilter : "";
$products = $pdo->query("
    SELECT p.*, c.name AS category_name,
           (SELECT image_url FROM shop_product_images i
             WHERE i.product_id = p.id
             ORDER BY i.is_primary DESC, i.sort_order ASC LIMIT 1) AS thumb
    FROM shop_products p
    LEFT JOIN shop_categories c ON c.id = p.category_id
    {$where}
    ORDER BY p.sort_order ASC, p.created_at DESC
")->fetchAll();

include dirname(__DIR__, 2) . '/admin/includes/admin-layout-top.php';
?>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Shop Products</h1>
        <p class="text-white/40 text-sm mt-1">Made-to-order pieces sold on the website. Drafts are never shown publicly.</p>
    </div>
    <a href="/shop/admin/product-edit" class="admin-btn admin-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Product
    </a>
</div>

<?= render_flash_messages() ?>

<div class="flex items-center gap-2 mb-5 flex-wrap">
    <a href="/shop/admin/products" class="admin-btn admin-btn-sm <?= $categoryFilter === 0 ? 'admin-btn-primary' : 'admin-btn-secondary' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
        <a href="/shop/admin/products?category=<?= (int) $cat['id'] ?>"
           class="admin-btn admin-btn-sm <?= $categoryFilter === (int) $cat['id'] ? 'admin-btn-primary' : 'admin-btn-secondary' ?>">
            <?= e($cat['name']) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (!$products): ?>
    <div class="admin-card text-center py-14">
        <h3 class="admin-empty-title">No products yet</h3>
        <p class="admin-empty-text">Add your first piece — bed, table, nightstand — and it can go live on the shop page.</p>
        <a href="/shop/admin/product-edit" class="admin-btn admin-btn-primary mt-4">Add Product</a>
    </div>
<?php else: ?>
    <div class="admin-card" style="padding:0;overflow-x:auto;">
        <table class="w-full text-sm" style="min-width:760px;">
            <thead>
                <tr class="text-left text-white/40 border-b border-white/10">
                    <th class="p-4">Product</th>
                    <th class="p-4">Category</th>
                    <th class="p-4 text-right">Price</th>
                    <th class="p-4 text-right">Delivery Fee</th>
                    <th class="p-4 text-center">Lead Time</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <?php if ($p['thumb']): ?>
                                    <img src="<?= e($p['thumb']) ?>" alt="" class="w-12 h-12 object-cover rounded-lg bg-dark-300">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-lg bg-dark-300 flex items-center justify-center text-white/20 text-xs">no img</div>
                                <?php endif; ?>
                                <div>
                                    <div class="text-white font-semibold"><?= e($p['name']) ?></div>
                                    <div class="text-white/30 text-xs">/shop/<?= e($p['slug']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-white/60"><?= e($p['category_name'] ?? '—') ?></td>
                        <td class="p-4 text-right text-white font-semibold"><?= shop_money((float) $p['price']) ?></td>
                        <td class="p-4 text-right text-white/60"><?= (float) $p['delivery_fee'] > 0 ? shop_money((float) $p['delivery_fee']) : 'Free' ?></td>
                        <td class="p-4 text-center text-white/60">~<?= (int) $p['lead_time_weeks'] ?> wk</td>
                        <td class="p-4 text-center">
                            <?php if ($p['status'] === 'active'): ?>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:rgba(34,197,94,0.12);color:#22c55e;">Active</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.45);">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-right">
                            <a href="/shop/admin/product-edit?id=<?= (int) $p['id'] ?>" class="admin-btn admin-btn-secondary admin-btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include dirname(__DIR__, 2) . '/admin/includes/admin-layout-bottom.php'; ?>
