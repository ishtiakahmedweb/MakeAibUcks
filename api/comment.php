<?php
/**
 * API - Post Comment
 */
require_once '../includes/config.php';

if (!isPost()) {
    jsonError('Invalid request method');
}

$resultId = (int)post('result_id');
$name = post('name');
$content = post('content');

if (!$resultId || !$name || !$content) {
    jsonError('All fields are required');
}

$db = db();

try {
    $db->insert('comments', [
        'result_id' => $resultId,
        'name' => $name,
        'content' => $content,
        'ip_address' => getClientIp(),
        'is_approved' => 1 // Auto-approve for now as per "Minimal Admin" start, can be changed later
    ]);
    
    jsonSuccess([], 'Comment posted successfully');
} catch (Exception $e) {
    jsonError('Error saving comment. Please try again.');
}
