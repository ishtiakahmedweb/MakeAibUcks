<?php
/**
 * Admin Authentication Helpers
 */

function isAdminLoggedIn() {
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        return false;
    }

    // Session timeout (default: 2 hours)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
        adminLogout();
        return false;
    }

    $_SESSION['last_activity'] = time();
    return true;
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/');
        exit;
    }
}

function adminLogin($password) {
    $adminPasswordHash = db()->getSetting('admin_password');
    if (password_verify($password, $adminPasswordHash)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['last_activity'] = time();
        return true;
    }
    return false;
}

function adminLogout() {
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['last_activity']);
    session_destroy();
    header('Location: ' . SITE_URL . '/admin/');
    exit;
}
