<?php
/**
 * CMS Admin - Site Settings
 */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$adminPageTitle = 'Settings';
$adminCurrentPage = 'settings';
$adminBreadcrumb = ['Settings' => ''];

// Define setting groups and fields
$settingFields = [
    'company' => [
        'label' => 'Company Information',
        'fields' => [
            'site_name'        => ['label' => 'Site Name', 'type' => 'text', 'placeholder' => 'Masterlay Renovations Inc.'],
            'site_tagline'     => ['label' => 'Tagline', 'type' => 'text', 'placeholder' => 'Elevating Homes Through Precision Craftsmanship'],
            'site_url'         => ['label' => 'Site URL', 'type' => 'url', 'placeholder' => 'https://masterlayrenovations.ca'],
            'phone'            => ['label' => 'Phone Number', 'type' => 'text', 'placeholder' => '+14318877709'],
            'phone_display'    => ['label' => 'Phone Display', 'type' => 'text', 'placeholder' => '(431) 887-7709'],
            'email'            => ['label' => 'Email', 'type' => 'email', 'placeholder' => 'inquiry@masterlayrenovations.ca'],
            'address'          => ['label' => 'Address', 'type' => 'text', 'placeholder' => 'Brampton, ON, Canada'],
            'hours'            => ['label' => 'Business Hours', 'type' => 'text', 'placeholder' => 'Sun - Sat: 7:00 AM - 8:00 PM'],
            'year_established' => ['label' => 'Year Established', 'type' => 'number', 'placeholder' => '2018'],
        ],
    ],
    'social' => [
        'label' => 'Social Media',
        'fields' => [
            'instagram_url' => ['label' => 'Instagram URL', 'type' => 'url', 'placeholder' => 'https://instagram.com/...'],
            'facebook_url'  => ['label' => 'Facebook URL', 'type' => 'url', 'placeholder' => 'https://facebook.com/...'],
            'tiktok_url'    => ['label' => 'TikTok URL', 'type' => 'url', 'placeholder' => 'https://tiktok.com/...'],
        ],
    ],
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $stmt = $pdo->prepare("INSERT INTO ml_cms_settings (setting_key, setting_value, setting_group)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()");

    foreach ($settingFields as $group => $groupData) {
        foreach ($groupData['fields'] as $key => $field) {
            $value = trim($_POST[$key] ?? '');
            $stmt->execute([$key, $value, $group]);
        }
    }

    set_flash('success', 'Settings saved successfully.');
    redirect('/admin/settings');
}

// Load current values
$currentSettings = [];
$rows = $pdo->query("SELECT setting_key, setting_value FROM ml_cms_settings")->fetchAll();
foreach ($rows as $row) {
    $currentSettings[$row['setting_key']] = $row['setting_value'];
}

include __DIR__ . '/includes/admin-layout-top.php';
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-heading text-2xl font-bold text-white">Site Settings</h1>
        <p class="text-white/40 text-sm mt-1">Manage company information and social media links</p>
    </div>
</div>

<form method="POST" action="/admin/settings">
    <?= csrf_field() ?>

    <?php foreach ($settingFields as $group => $groupData): ?>
        <div class="admin-card mb-6">
            <h2 class="admin-card-title mb-5"><?= e($groupData['label']) ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php foreach ($groupData['fields'] as $key => $field): ?>
                    <div class="admin-form-group mb-0">
                        <label class="admin-form-label" for="<?= $key ?>"><?= e($field['label']) ?></label>
                        <input
                            type="<?= $field['type'] ?>"
                            id="<?= $key ?>"
                            name="<?= $key ?>"
                            value="<?= e($currentSettings[$key] ?? '') ?>"
                            placeholder="<?= e($field['placeholder']) ?>"
                            class="admin-form-input"
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="flex items-center gap-3">
        <button type="submit" class="admin-btn admin-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Settings
        </button>
    </div>
</form>

<?php include __DIR__ . '/includes/admin-layout-bottom.php'; ?>
