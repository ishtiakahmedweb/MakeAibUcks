<?php
/**
 * API - Newsletter Subscription
 */
require_once '../includes/config.php';

if (!isPost()) {
    jsonError('Invalid request method');
}

$email = post('email');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Please provide a valid email address');
}

$db = db();
$existing = $db->fetchOne("SELECT id FROM subscribers WHERE email = ?", [$email]);

if ($existing) {
    jsonError('You are already subscribed to our list!');
}

try {
    $db->insert('subscribers', [
        'email' => $email,
        'ip_address' => getClientIp()
    ]);
    
    jsonSuccess([], 'Successfully subscribed');
} catch (Exception $e) {
    jsonError('Database error occurred. Please try again later.');
}
