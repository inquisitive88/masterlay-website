<?php
/**
 * Shop Admin — AJAX image upload. Receives ONE file, pushes it to R2,
 * returns JSON {success, url, key}. The product editor attaches the
 * returned keys to the product when the form is saved.
 */
require_once dirname(__DIR__) . '/includes/shop-admin-bootstrap.php';
require_once dirname(__DIR__, 2) . '/admin/includes/admin-r2.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST only']);
    exit;
}
if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Session expired — reload the page.']);
    exit;
}
if (empty($_FILES['image']) || ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No file received (PHP error code ' . ($_FILES['image']['error'] ?? 'none') . ')']);
    exit;
}

$r = r2_upload_file($_FILES['image'], 'images/shop');
if (!$r['success']) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $r['error']]);
    exit;
}
echo json_encode(['success' => true, 'url' => $r['url'], 'key' => $r['key']]);
