<?php
// ============================================
// Masterlay Renovations - Quote Form Handler
// ============================================

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load dependencies
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db-connection.php';

// Collect and sanitize input
$name         = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$email        = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
$phone        = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$address      = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$notes        = trim(filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$service_type = trim(filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

// Validate required fields
$errors = [];
if (strlen($name) < 2) $errors[] = 'Please enter your full name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
$cleanedPhone = preg_replace('/[\s\-\(\)\+\.]/', '', $phone);
if (!preg_match('/^\d{10,11}$/', $cleanedPhone)) $errors[] = 'Please enter a valid phone number.';
if (!in_array($service_type, ['sanding', 'recapping', 'flooring'])) $errors[] = 'Please select a valid service type.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Load pricing from database
$pricing = [];
if ($pdo) {
    $rows = $pdo->query("SELECT pricing_key, pricing_value FROM ml_cms_quote_pricing")->fetchAll();
    foreach ($rows as $row) {
        $pricing[$row['pricing_key']] = (float)$row['pricing_value'];
    }
}

// Fallback defaults if DB pricing not loaded
$defaults = [
    // Stairs — Sanding
    'sanding_base_per_staircase' => 2000, 'sanding_posts' => 50, 'sanding_railing' => 50,
    'sanding_spindles_replace_metal' => 20, 'sanding_spindles_paint_white' => 14, 'sanding_spindles_keep_metal' => 10,
    // Stairs — Recapping
    'recap_box_step' => 120, 'recap_open_step' => 170, 'recap_spindles_new_metal' => 20,
    'recap_spindles_reuse_wood' => 14, 'recap_spindles_reuse_metal' => 10, 'recap_railing_per_lf' => 50,
    'recap_staining_per_staircase' => 400, 'recap_posts_existing' => 50, 'recap_posts_new' => 50,
    // Flooring
    'floor_demo_carpet' => 0.40, 'floor_demo_hardwood' => 0.50, 'floor_demo_tiles' => 1.00,
    'floor_material_hardwood' => 5.00, 'floor_material_engineered' => 4.00,
    'floor_material_vinyl' => 3.00, 'floor_material_laminate' => 2.50,
    'floor_labour_vinyl_laminate' => 1.00, 'floor_labour_eng_glue_nail' => 2.00,
    'floor_labour_eng_nails' => 1.80, 'floor_labour_hardwood' => 2.30, 'floor_labour_minimum' => 600,
    'floor_baseboard_per_lf' => 2.00, 'floor_shoe_molding_per_lf' => 1.50,
];
foreach ($defaults as $k => $v) {
    if (!isset($pricing[$k])) $pricing[$k] = $v;
}

// Calculate total and build line items
$lineItems = [];
$total = 0;

if ($service_type === 'sanding') {
    $serviceLabel = 'Sanding & Re-Staining';

    // Staircases
    $staircases = max(1, intval($_POST['sanding_staircases'] ?? 1));
    $cost = $staircases * $pricing['sanding_base_per_staircase'];
    $lineItems[] = ['desc' => $staircases . ' Staircase' . ($staircases > 1 ? 's' : ''), 'qty' => $staircases, 'unit' => $pricing['sanding_base_per_staircase'], 'cost' => $cost];
    $total += $cost;

    // Railing & Posts
    $hasRailing = ($_POST['sanding_has_railing'] ?? 'no') === 'yes';
    if ($hasRailing) {
        // Posts
        $postsQty = max(0, intval($_POST['sanding_posts_qty'] ?? 0));
        if ($postsQty > 0) {
            $cost = $postsQty * $pricing['sanding_posts'];
            $lineItems[] = ['desc' => $postsQty . ' Post' . ($postsQty > 1 ? 's' : ''), 'qty' => $postsQty, 'unit' => $pricing['sanding_posts'], 'cost' => $cost];
            $total += $cost;
        }

        // Railing
        $railingPresent = ($_POST['sanding_railing_present'] ?? 'no') === 'yes';
        if ($railingPresent) {
            $cost = $staircases * $pricing['sanding_railing'];
            $lineItems[] = ['desc' => 'Railing (' . $staircases . ' staircase' . ($staircases > 1 ? 's' : '') . ')', 'qty' => $staircases, 'unit' => $pricing['sanding_railing'], 'cost' => $cost];
            $total += $cost;
        }

        // Spindles
        $spindleType = $_POST['sanding_spindle_type'] ?? '';
        $spindlesQty = max(0, intval($_POST['sanding_spindles_qty'] ?? 0));
        if ($spindleType && $spindlesQty > 0) {
            $spindleLabels = [
                'replace_metal' => ['Replace with Metal', 'sanding_spindles_replace_metal'],
                'paint_white'   => ['Paint White', 'sanding_spindles_paint_white'],
                'keep_metal'    => ['Keep Existing Metal', 'sanding_spindles_keep_metal'],
            ];
            if (isset($spindleLabels[$spindleType])) {
                $label = $spindleLabels[$spindleType][0];
                $priceKey = $spindleLabels[$spindleType][1];
                $cost = $spindlesQty * $pricing[$priceKey];
                $lineItems[] = ['desc' => $spindlesQty . ' Spindles — ' . $label, 'qty' => $spindlesQty, 'unit' => $pricing[$priceKey], 'cost' => $cost];
                $total += $cost;
            }
        }
    }

} elseif ($service_type === 'recapping') {
    $serviceLabel = 'Recapping';

    // Box steps
    $boxSteps = max(0, intval($_POST['recap_box_steps'] ?? 0));
    if ($boxSteps > 0) {
        $cost = $boxSteps * $pricing['recap_box_step'];
        $lineItems[] = ['desc' => $boxSteps . ' Box Step' . ($boxSteps > 1 ? 's' : ''), 'qty' => $boxSteps, 'unit' => $pricing['recap_box_step'], 'cost' => $cost];
        $total += $cost;
    }

    // Open steps
    $openSteps = max(0, intval($_POST['recap_open_steps'] ?? 0));
    if ($openSteps > 0) {
        $cost = $openSteps * $pricing['recap_open_step'];
        $lineItems[] = ['desc' => $openSteps . ' Open Step' . ($openSteps > 1 ? 's' : '') . ' (left/right)', 'qty' => $openSteps, 'unit' => $pricing['recap_open_step'], 'cost' => $cost];
        $total += $cost;
    }

    // Spindles
    $spindleType = $_POST['recap_spindle_type'] ?? '';
    $spindlesQty = max(0, intval($_POST['recap_spindles_qty'] ?? 0));
    if ($spindleType && $spindleType !== 'none' && $spindlesQty > 0) {
        $spindleLabels = [
            'new_metal'    => ['New Plain Metal', 'recap_spindles_new_metal'],
            'reuse_wood'   => ['Reuse Wood, Paint White', 'recap_spindles_reuse_wood'],
            'reuse_metal'  => ['Reuse Metal', 'recap_spindles_reuse_metal'],
        ];
        if (isset($spindleLabels[$spindleType])) {
            $label = $spindleLabels[$spindleType][0];
            $priceKey = $spindleLabels[$spindleType][1];
            $cost = $spindlesQty * $pricing[$priceKey];
            $lineItems[] = ['desc' => $spindlesQty . ' Spindles — ' . $label, 'qty' => $spindlesQty, 'unit' => $pricing[$priceKey], 'cost' => $cost];
            $total += $cost;
        }
    }

    // New railing
    $newRailing = ($_POST['recap_new_railing'] ?? 'no') === 'yes';
    if ($newRailing) {
        $lf = max(0, intval($_POST['recap_railing_lf'] ?? 0));
        if ($lf > 0) {
            $cost = $lf * $pricing['recap_railing_per_lf'];
            $lineItems[] = ['desc' => 'New Railing — ' . $lf . ' linear feet', 'qty' => $lf, 'unit' => $pricing['recap_railing_per_lf'], 'cost' => $cost];
            $total += $cost;
        }
    }

    // Staining
    $staining = ($_POST['recap_staining'] ?? 'no') === 'yes';
    if ($staining) {
        $staircases = max(1, intval($_POST['recap_staining_staircases'] ?? 1));
        $cost = $staircases * $pricing['recap_staining_per_staircase'];
        $lineItems[] = ['desc' => 'Staining — ' . $staircases . ' staircase' . ($staircases > 1 ? 's' : ''), 'qty' => $staircases, 'unit' => $pricing['recap_staining_per_staircase'], 'cost' => $cost];
        $total += $cost;
    }

    // Posts
    $postsType = $_POST['recap_posts_type'] ?? '';
    $postsQty = max(0, intval($_POST['recap_posts_qty'] ?? 0));
    if ($postsType && $postsQty > 0) {
        $postLabels = [
            'existing' => ['Existing to Stain', 'recap_posts_existing'],
            'new'      => ['New Posts', 'recap_posts_new'],
        ];
        if (isset($postLabels[$postsType])) {
            $label = $postLabels[$postsType][0];
            $priceKey = $postLabels[$postsType][1];
            $cost = $postsQty * $pricing[$priceKey];
            $lineItems[] = ['desc' => $postsQty . ' Posts — ' . $label, 'qty' => $postsQty, 'unit' => $pricing[$priceKey], 'cost' => $cost];
            $total += $cost;
        }
    }

} else {
    // Flooring
    $serviceLabel = 'Flooring';
    $totalSqft = max(0, intval($_POST['floor_total_sqft'] ?? 0));
    $calculatedLf = (int)ceil($totalSqft / 3);

    // 1) Demolition
    $demoType = $_POST['floor_demo_type'] ?? 'none';
    if ($demoType !== 'none' && $demoType !== '' && $totalSqft > 0) {
        $demoKeys = [
            'carpet'   => ['Carpet Demolition', 'floor_demo_carpet'],
            'hardwood' => ['Hardwood Demolition', 'floor_demo_hardwood'],
            'tiles'    => ['Tile Demolition', 'floor_demo_tiles'],
        ];
        if (isset($demoKeys[$demoType])) {
            $label = $demoKeys[$demoType][0];
            $priceKey = $demoKeys[$demoType][1];
            $cost = $totalSqft * $pricing[$priceKey];
            $lineItems[] = ['desc' => $label . ' — ' . number_format($totalSqft) . ' sqft', 'qty' => $totalSqft, 'unit' => $pricing[$priceKey], 'cost' => $cost];
            $total += $cost;
        }
    }

    // 2) Materials (if include_material = yes)
    $includeMaterial = ($_POST['floor_include_material'] ?? 'no') === 'yes';
    $materialLabels = [
        'hardwood'   => ['Hardwood', 'floor_material_hardwood'],
        'engineered' => ['Engineered Hardwood', 'floor_material_engineered'],
        'vinyl'      => ['Vinyl', 'floor_material_vinyl'],
        'laminate'   => ['Laminate', 'floor_material_laminate'],
    ];

    // Track selected materials and their sqft for labour calculation
    $selectedMaterials = [];

    if ($includeMaterial) {
        foreach ($materialLabels as $mat => $info) {
            $selected = intval($_POST['floor_mat_' . $mat] ?? 0);
            $matSqft = max(0, intval($_POST['floor_mat_' . $mat . '_sqft'] ?? 0));
            if ($selected && $matSqft > 0) {
                $label = $info[0] . ' Material';
                $priceKey = $info[1];
                $cost = $matSqft * $pricing[$priceKey];
                $lineItems[] = ['desc' => $label . ' — ' . number_format($matSqft) . ' sqft', 'qty' => $matSqft, 'unit' => $pricing[$priceKey], 'cost' => $cost];
                $total += $cost;
                $selectedMaterials[$mat] = $matSqft;
            }
        }
    }

    // If no materials selected with sqft but we still need labour, use totalSqft
    // Labour is always calculated. If materials are selected, use per-material sqft.
    // If not, we need at least the total sqft for a labour estimate.

    // 3) Labour — per material type
    $totalLabour = 0;
    $engMethod = $_POST['floor_eng_method'] ?? 'glue_nail';

    if (!empty($selectedMaterials)) {
        // Labour based on selected materials
        foreach ($selectedMaterials as $mat => $matSqft) {
            $labourRate = 0;
            $labourLabel = '';
            if ($mat === 'vinyl' || $mat === 'laminate') {
                $labourRate = $pricing['floor_labour_vinyl_laminate'];
                $labourLabel = ucfirst($mat) . ' Installation Labour';
            } elseif ($mat === 'engineered') {
                if ($engMethod === 'nails_only') {
                    $labourRate = $pricing['floor_labour_eng_nails'];
                    $labourLabel = 'Eng. Hardwood Labour (Nails Only)';
                } else {
                    $labourRate = $pricing['floor_labour_eng_glue_nail'];
                    $labourLabel = 'Eng. Hardwood Labour (Glue & Nail)';
                }
            } elseif ($mat === 'hardwood') {
                $labourRate = $pricing['floor_labour_hardwood'];
                $labourLabel = 'Hardwood Installation Labour';
            }

            if ($labourRate > 0 && $matSqft > 0) {
                $cost = $matSqft * $labourRate;
                $totalLabour += $cost;
                $lineItems[] = ['desc' => $labourLabel . ' — ' . number_format($matSqft) . ' sqft', 'qty' => $matSqft, 'unit' => $labourRate, 'cost' => $cost];
            }
        }
    } else {
        // No specific materials selected — use total sqft with generic labour
        // Default to vinyl/laminate rate as base
        if ($totalSqft > 0) {
            $labourRate = $pricing['floor_labour_vinyl_laminate'];
            $cost = $totalSqft * $labourRate;
            $totalLabour = $cost;
            $lineItems[] = ['desc' => 'Installation Labour — ' . number_format($totalSqft) . ' sqft', 'qty' => $totalSqft, 'unit' => $labourRate, 'cost' => $cost];
        }
    }

    // Apply minimum labour
    $minLabour = $pricing['floor_labour_minimum'];
    if ($totalLabour > 0 && $totalLabour < $minLabour) {
        // Replace labour line items total with minimum
        $labourDiff = $minLabour - $totalLabour;
        $lineItems[] = ['desc' => 'Minimum Labour Adjustment', 'qty' => 1, 'unit' => $labourDiff, 'cost' => $labourDiff];
        $totalLabour = $minLabour;
    }
    $total += $totalLabour;

    // 4) Baseboard
    if (($_POST['floor_baseboard'] ?? 'no') === 'yes' && $calculatedLf > 0) {
        $cost = $calculatedLf * $pricing['floor_baseboard_per_lf'];
        $lineItems[] = ['desc' => 'Baseboard — ' . number_format($calculatedLf) . ' LF', 'qty' => $calculatedLf, 'unit' => $pricing['floor_baseboard_per_lf'], 'cost' => $cost];
        $total += $cost;
    }

    // 5) Shoe Molding
    if (($_POST['floor_shoe_molding'] ?? 'no') === 'yes' && $calculatedLf > 0) {
        $cost = $calculatedLf * $pricing['floor_shoe_molding_per_lf'];
        $lineItems[] = ['desc' => 'Shoe Molding — ' . number_format($calculatedLf) . ' LF', 'qty' => $calculatedLf, 'unit' => $pricing['floor_shoe_molding_per_lf'], 'cost' => $cost];
        $total += $cost;
    }
}

$formattedTotal = '$' . number_format($total, 0);

// Build emails
try {
    // ---- Email 1: To Customer ----
    $mail = get_masterlay_mailer();
    $mail->addAddress($email, $name);
    $mail->isHTML(true);
    $emailCategory = ($service_type === 'flooring') ? 'Flooring' : 'Stair';
    $mail->Subject = 'Your ' . $emailCategory . ' Renovation Quote — Masterlay Renovations';

    $itemsHtml = '';
    foreach ($lineItems as $item) {
        $itemsHtml .= "<li style='padding: 6px 0; color: #333; font-size: 14px;'>{$item['desc']}</li>";
    }

    $mail->Body = "
    <div style='font-family: Arial, Helvetica, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f9f9f9;'>
        <div style='background-color: #0A0A0A; padding: 30px 40px; text-align: center;'>
            <h1 style='color: #FAA416; margin: 0; font-size: 24px;'>Your " . htmlspecialchars($emailCategory) . " Renovation Quote</h1>
            <p style='color: #ffffff99; margin: 8px 0 0; font-size: 14px;'>" . htmlspecialchars($serviceLabel) . "</p>
        </div>
        <div style='background-color: #ffffff; padding: 30px 40px;'>
            <p style='color: #333; font-size: 14px; margin: 0 0 20px;'>Hi " . htmlspecialchars($name) . ",</p>
            <p style='color: #333; font-size: 14px; margin: 0 0 20px;'>Thank you for using our instant quote tool. Here's a summary of your selections:</p>

            <h3 style='color: #0A0A0A; font-size: 16px; margin: 0 0 12px; border-bottom: 2px solid #FAA416; padding-bottom: 8px;'>" . htmlspecialchars($serviceLabel) . "</h3>
            <ul style='list-style: none; padding: 0; margin: 0 0 24px;'>{$itemsHtml}</ul>

            <div style='background-color: #0A0A0A; border-radius: 8px; padding: 20px; text-align: center; margin: 0 0 24px;'>
                <p style='color: #ffffff99; font-size: 12px; margin: 0 0 4px; text-transform: uppercase; letter-spacing: 1px;'>Estimated Total</p>
                <p style='color: #FAA416; font-size: 32px; font-weight: bold; margin: 0;'>{$formattedTotal}</p>
            </div>

            <p style='color: #666; font-size: 13px; font-style: italic; margin: 0 0 20px;'>This is an automated estimate. Final pricing will be confirmed after an on-site assessment.</p>

            <div style='text-align: center; margin: 24px 0 0;'>
                <a href='tel:" . PHONE . "' style='display: inline-block; background-color: #FAA416; color: #0A0A0A; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px;'>Call Us: " . PHONE_DISPLAY . "</a>
            </div>
        </div>
        <div style='background-color: #0A0A0A; padding: 20px 40px; text-align: center;'>
            <p style='color: #ffffff60; margin: 0; font-size: 12px;'>&copy; " . date('Y') . " " . SITE_NAME . " &bull; " . ADDRESS . "</p>
        </div>
    </div>";

    $mail->AltBody = "Your {$emailCategory} Renovation Quote\n\nService: {$serviceLabel}\n\n";
    foreach ($lineItems as $item) {
        $mail->AltBody .= "- {$item['desc']}\n";
    }
    $mail->AltBody .= "\nEstimated Total: {$formattedTotal}\n\nThis is an automated estimate. Final pricing confirmed after on-site assessment.\n\nCall us: " . PHONE_DISPLAY;

    $mail->send();

    // ---- Email 2: To Business ----
    $mail2 = get_masterlay_mailer();
    $mail2->addAddress(EMAIL, SITE_NAME);
    $mail2->addReplyTo($email, $name);
    $mail2->isHTML(true);
    $mail2->Subject = 'New Quote Request from ' . $name;

    $itemsDetailHtml = '';
    foreach ($lineItems as $item) {
        $unitFormatted = '$' . number_format($item['unit'], 2);
        $costFormatted = '$' . number_format($item['cost'], 2);
        $itemsDetailHtml .= "
            <tr>
                <td style='padding: 8px 12px; border-bottom: 1px solid #eee; color: #333; font-size: 13px;'>{$item['desc']}</td>
                <td style='padding: 8px 12px; border-bottom: 1px solid #eee; color: #666; font-size: 13px; text-align: center;'>{$item['qty']}</td>
                <td style='padding: 8px 12px; border-bottom: 1px solid #eee; color: #666; font-size: 13px; text-align: right;'>{$unitFormatted}</td>
                <td style='padding: 8px 12px; border-bottom: 1px solid #eee; color: #333; font-size: 13px; text-align: right; font-weight: 600;'>{$costFormatted}</td>
            </tr>";
    }

    $addressHtml = $address ? htmlspecialchars($address) : '<span style="color:#999;">Not provided</span>';
    $notesHtml = $notes ? nl2br(htmlspecialchars($notes)) : '<span style="color:#999;">None</span>';

    $mail2->Body = "
    <div style='font-family: Arial, Helvetica, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f9f9f9;'>
        <div style='background-color: #0A0A0A; padding: 30px 40px; text-align: center;'>
            <h1 style='color: #FAA416; margin: 0; font-size: 24px;'>New Quote Request</h1>
            <p style='color: #ffffff99; margin: 8px 0 0; font-size: 14px;'>from " . htmlspecialchars($name) . "</p>
        </div>
        <div style='background-color: #ffffff; padding: 30px 40px;'>
            <h3 style='color: #0A0A0A; font-size: 14px; margin: 0 0 12px;'>Customer Information</h3>
            <table style='width: 100%; border-collapse: collapse; margin: 0 0 24px;'>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px; width: 100px;'>Name</td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #333; font-size: 13px; font-weight: 600;'>" . htmlspecialchars($name) . "</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px;'>Email</td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #333; font-size: 13px;'><a href='mailto:{$email}' style='color: #FAA416;'>{$email}</a></td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px;'>Phone</td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #333; font-size: 13px;'><a href='tel:{$phone}' style='color: #FAA416;'>{$phone}</a></td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px;'>Address</td>
                    <td style='padding: 8px 0; border-bottom: 1px solid #eee; color: #333; font-size: 13px;'>{$addressHtml}</td>
                </tr>
                <tr>
                    <td style='padding: 8px 0; color: #666; font-size: 13px; vertical-align: top;'>Notes</td>
                    <td style='padding: 8px 0; color: #333; font-size: 13px;'>{$notesHtml}</td>
                </tr>
            </table>

            <h3 style='color: #0A0A0A; font-size: 14px; margin: 0 0 12px; border-bottom: 2px solid #FAA416; padding-bottom: 8px;'>" . htmlspecialchars($serviceLabel) . " — Line Items</h3>
            <table style='width: 100%; border-collapse: collapse; margin: 0 0 16px;'>
                <tr style='background-color: #f5f5f5;'>
                    <th style='padding: 8px 12px; text-align: left; font-size: 12px; color: #666;'>Item</th>
                    <th style='padding: 8px 12px; text-align: center; font-size: 12px; color: #666;'>Qty</th>
                    <th style='padding: 8px 12px; text-align: right; font-size: 12px; color: #666;'>Unit Price</th>
                    <th style='padding: 8px 12px; text-align: right; font-size: 12px; color: #666;'>Subtotal</th>
                </tr>
                {$itemsDetailHtml}
            </table>

            <div style='background-color: #0A0A0A; border-radius: 8px; padding: 16px 20px; text-align: right;'>
                <span style='color: #ffffff99; font-size: 14px; margin-right: 12px;'>Total:</span>
                <span style='color: #FAA416; font-size: 24px; font-weight: bold;'>{$formattedTotal}</span>
            </div>
        </div>
        <div style='background-color: #0A0A0A; padding: 20px 40px; text-align: center;'>
            <p style='color: #ffffff60; margin: 0; font-size: 12px;'>&copy; " . date('Y') . " " . SITE_NAME . " &bull; " . ADDRESS . "</p>
        </div>
    </div>";

    $mail2->AltBody = "New Quote Request from {$name}\n\nEmail: {$email}\nPhone: {$phone}\nAddress: " . ($address ?: 'N/A') . "\nNotes: " . ($notes ?: 'None') . "\n\nService: {$serviceLabel}\n\n";
    foreach ($lineItems as $item) {
        $mail2->AltBody .= "- {$item['desc']} | Qty: {$item['qty']} | \${$item['unit']}/ea | \${$item['cost']}\n";
    }
    $mail2->AltBody .= "\nTotal: {$formattedTotal}";

    $mail2->send();

    echo json_encode(['success' => true, 'message' => 'Your quote has been sent to your email!']);

} catch (\Exception $e) {
    error_log('Quote email error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your quote. Please try calling us or emailing us directly.']);
}
