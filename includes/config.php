<?php
/**
 * MakeAIBucks Configuration
 */

// Database Credentials (XAMPP Defaults)
define('DB_HOST', 'localhost');
define('DB_NAME', 'makeaibucks');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Settings
define('SITE_URL', 'http://localhost/makeaibucks');
define('SITE_NAME', 'MakeAIBucks');
define('ADMIN_EMAIL', 'admin@makeaibucks.com');
define('SECRET_KEY', 'mk-ai-bucks-secure-random-key-2024');

// Rate Limiting
define('RATE_LIMIT_HOUR', 10);
define('RATE_LIMIT_DAY', 30);

// Caching
define('CACHE_ENABLED', true);
define('CACHE_TTL', 3600); // 1 hour

// Environment
define('APP_ENV', 'development'); // 'development' or 'production'
define('APP_DEBUG', true);

// Security Settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Path Constants
define('INC_PATH', __DIR__ . '/');
define('ROOT_PATH', dirname(__DIR__) . '/');

// Load Foundation Files
require_once INC_PATH . 'db.php';
require_once INC_PATH . 'functions.php';
require_once INC_PATH . 'auth.php';
require_once INC_PATH . 'seo.php';
require_once INC_PATH . 'rate-limiter.php';
require_once INC_PATH . 'cache-engine.php';
require_once INC_PATH . 'ai-engine.php';
