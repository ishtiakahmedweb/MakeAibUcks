<?php
/**
 * Dynamic Sitemap Generator - MakeAIBucks
 */
require_once '../includes/config.php';

header("Content-Type: application/xml; charset=utf-8");

$db = db();
$tools = $db->fetchAll("SELECT slug, updated_at FROM tools WHERE is_active = 1");
$categories = $db->fetchAll("SELECT slug FROM categories");
// Limit results to 1000 for sitemap performance
$results = $db->fetchAll("SELECT slug, created_at FROM results WHERE is_public = 1 ORDER BY created_at DESC LIMIT 1000");

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Static Pages
$static = ['', 'tools', 'categories', 'requests', 'about', 'privacy', 'terms'];
foreach ($static as $page) {
    echo '<url>';
    echo '<loc>' . url($page) . '</loc>';
    echo '<changefreq>daily</changefreq>';
    echo '<priority>' . ($page === '' ? '1.0' : '0.8') . '</priority>';
    echo '</url>';
}

// Categories
foreach ($categories as $cat) {
    echo '<url>';
    echo '<loc>' . url('tools?category=' . $cat['slug']) . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.7</priority>';
    echo '</url>';
}

// Tools
foreach ($tools as $tool) {
    echo '<url>';
    echo '<loc>' . url('tool/' . $tool['slug']) . '</loc>';
    echo '<lastmod>' . date('c', strtotime($tool['updated_at'])) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.9</priority>';
    echo '</url>';
}

// Results
foreach ($results as $res) {
    echo '<url>';
    echo '<loc>' . url('result/' . $res['slug']) . '</loc>';
    echo '<lastmod>' . date('c', strtotime($res['created_at'])) . '</lastmod>';
    echo '<changefreq>monthly</changefreq>';
    echo '<priority>0.5</priority>';
    echo '</url>';
}

echo '</urlset>';
