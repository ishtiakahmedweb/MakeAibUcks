<?php
/**
 * API - AI Content Generation
 */
require_once '../includes/config.php';

if (!isPost()) {
    jsonError('Invalid request method');
}

// 1. Verify CSRF
verifyCsrf();

$toolSlug = post('tool_slug');
if (!$toolSlug) {
    jsonError('Tool identifier missing');
}

$db = db();
$tool = $db->fetchOne("SELECT * FROM tools WHERE slug = ? AND is_active = 1", [$toolSlug]);

if (!$tool) {
    jsonError('Tool not found');
}

// 2. Validate Inputs
$fields = json_decode($tool['fields_json'], true) ?: [];
$userInputs = [];
foreach ($fields as $field) {
    $val = post($field['name']);
    if ($field['required'] && empty($val)) {
        jsonError("The field '{$field['label']}' is required");
    }
    $userInputs[$field['name']] = $val;
}

// 3. Rate Limiting
$ip = getClientIp();
$limiter = new RateLimiter($db);
if (!$limiter->check($ip, 'generate')) {
    jsonError('Daily/Hourly limit reached. Please try again later.');
}

// 4. Cache Check
$cache = new CacheEngine($db);
$cacheKey = $cache->generateKey($toolSlug, $userInputs);
$cachedResponse = $cache->get($cacheKey);

if ($cachedResponse) {
    // Log cached generation
    $db->insert('generations', [
        'tool_slug' => $toolSlug,
        'ip_address' => $ip,
        'api_type' => 'cache'
    ]);
    
    // Create a result record even for cache (to get a unique URL)
    $resultSlug = slugify($tool['name'] . '-' . bin2hex(random_bytes(4)));
    $db->insert('results', [
        'tool_id' => $tool['id'],
        'tool_slug' => $toolSlug,
        'slug' => $resultSlug,
        'inputs_json' => json_encode($userInputs),
        'output_text' => $cachedResponse,
        'page_title' => $tool['name'] . ' Generated Content',
        'ip_address' => $ip,
        'delete_token' => bin2hex(random_bytes(16))
    ]);

    jsonSuccess([
        'output' => $cachedResponse,
        'result_slug' => $resultSlug
    ], 'Loaded from cache');
}

// 5. Call AI Engine
$aiResult = AIEngine::generate($tool['system_prompt'], $userInputs);

if (!$aiResult['success']) {
    jsonError($aiResult['message']);
}

$outputText = $aiResult['output'];

// 6. Save to Cache
$cache->set($cacheKey, $toolSlug, $outputText);

// 7. Save to Results
$resultSlug = slugify($tool['name'] . '-' . bin2hex(random_bytes(4)));
$db->insert('results', [
    'tool_id' => $tool['id'],
    'tool_slug' => $toolSlug,
    'slug' => $resultSlug,
    'inputs_json' => json_encode($userInputs),
    'output_text' => $outputText,
    'page_title' => $tool['name'] . ' Generated Output',
    'ip_address' => $ip,
    'delete_token' => bin2hex(random_bytes(16))
]);

// 8. Log Generation
$db->insert('generations', [
    'tool_slug' => $toolSlug,
    'ip_address' => $ip,
    'api_type' => $aiResult['provider']
]);

// 9. Update Tool Use Count
$db->query("UPDATE tools SET uses_count = uses_count + 1 WHERE id = ?", [$tool['id']]);

jsonSuccess([
    'output' => $outputText,
    'result_slug' => $resultSlug
], 'Content generated successfully');
