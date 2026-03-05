<?php
/**
 * Global Helper Functions
 */

function slugify($text) {
    if (empty($text)) return 'n-a';
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function truncate($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, strrpos(substr($text, 0, $length), ' ')) . '...';
}

function formatNumber($num) {
    if ($num >= 1000000) return round($num / 1000000, 1) . 'M';
    if ($num >= 1000) return round($num / 1000, 1) . 'k';
    return $num;
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    $units = [
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    ];
    foreach ($units as $unit => $label) {
        if ($time < $unit) continue;
        $count = floor($time / $unit);
        return $count . ' ' . $label . ($count > 1 ? 's' : '') . ' ago';
    }
    return 'just now';
}

function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function get($key, $default = null) {
    return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
}

function post($key, $default = null) {
    return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function jsonSuccess($data = [], $message = '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $data, 'message' => $message]);
    exit;
}

function jsonError($message = '', $code = 400) {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

function getClientIp() {
    return $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf() {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($token) || $token !== ($_SESSION['csrf_token'] ?? '')) {
        jsonError('Invalid CSRF token', 403);
    }
}

/**
 * Get all categories with tool counts
 */
function getCategories($withCount = false) {
    if ($withCount) {
        return db()->fetchAll("
            SELECT c.*, COUNT(t.id) as tool_count 
            FROM categories c 
            LEFT JOIN tools t ON c.slug = t.category_slug AND t.is_active = 1
            GROUP BY c.id 
            ORDER BY c.sort_order ASC
        ");
    }
    return db()->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");
}

/**
 * Check if sidebar item is active
 */
function isActive($slug, $current) {
    return $slug === $current 
        ? 'bg-primary/10 text-primary border-primary' 
        : 'text-slate-400 hover:text-white hover:bg-slate-800/50 border-transparent';
}

/**
 * Escape HTML
 */
function h($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Get site setting from database
 */
function getSetting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        $data = db()->fetchAll("SELECT setting_key, setting_value FROM settings");
        foreach ($data as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings[$key] ?? $default;
}
