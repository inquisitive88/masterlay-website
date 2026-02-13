<?php
/**
 * CMS Admin - Quote Pricing
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Quote Pricing';
$adminCurrentPage = 'quote-pricing';
$adminBreadcrumb = ['Quote Pricing' => ''];

// Define pricing fields by service type
$pricingFields = [
    'sanding' => [
        'label' => 'Sanding & Re-Staining',
        'fields' => [
            'sanding_base_per_staircase'    => ['label' => 'Base per staircase', 'default' => 2000.00],
            'sanding_posts'                 => ['label' => 'Posts (per post)', 'default' => 50.00],
            'sanding_railing'               => ['label' => 'Railing (per staircase)', 'default' => 50.00],
            'sanding_spindles_replace_metal' => ['label' => 'Spindles — Replace with metal (each)', 'default' => 20.00],
            'sanding_spindles_paint_white'  => ['label' => 'Spindles — Paint white (each)', 'default' => 14.00],
            'sanding_spindles_keep_metal'   => ['label' => 'Spindles — Keep existing metal (each)', 'default' => 10.00],
        ],
    ],
    'recapping' => [
        'label' => 'Recapping',
        'fields' => [
            'recap_box_step'              => ['label' => 'Box step (each)', 'default' => 120.00],
            'recap_open_step'             => ['label' => 'Open step — left/right (each)', 'default' => 170.00],
            'recap_spindles_new_metal'    => ['label' => 'Spindles — New plain metal (each)', 'default' => 20.00],
            'recap_spindles_reuse_wood'   => ['label' => 'Spindles — Reuse wood, paint white (each)', 'default' => 14.00],
            'recap_spindles_reuse_metal'  => ['label' => 'Spindles — Reuse metal (each)', 'default' => 10.00],
            'recap_railing_per_lf'        => ['label' => 'New railing (per linear foot)', 'default' => 50.00],
            'recap_staining_per_staircase' => ['label' => 'Staining (per staircase)', 'default' => 400.00],
            'recap_posts_existing'        => ['label' => 'Posts — Existing to stain (each)', 'default' => 50.00],
            'recap_posts_new'             => ['label' => 'Posts — New (each)', 'default' => 50.00],
        ],
    ],
    'flooring' => [
        'label' => 'Flooring',
        'fields' => [
            'floor_demo_carpet'           => ['label' => 'Demolition — Carpet (per sqft)', 'default' => 0.40],
            'floor_demo_hardwood'         => ['label' => 'Demolition — Hardwood (per sqft)', 'default' => 0.50],
            'floor_demo_tiles'            => ['label' => 'Demolition — Tiles (per sqft)', 'default' => 1.00],
            'floor_material_hardwood'     => ['label' => 'Material — Hardwood (per sqft)', 'default' => 5.00],
            'floor_material_engineered'   => ['label' => 'Material — Engineered Hardwood (per sqft)', 'default' => 4.00],
            'floor_material_vinyl'        => ['label' => 'Material — Vinyl (per sqft)', 'default' => 3.00],
            'floor_material_laminate'     => ['label' => 'Material — Laminate (per sqft)', 'default' => 2.50],
            'floor_labour_vinyl_laminate' => ['label' => 'Labour — Vinyl/Laminate (per sqft)', 'default' => 1.00],
            'floor_labour_eng_glue_nail'  => ['label' => 'Labour — Eng. Hardwood glue+nail (per sqft)', 'default' => 2.00],
            'floor_labour_eng_nails'      => ['label' => 'Labour — Eng. Hardwood nails only (per sqft)', 'default' => 1.80],
            'floor_labour_hardwood'       => ['label' => 'Labour — Hardwood (per sqft)', 'default' => 2.30],
            'floor_labour_minimum'        => ['label' => 'Labour — Minimum charge', 'default' => 600.00],
            'floor_baseboard_per_lf'      => ['label' => 'Baseboard (per linear foot)', 'default' => 2.00],
            'floor_shoe_molding_per_lf'   => ['label' => 'Shoe molding (per linear foot)', 'default' => 1.50],
        ],
    ],
];

// Seed missing defaults (INSERT IGNORE ensures existing keys are not overwritten)
$seedStmt = $pdo->prepare(
    "INSERT IGNORE INTO ml_cms_quote_pricing (pricing_key, pricing_value, service_type, label) VALUES (?, ?, ?, ?)"
);
foreach ($pricingFields as $serviceType => $group) {
    foreach ($group['fields'] as $key => $field) {
        $seedStmt->execute([$key, $field['default'], $serviceType, $field['label']]);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $stmt = $pdo->prepare(
        "INSERT INTO ml_cms_quote_pricing (pricing_key, pricing_value, service_type, label)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE pricing_value = VALUES(pricing_value), updated_at = NOW()"
    );

    foreach ($pricingFields as $serviceType => $group) {
        foreach ($group['fields'] as $key => $field) {
            $value = floatval($_POST[$key] ?? $field['default']);
            $stmt->execute([$key, $value, $serviceType, $field['label']]);
        }
    }

    set_flash('success', 'Quote pricing saved successfully.');
    redirect('/admin/quote-pricing');
}

// Load current values
$currentPricing = [];
$rows = $pdo->query("SELECT pricing_key, pricing_value FROM ml_cms_quote_pricing")->fetchAll();
foreach ($rows as $row) {
    $currentPricing[$row['pricing_key']] = $row['pricing_value'];
}

include __DIR__ . '/includes/admin-layout-top.php';
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Quote Pricing</h1>
        <p class="text-white/40 text-sm mt-1">Manage pricing for the instant quote calculator</p>
    </div>
</div>

<form method="POST" action="/admin/quote-pricing">
    <?= csrf_field() ?>

    <?php foreach ($pricingFields as $serviceType => $group): ?>
        <div class="admin-card mb-6">
            <h2 class="admin-card-title mb-5"><?= e($group['label']) ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($group['fields'] as $key => $field): ?>
                    <div class="admin-form-group mb-0">
                        <label class="admin-form-label" for="<?= $key ?>"><?= e($field['label']) ?></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-sm">$</span>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="<?= $key ?>"
                                name="<?= $key ?>"
                                value="<?= e(number_format((float)($currentPricing[$key] ?? $field['default']), 2, '.', '')) ?>"
                                class="admin-form-input pl-7"
                            >
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="admin-btn admin-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Pricing
        </button>
    </div>
</form>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
