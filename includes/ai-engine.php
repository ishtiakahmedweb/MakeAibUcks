<?php
/**
 * AI Engine - MakeAIBucks
 * Handles communication with AI providers (Gemini, Groq).
 */

class AIEngine {
    private static $gemini_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    private static $groq_url   = 'https://api.groq.com/openai/v1/chat/completions';

    /**
     * Generate content using available AI providers
     */
    public static function generate($systemPrompt, $userInputs) {
        $apiKey = getSetting('gemini_api_key');
        
        if (!$apiKey) {
            return self::fallbackToGroq($systemPrompt, $userInputs);
        }

        return self::callGemini($apiKey, $systemPrompt, $userInputs);
    }

    /**
     * Call Google Gemini API
     */
    private static function callGemini($apiKey, $systemPrompt, $userInputs) {
        $prompt = "SYSTEM INSTRUCTIONS: $systemPrompt\n\nUSER INPUTS:\n";
        foreach ($userInputs as $key => $value) {
            $prompt .= ucfirst($key) . ": $value\n";
        }

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2048,
            ]
        ];

        $ch = curl_init(self::$gemini_url . '?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode === 200 && isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return [
                'success' => true,
                'output'  => $result['candidates'][0]['content']['parts'][0]['text'],
                'provider' => 'gemini'
            ];
        }

        // Log error and fallback
        error_log("Gemini API Error: " . ($result['error']['message'] ?? 'Unknown error'));
        return self::fallbackToGroq($systemPrompt, $userInputs);
    }

    /**
     * Fallback to Groq API
     */
    private static function fallbackToGroq($systemPrompt, $userInputs) {
        $apiKey = getSetting('groq_api_key');
        
        if (!$apiKey) {
            return [
                'success' => false,
                'message' => 'AI services are currently unavailable. Please try again later.'
            ];
        }

        $prompt = "USER INPUTS:\n";
        foreach ($userInputs as $key => $value) {
            $prompt .= ucfirst($key) . ": $value\n";
        }

        $data = [
            'model' => 'mixtral-8x7b-32768',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2048
        ];

        $ch = curl_init(self::$groq_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['choices'][0]['message']['content'])) {
            return [
                'success' => true,
                'output'  => $result['choices'][0]['message']['content'],
                'provider' => 'groq'
            ];
        }

        return [
            'success' => false,
            'message' => 'Both AI providers failed. Please check backend settings.'
        ];
    }
}
