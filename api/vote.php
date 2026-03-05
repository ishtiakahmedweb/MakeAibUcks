<?php
/**
 * API - Vote for Request
 */
require_once '../includes/config.php';

if (!isPost()) {
    jsonError('Invalid request method');
}

$id = (int)post('id');

if (!$id) {
    jsonError('Invalid request ID');
}

$db = db();
$ip = getClientIp();

// Simple IP-based vote check (can be improved with a votes table, but keeping it light)
// For now, we'll just increment up to once per session to keep it simple and Frictionless.
if (isset($_SESSION['voted_for_' . $id])) {
    jsonError('You have already voted for this tool.');
}

try {
    $db->query("UPDATE tool_requests SET votes = votes + 1 WHERE id = ?", [$id]);
    $_SESSION['voted_for_' . $id] = true;
    
    jsonSuccess([], 'Vote recorded');
} catch (Exception $e) {
    jsonError('Error recording vote.');
}
