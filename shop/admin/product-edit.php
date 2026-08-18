<?php
/**
 * Shop Admin — Product create/edit with multi-image upload (Cloudflare R2).
 * One form handles product fields, new image uploads, and per-image
 * controls (primary / sort / delete) in a single save.
 */
require_once dirname(__DIR__) . '/includes/shop-admin-bootstrap.php';
require_once dirname(__DIR__, 2) . '/admin/includes/admin-r2.php';

$id = (int) ($_GET['id'] ?? 0);
$isEdit = $id > 0;
$product = null;

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM shop_products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        redirect('/shop/admin/products', 'error', 'Product not found.');
    }
}

$adminPageTitle = $isEdit ? 'Edit Product' : 'Add Product';
$adminCurrentPage = 'shop-products';
$adminBreadcrumb = ['Shop Products' => '/shop/admin/products', $adminPageTitle => ''];

$categories = $pdo->query("SELECT * FROM shop_categories ORDER BY sort_order ASC, name ASC")->fetchAll();

// ---------- Delete product ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete' && $isEdit) {
    require_csrf();
    // Remove R2 objects first, then the rows (images cascade)
    $imgs = $pdo->prepare("SELECT r2_key FROM shop_product_images WHERE product_id = ? AND r2_key != ''");
    $imgs->execute([$id]);
    foreach ($imgs->fetchAll(PDO::FETCH_COLUMN) as $key) {
        r2_delete_file($key);
    }
    $pdo->prepare("DELETE FROM shop_products WHERE id = ?")->execute([$id]);
    redirect('/shop/admin/products', 'success', 'Product deleted.');
}

// ---------- Save (create/update + images) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') !== 'delete') {
    require_csrf();

    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        set_flash('error', 'Product name is required.');
    } else {
        $categoryId = (int) ($_POST['category_id'] ?? 0) ?: null;
        // Inline new category (optional free-text field)
        $newCategory = trim($_POST['new_category'] ?? '');
        if ($newCategory !== '') {
            $catSlug = slugify($newCategory);
            $exists = $pdo->prepare("SELECT id FROM shop_categories WHERE slug = ?");
            $exists->execute([$catSlug]);
            $categoryId = (int) $exists->fetchColumn();
            if (!$categoryId) {
                $maxSort = (int) $pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM shop_categories")->fetchColumn();
                $pdo->prepare("INSERT INTO shop_categories (name, slug, sort_order) VALUES (?,?,?)")
                    ->execute([$newCategory, $catSlug, $maxSort + 1]);
                $categoryId = (int) $pdo->lastInsertId();
            }
        }

        $fields = [
            'category_id'     => $categoryId,
            'name'            => $name,
            'slug'            => shop_unique_slug($pdo, trim($_POST['slug'] ?? '') ?: $name, $id),
            'short_desc'      => trim($_POST['short_desc'] ?? ''),
            'long_desc'       => trim($_POST['long_desc'] ?? ''),
            'price'           => round(max(0, (float) ($_POST['price'] ?? 0)), 2),
            'deposit_amount'  => min(round(max(0, (float) ($_POST['deposit_amount'] ?? 0)), 2), round(max(0, (float) ($_POST['price'] ?? 0)), 2)),
            'delivery_fee'    => round(max(0, (float) ($_POST['delivery_fee'] ?? 0)), 2),
            'dimensions'      => trim($_POST['dimensions'] ?? ''),
            'wood_finish'     => trim($_POST['wood_finish'] ?? ''),
            'lead_time_weeks' => min(52, max(1, (int) ($_POST['lead_time_weeks'] ?? 3))),
            'status'          => ($_POST['status'] ?? '') === 'active' ? 'active' : 'draft',
        ];

        if ($isEdit) {
            $pdo->prepare("UPDATE shop_products SET category_id=?, name=?, slug=?, short_desc=?, long_desc=?,
                price=?, deposit_amount=?, delivery_fee=?, dimensions=?, wood_finish=?, lead_time_weeks=?, status=? WHERE id=?")
                ->execute([...array_values($fields), $id]);
        } else {
            $maxSort = (int) $pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM shop_products")->fetchColumn();
            $pdo->prepare("INSERT INTO shop_products
                (category_id, name, slug, short_desc, long_desc, price, deposit_amount, delivery_fee, dimensions, wood_finish, lead_time_weeks, status, sort_order)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)")
                ->execute([...array_values($fields), $maxSort + 1]);
            $id = (int) $pdo->lastInsertId();
            $isEdit = true;
        }

        // --- Existing image controls: delete / sort / primary / alt ---
        $deleteIds = array_map('intval', (array) ($_POST['img_delete'] ?? []));
        if ($deleteIds) {
            $in = implode(',', array_fill(0, count($deleteIds), '?'));
            $sel = $pdo->prepare("SELECT id, r2_key FROM shop_product_images WHERE product_id = ? AND id IN ($in)");
            $sel->execute([$id, ...$deleteIds]);
            foreach ($sel->fetchAll() as $img) {
                if ($img['r2_key'] !== '') r2_delete_file($img['r2_key']);
                $pdo->prepare("DELETE FROM shop_product_images WHERE id = ?")->execute([$img['id']]);
            }
        }
        foreach ((array) ($_POST['img_sort'] ?? []) as $imgId => $sort) {
            $pdo->prepare("UPDATE shop_product_images SET sort_order = ? WHERE id = ? AND product_id = ?")
                ->execute([(int) $sort, (int) $imgId, $id]);
        }
        foreach ((array) ($_POST['img_alt'] ?? []) as $imgId => $alt) {
            $pdo->prepare("UPDATE shop_product_images SET alt_text = ? WHERE id = ? AND product_id = ?")
                ->execute([trim((string) $alt), (int) $imgId, $id]);
        }

        // --- New images: pre-uploaded to R2 via upload-image.php (AJAX). The
        // form only carries their url+key, so no browser file-input quirks
        // can lose them. Validate the URL against our R2 public base.
        $uploadErrors = [];
        $r2Base = 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/';
        foreach ((array) ($_POST['uploaded_images'] ?? []) as $json) {
            $img = json_decode((string) $json, true);
            $url = (string) ($img['url'] ?? '');
            $key = (string) ($img['key'] ?? '');
            if ($url === '' || strpos($url, $r2Base) !== 0) {
                continue; // not one of ours
            }
            $maxImgSort = (int) $pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM shop_product_images WHERE product_id = " . $id)->fetchColumn();
            $pdo->prepare("INSERT INTO shop_product_images (product_id, image_url, r2_key, alt_text, sort_order) VALUES (?,?,?,?,?)")
                ->execute([$id, $url, $key, $name, $maxImgSort + 1]);
        }

        // --- Primary image (radio; also promote first image if none primary) ---
        $primaryId = (int) ($_POST['img_primary'] ?? 0);
        $pdo->prepare("UPDATE shop_product_images SET is_primary = 0 WHERE product_id = ?")->execute([$id]);
        if ($primaryId) {
            $pdo->prepare("UPDATE shop_product_images SET is_primary = 1 WHERE id = ? AND product_id = ?")
                ->execute([$primaryId, $id]);
        } else {
            $pdo->prepare("UPDATE shop_product_images SET is_primary = 1 WHERE product_id = ?
                ORDER BY sort_order ASC LIMIT 1")->execute([$id]);
        }

        if ($uploadErrors) {
            set_flash('error', 'Some images failed: ' . implode(' · ', $uploadErrors));
        }
        redirect('/shop/admin/product-edit?id=' . $id, 'success', 'Product saved.');
    }
}

$form = $product ?? [
    'category_id' => 0, 'name' => '', 'slug' => '', 'short_desc' => '', 'long_desc' => '',
    'price' => '', 'deposit_amount' => '', 'delivery_fee' => '', 'dimensions' => '', 'wood_finish' => '',
    'lead_time_weeks' => 3, 'status' => 'draft',
];
$images = [];
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM shop_product_images WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$id]);
    $images = $stmt->fetchAll();
}

include dirname(__DIR__, 2) . '/admin/includes/admin-layout-top.php';
?>
<style>
/* NOTE: deliberately NOT styling ::file-selector-button — custom styling on
   the native picker button breaks file selection in Safari 26 (files chosen
   through the styled button never attach). Leave the control fully native. */
.shop-file-input { color: rgba(255,255,255,0.6); font-size: 13px; max-width: 100%; }
</style>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <h1 class="font-heading text-2xl font-bold text-white"><?= e($adminPageTitle) ?></h1>
    <a href="/shop/admin/products" class="admin-btn admin-btn-secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Products
    </a>
</div>

<?= render_flash_messages() ?>

<form method="POST" action="/shop/admin/product-edit<?= $isEdit ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Details</h2>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="name">Name *</label>
                    <input type="text" id="name" name="name" value="<?= e($form['name']) ?>" class="admin-form-input" placeholder="e.g. Walnut Queen Bed Frame" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="admin-form-select">
                            <option value="0">No category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int) $cat['id'] ?>" <?= (int) ($form['category_id'] ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="new_category">…or new category</label>
                        <input type="text" id="new_category" name="new_category" class="admin-form-input" placeholder="e.g. Benches">
                    </div>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="short_desc">Short Description</label>
                    <input type="text" id="short_desc" name="short_desc" value="<?= e($form['short_desc']) ?>" class="admin-form-input" maxlength="500" placeholder="One-line summary shown on the shop grid">
                </div>
                <div class="admin-form-group mb-0">
                    <label class="admin-form-label" for="long_desc">Full Description</label>
                    <textarea id="long_desc" name="long_desc" rows="7" class="admin-form-input" placeholder="Materials, joinery, finish options, care instructions…"><?= e($form['long_desc'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Images</h2>
                <?php if ($images): ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5">
                        <?php foreach ($images as $img): ?>
                            <div class="rounded-xl border border-white/10 p-2 bg-dark-300">
                                <img src="<?= e($img['image_url']) ?>" alt="" class="w-full h-32 object-cover rounded-lg mb-2">
                                <label class="flex items-center gap-2 text-xs text-white/60 mb-1 cursor-pointer">
                                    <input type="radio" name="img_primary" value="<?= (int) $img['id'] ?>" <?= $img['is_primary'] ? 'checked' : '' ?>>
                                    Primary
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="img_sort[<?= (int) $img['id'] ?>]" value="<?= (int) $img['sort_order'] ?>" class="admin-form-input" style="width:64px;padding:4px 8px;" title="Sort order">
                                    <label class="flex items-center gap-1 text-xs text-red-400 cursor-pointer ml-auto">
                                        <input type="checkbox" name="img_delete[]" value="<?= (int) $img['id'] ?>"> Delete
                                    </label>
                                </div>
                                <input type="text" name="img_alt[<?= (int) $img['id'] ?>]" value="<?= e($img['alt_text']) ?>" class="admin-form-input mt-1" style="padding:4px 8px;font-size:12px;" placeholder="Alt text">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="admin-upload-area" id="uploadArea">
                    <p class="text-white/40 text-sm mb-1">Drop images here, or choose files below</p>
                    <p class="text-white/20 text-xs mb-3">Images upload immediately — Save attaches them to the product (uploader v3)</p>
                    <!-- EXACT pattern from the portal receipt uploader (proven in Safari):
                         single-file input, display:none, native label trigger. Safari 26
                         fires CANCEL on `multiple` file dialogs — so: one photo per pick,
                         as many picks as you like; drag & drop still takes batches. -->
                    <input type="file" id="imageInput" accept="image/*" style="display:none;">
                    <label for="imageInput" id="addPhotoLabel" class="admin-btn admin-btn-secondary admin-btn-sm" style="cursor:pointer;">Add Photo</label>
                    <p id="attachCount" class="text-xs mt-3" style="color:#FAA416;">No new images yet.</p>
                    <div id="uploadPreview" class="mt-4 grid grid-cols-3 gap-2" style="display:none;"></div>
                    <div id="uploadedFields"></div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Pricing & Build</h2>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="price">Price (CAD, before HST) *</label>
                    <input type="number" step="0.01" min="0" id="price" name="price" value="<?= e((string) $form['price']) ?>" class="admin-form-input" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="deposit_amount">Deposit / Upfront Amount (CAD)</label>
                    <input type="number" step="0.01" min="0" id="deposit_amount" name="deposit_amount" value="<?= e((string) ($form['deposit_amount'] ?? '')) ?>" class="admin-form-input">
                    <p class="admin-form-help">Charged at checkout; balance due at delivery/pickup. 0 = full price charged upfront.</p>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="delivery_fee">Local Delivery Fee (0 = free)</label>
                    <input type="number" step="0.01" min="0" id="delivery_fee" name="delivery_fee" value="<?= e((string) $form['delivery_fee']) ?>" class="admin-form-input">
                    <p class="admin-form-help">Cart charges ONE delivery fee per order — the highest among its items.</p>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="lead_time_weeks">Lead Time (weeks)</label>
                    <input type="number" min="1" max="52" id="lead_time_weeks" name="lead_time_weeks" value="<?= (int) $form['lead_time_weeks'] ?>" class="admin-form-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label" for="dimensions">Dimensions</label>
                    <input type="text" id="dimensions" name="dimensions" value="<?= e($form['dimensions']) ?>" class="admin-form-input" placeholder='e.g. 65" W × 85" L × 48" H'>
                </div>
                <div class="admin-form-group mb-0">
                    <label class="admin-form-label" for="wood_finish">Wood & Finish</label>
                    <input type="text" id="wood_finish" name="wood_finish" value="<?= e($form['wood_finish']) ?>" class="admin-form-input" placeholder="e.g. Solid walnut, matte poly">
                </div>
            </div>

            <div class="admin-card">
                <h2 class="admin-card-title mb-5">Publish</h2>
                <label class="flex items-center gap-3 cursor-pointer mb-2">
                    <input type="checkbox" name="status" value="active" <?= ($form['status'] ?? '') === 'active' ? 'checked' : '' ?> class="w-4 h-4 rounded border-white/20 bg-dark-100 text-primary focus:ring-primary/30">
                    <span class="text-white/70 text-sm">Active — visible and buyable on the shop</span>
                </label>
                <div class="admin-form-group mb-0">
                    <label class="admin-form-label" for="slug">URL Slug</label>
                    <input type="text" id="slug" name="slug" value="<?= e($form['slug']) ?>" class="admin-form-input" placeholder="auto-generated from name">
                </div>
            </div>

            <button type="submit" class="admin-btn admin-btn-primary w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Product
            </button>
        </div>
    </div>
</form>

<?php if ($isEdit): ?>
<form method="POST" action="/shop/admin/product-edit?id=<?= $id ?>" class="mt-6"
      onsubmit="return confirm('Delete this product and all its images? This cannot be undone.');">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="delete">
    <button type="submit" class="admin-btn admin-btn-secondary" style="border-color:rgba(239,68,68,0.4);color:#ef4444;">Delete Product</button>
</form>
<?php endif; ?>

<script>
var imageInput = document.getElementById('imageInput');
var attachCount = document.getElementById('attachCount');
var uploadedCount = 0;
var uploadedSigs = {}; // name:size -> true; a file uploads exactly once


window.addEventListener('error', function (ev) {
});

function setStatus(msg) { attachCount.textContent = msg; }

function uploadFiles(fileList) {
    var files = [];
    for (var i = 0; i < fileList.length; i++) {
        var sig = fileList[i].name + ':' + fileList[i].size;
        if (uploadedSigs[sig]) { continue; }
        uploadedSigs[sig] = true;
        files.push(fileList[i]);
    }
    if (!files.length) return;
    var done = 0, failed = [];
    setStatus('Uploading 0/' + files.length + '…');
    files.forEach(function (file) {
        var fd = new FormData();
        fd.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        fd.append('image', file);
        fetch('/shop/admin/upload-image', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                done++;
                if (d.success) {
                    uploadedCount++;
                    var h = document.createElement('input');
                    h.type = 'hidden';
                    h.name = 'uploaded_images[]';
                    h.value = JSON.stringify({ url: d.url, key: d.key });
                    document.getElementById('uploadedFields').appendChild(h);
                    var preview = document.getElementById('uploadPreview');
                    preview.style.display = '';
                    var img = document.createElement('img');
                    img.src = d.url;
                    img.className = 'h-20 w-full object-cover rounded-lg';
                    preview.appendChild(img);
                } else {
                    failed.push(file.name + ': ' + (d.error || 'failed'));
                }
                setStatus(done < files.length
                    ? 'Uploading ' + done + '/' + files.length + '…'
                    : uploadedCount + ' image' + (uploadedCount === 1 ? '' : 's') + ' uploaded ✓ — press Save Product to attach'
                      + (failed.length ? ' · FAILED: ' + failed.join(' · ') : ''));
            })
            .catch(function (err) {
                done++;
                failed.push(file.name + ': ' + err.message);
                setStatus('Upload error: ' + failed.join(' · '));
            });
    });
}

function onPicked(src) {
    if (imageInput.files.length) {
        uploadFiles(imageInput.files);
        imageInput.value = ''; // ready for the next single-file pick
    }
}
imageInput.addEventListener('change', function () { onPicked('change'); });
imageInput.addEventListener('input', function () { onPicked('input'); });
// admin.js binds a click handler on every .admin-upload-area that calls
// input.click() — combined with the label's native trigger, that opened TWO
// picker dialogs per click, and Safari discards the selection when dialog
// requests collide (the "native input clicked" double-log). Stop the label
// and input clicks from bubbling so exactly ONE dialog ever opens.
imageInput.addEventListener('click', function (e) { e.stopPropagation(); });
document.getElementById('addPhotoLabel').addEventListener('click', function (e) { e.stopPropagation(); });


var uploadArea = document.getElementById('uploadArea');
['dragenter','dragover','dragleave','drop'].forEach(function (n) {
    uploadArea.addEventListener(n, function (e) { e.preventDefault(); e.stopPropagation(); });
});
['dragenter','dragover'].forEach(function (n) {
    uploadArea.addEventListener(n, function () { uploadArea.classList.add('border-primary'); });
});
['dragleave','drop'].forEach(function (n) {
    uploadArea.addEventListener(n, function () { uploadArea.classList.remove('border-primary'); });
});
uploadArea.addEventListener('drop', function (e) {
    if (e.dataTransfer.files.length) uploadFiles(e.dataTransfer.files);
});
</script>

<?php include dirname(__DIR__, 2) . '/admin/includes/admin-layout-bottom.php'; ?>
