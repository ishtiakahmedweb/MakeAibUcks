<?php
/**
 * API - Submit Tool Request
 */
require_once '../includes/config.php';

if (!isPost()) {
    jsonError('Invalid request method');
}

$name = post('name');
$category = post('category');
$description = post('description');
$email = post('email');

if (!$name || !$description) {
    jsonError('Name and Description are required');
}

$db = db();
$ip = getClientIp();

// Basic rate limit for requests
$lastRequest = $db->fetchOne("SELECT id FROM tool_requests WHERE ip_address = ? AND created_at > NOW() - INTERVAL 1 HOUR", [$ip]);
if ($lastRequest) {
    jsonError('You can only submit one request per hour.');
}

try {
    $db->insert('tool_requests', [
        'name' => $name,
        'category' => $category,
        'description' => $description,
        'email' => $email,
        'ip_address' => $ip,
        'status' => 'pending',
        'votes' => 1
    ]);
    
    jsonSuccess([], 'Request submitted successfully');
} catch (Exception $e) {
    jsonError('Error saving request. Please try again.');
}
