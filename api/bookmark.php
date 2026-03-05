<?php
/**
 * API - Toggle Bookmark
 */
require_once '../includes/config.php';

if (!isPost()) {
    jsonError('Invalid request method');
}

$toolSlug = post('tool_slug');

if (!$toolSlug) {
    jsonError('Tool identifier missing');
}

$sessionId = session_id();
$db = db();

$existing = $db->fetchOne("SELECT id FROM bookmarks WHERE session_id = ? AND tool_slug = ?", [$sessionId, $toolSlug]);

try {
    if ($existing) {
        $db->query("DELETE FROM bookmarks WHERE id = ?", [$existing['id']]);
        jsonSuccess(['bookmarked' => false], 'Tool removed from bookmarks');
    } else {
        $db->insert('bookmarks', [
            'session_id' => $sessionId,
            'tool_slug'  => $toolSlug
        ]);
        jsonSuccess(['bookmarked' => true], 'Tool saved to your bookmarks');
    }
} catch (Exception $e) {
    jsonError('Error managing bookmarks. Please try again.');
}
