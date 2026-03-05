<?php
/**
 * Individual Category Page - MakeAIBucks
 * This redirects to tools directory with category filter for consistency.
 */
require_once '../includes/config.php';

$slug = get('slug');

if (!$slug) {
    redirect(url('categories'));
}

redirect(url("tools?category=$slug"));
