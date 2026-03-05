<?php
/**
 * Rate Limiter Class
 */

class RateLimiter {
    public function check($ip, $actionType = 'generate') {
        $db = db();
        
        // Cleanup old records randomly (1 in 100)
        if (mt_rand(1, 100) === 1) {
            $this->cleanup();
        }

        // Count per hour
        $hourCount = $db->fetchOne(
            "SELECT COUNT(*) as cnt FROM rate_limits WHERE ip_address = ? AND action_type = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)",
            [$ip, $actionType]
        )['cnt'];

        if ($hourCount >= RATE_LIMIT_HOUR) {
            return ['allowed' => false, 'message' => "Hourly limit reached. Please try again soon."];
        }

        // Count per day
        $dayCount = $db->fetchOne(
            "SELECT COUNT(*) as cnt FROM rate_limits WHERE ip_address = ? AND action_type = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)",
            [$ip, $actionType]
        )['cnt'];

        if ($dayCount >= RATE_LIMIT_DAY) {
            return ['allowed' => false, 'message' => "Daily limit reached. Come back tomorrow!"];
        }

        // Log action
        $db->insert('rate_limits', [
            'ip_address' => $ip,
            'action_type' => $actionType
        ]);

        return ['allowed' => true, 'message' => ''];
    }

    private function cleanup() {
        db()->query("DELETE FROM rate_limits WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    }
}
