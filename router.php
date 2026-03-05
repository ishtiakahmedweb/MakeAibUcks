<?php
/**
 * Local Router for PHP built-in server
 * Mimics .htaccess rules for MakeAIBucks
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

if (file_exists($file)) {
    // If it's a directory, look for index.php
    if (is_dir($file)) {
        $index = rtrim($file, '/') . '/index.php';
        if (file_exists($index)) {
            require_once $index;
            return;
        }
    }
    // Serve static files as-is
    return false;
}

// Tool routing: /tool/slug -> /tool/index.php?slug=slug
if (preg_match('#^/tool/([^/]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    chdir(__DIR__ . '/tool');
    require_once 'index.php';
    return;
}

// Result routing: /result/slug -> /result/index.php?slug=slug
if (preg_match('#^/result/([^/]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    chdir(__DIR__ . '/result');
    require_once 'index.php';
    return;
}

// Category routing: /category/slug -> /category/index.php?slug=slug
if (preg_match('#^/category/([^/]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    chdir(__DIR__ . '/category');
    require_once 'index.php';
    return;
}

// About/Terms/Privacy routing: /about -> /about/index.php
foreach (['about', 'terms', 'privacy', 'contact', 'requests', 'categories'] as $page) {
    if (strpos($uri, "/$page") === 0) {
        chdir(__DIR__ . "/$page");
        require_once 'index.php';
        return;
    }
}

// Default to 404.php if not found
chdir(__DIR__);
if (file_exists('404.php')) {
    require_once '404.php';
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}
