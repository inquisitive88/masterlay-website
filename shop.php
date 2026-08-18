<?php
// /shop → the shop module's catalog page. This shim exists because the nginx
// vhost has no directory-index handling for subfolders; the extensionless
// rewrite maps /shop to this file with zero server-config changes.
require __DIR__ . '/shop/index.php';
