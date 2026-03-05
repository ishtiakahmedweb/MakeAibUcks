<?php
/**
 * Cache Engine Class (MySQL-based)
 */

class CacheEngine {
    public function get($toolSlug, $inputs) {
        if (!CACHE_ENABLED) return null;

        $key = $this->makeKey($toolSlug, $inputs);
        $result = db()->fetchOne(
            "SELECT response_text FROM api_cache WHERE cache_key = ? AND created_at > DATE_SUB(NOW(), INTERVAL " . CACHE_TTL . " SECOND)",
            [$key]
        );

        return $result ? $result['response_text'] : null;
    }

    public function set($toolSlug, $inputs, $response) {
        if (!CACHE_ENABLED) return;

        $key = $this->makeKey($toolSlug, $inputs);
        db()->query(
            "INSERT INTO api_cache (cache_key, tool_slug, response_text) VALUES (?, ?, ?) 
             ON DUPLICATE KEY UPDATE response_text = VALUES(response_text), created_at = CURRENT_TIMESTAMP",
            [$key, $toolSlug, $response]
        );
    }

    public function clear($toolSlug = null) {
        if ($toolSlug) {
            db()->query("DELETE FROM api_cache WHERE tool_slug = ?", [$toolSlug]);
        } else {
            db()->query("TRUNCATE TABLE api_cache");
        }
    }

    private function makeKey($toolSlug, $inputs) {
        ksort($inputs); // Sort to ensure same inputs give same key
        return hash('sha256', $toolSlug . json_encode($inputs));
    }
}
