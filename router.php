<?php
/* ============================================================
 * Local dev router — emulates the .htaccess clean-URL rewrite
 * for PHP's built-in server, which ignores .htaccess.
 *
 *   php -S 127.0.0.1:8003 router.php
 *
 * Production uses Apache + .htaccess; this file is dev-only.
 * ============================================================ */

$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$root = __DIR__;
$full = realpath($root . $path);

// Never serve server-side includes or runtime state directly.
if (preg_match('#^/(inc|data)(/|$)#', $path) || preg_match('#/\.#', $path)) {
    http_response_code(404);
    echo 'Not found';
    return true;
}

// Existing real file (asset, .php, etc.) → let the server handle it.
if ($path !== '/' && $full && is_file($full) && strpos($full, $root) === 0) {
    return false;
}

// Clean URL → matching .php file (e.g. /privacy → privacy.php).
$clean = trim($path, '/');
if ($clean !== '' && is_file($root . '/' . $clean . '.php')) {
    require $root . '/' . $clean . '.php';
    return true;
}

// Root or anything else → homepage.
require $root . '/index.php';
return true;
