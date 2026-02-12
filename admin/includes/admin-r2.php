<?php
/**
 * Cloudflare R2 Upload Helper for CMS Gallery
 * Uses the same Cloudflare account as the estimator portal but different bucket.
 * Bucket: masterlay-website (for website gallery images)
 */

// Only load once
if (defined('R2_LOADED')) return;
define('R2_LOADED', true);

// Autoloader
$autoloadPath = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    // Graceful fallback - R2 upload won't work but won't crash the page
    function r2_upload_file($file) {
        return ['success' => false, 'url' => null, 'key' => null, 'error' => 'AWS SDK not installed. Run: composer require aws/aws-sdk-php'];
    }
    function r2_delete_file($key) {
        return ['success' => false, 'error' => 'AWS SDK not installed.'];
    }
    return;
}

require_once $autoloadPath;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// R2 Configuration — same Cloudflare account, different bucket
$r2_cms_config = [
    'endpoint'   => 'https://5aadeeccb7f26d4ff280cb02d66c1fac.r2.cloudflarestorage.com',
    'access_key' => '231a87e4fae99cfd8fab25ad6c9845f3',
    'secret_key' => '1804c46892beea5d8241e40a3f78358f8dd0490d9c6036a9df262e62e3a4e899',
    'region'     => 'auto',
    'bucket'     => 'masterlay-website',
    'public_url' => 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev',
];

// Initialize S3 client
$r2CmsClient = new S3Client([
    'version'  => 'latest',
    'region'   => $r2_cms_config['region'],
    'endpoint' => $r2_cms_config['endpoint'],
    'credentials' => [
        'key'    => $r2_cms_config['access_key'],
        'secret' => $r2_cms_config['secret_key'],
    ],
    'bucket_endpoint'          => false,
    'use_path_style_endpoint'  => true,
]);

/**
 * Upload a file to R2 from a PHP $_FILES upload
 * @param array $file $_FILES['fieldname'] array
 * @return array ['success' => bool, 'url' => string|null, 'key' => string|null, 'error' => string|null]
 */
function r2_upload_file($file) {
    global $r2CmsClient, $r2_cms_config;

    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 10 * 1024 * 1024; // 10MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'url' => null, 'key' => null, 'error' => 'Upload error code: ' . $file['error']];
    }

    if ($file['size'] > $maxSize) {
        return ['success' => false, 'url' => null, 'key' => null, 'error' => 'File too large. Maximum 10MB.'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'url' => null, 'key' => null, 'error' => 'Invalid file type. Only JPG, PNG, WebP allowed.'];
    }

    // Generate unique key
    $ext = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => 'jpg',
    };
    $timestamp = time();
    $random = bin2hex(random_bytes(8));
    $key = "images/gallery/{$timestamp}-{$random}.{$ext}";

    try {
        $r2CmsClient->putObject([
            'Bucket'      => $r2_cms_config['bucket'],
            'Key'         => $key,
            'SourceFile'  => $file['tmp_name'],
            'ContentType' => $mimeType,
        ]);

        $url = rtrim($r2_cms_config['public_url'], '/') . '/' . $key;
        return ['success' => true, 'url' => $url, 'key' => $key, 'error' => null];
    } catch (AwsException $e) {
        return ['success' => false, 'url' => null, 'key' => null, 'error' => $e->getMessage()];
    }
}

/**
 * Delete a file from R2 by its key
 * @param string $key The R2 object key
 * @return array ['success' => bool, 'error' => string|null]
 */
function r2_delete_file($key) {
    global $r2CmsClient, $r2_cms_config;

    if (empty($key)) {
        return ['success' => true, 'error' => null]; // Nothing to delete
    }

    try {
        $r2CmsClient->deleteObject([
            'Bucket' => $r2_cms_config['bucket'],
            'Key'    => $key,
        ]);
        return ['success' => true, 'error' => null];
    } catch (AwsException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
