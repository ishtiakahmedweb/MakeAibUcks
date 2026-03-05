<?php
/**
 * Admin Router - MakeAIBucks
 */
require_once '../includes/config.php';

if (isAdminLoggedIn()) {
    redirect(url('admin/dashboard.php'));
} else {
    redirect(url('admin/login.php'));
}
