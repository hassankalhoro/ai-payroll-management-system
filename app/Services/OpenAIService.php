<?php

namespace App\Services;

use Exception;
use RuntimeException;

/**
 * Thin OpenAI client built on cURL (no Guzzle dependency in this project).
 * Handles chat completions, JSON-mode responses and vision (image) inputs.
 */
class OpenAIService
{
    protected $apiKey;
    protected $apiBase;
    protected $timeout;

    public function __construct()
    {
        $this->apiKey = config('ai.key');
        $this->apiBase = rtrim(config('ai.api_base', 'https://api.openai.com/v1'), '/');
        $this->timeout = config('ai.timeout', 60);

        if (empty($this->apiKey)) {
            throw new RuntimeException('OPENAI_API_KEY is not configured.');
        }
    }

    /**
     * Standard chat completion.
     *
     * @param array  $messages  [['role' => 'system'|'user'|'assistant', 'content' => string], ...]
     * @param array  $options   model, temperature, max_tokens, json (bool)
     * @return string            assistant message content
     */
    public function chat(array $messages, array $options = []): string
    {
        $payload = [
            'model' => $options['model'] ?? config('ai.model'),
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? config('ai.temperature'),
            'max_tokens' => $options['max_tokens'] ?? config('ai.max_tokens'),
        ];

        if (!empty($options['json'])) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        $data = $this->request('/chat/completions', $payload);

        return $data['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Chat completion that must return JSON. Decodes and returns an array.
     */
    public function chatJson(array $messages, array $options = []): array
    {
        $options['json'] = true;
        $raw = $this->chat($messages, $options);
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            // Best-effort: extract the first {...} block if the model added prose.
            if (preg_match('/\{.*\}/s', $raw, $m)) {
                $decoded = json_decode($m[0], true);
            }
        }

        return is_array($decoded) ? $decoded : ['raw' => $raw];
    }

    /**
     * Vision call: send an image (base64 data URL) plus a text prompt.
     *
     * @param string $dataUrl  e.g. "data:image/png;base64,...."
     * @param string $prompt   instruction for the model
     */
    public function vision(string $dataUrl, string $prompt, array $options = []): string
    {
        $messages = [
            [
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => $prompt],
                    ['type' => 'image_url', 'image_url' => ['url' => $dataUrl]],
                ],
            ],
        ];

        $payload = [
            'model' => $options['model'] ?? config('ai.vision_model'),
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? config('ai.max_tokens'),
            'temperature' => 0,
        ];

        if (!empty($options['json'])) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        $data = $this->request('/chat/completions', $payload);

        return $data['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Perform a POST request to the OpenAI API and return decoded JSON.
     * Retries a few times on HTTP 429 (free-tier rate limits) with backoff.
     */
    protected function request(string $path, array $payload): array
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ];

        $backoff = [3, 6];
        $attempt = 0;

        while (true) {
            $ch = curl_init($this->apiBase . $path);

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 15,
            ]);

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                throw new Exception('OpenAI request failed: ' . $error);
            }

            $decoded = json_decode($response, true);

            if ($status === 429 && $attempt < count($backoff)) {
                sleep($backoff[$attempt]);
                $attempt++;
                continue;
            }

            if ($status < 200 || $status >= 300) {
                $msg = $decoded['error']['message'] ?? ('HTTP ' . $status);
                throw new Exception('OpenAI API error: ' . $msg);
            }

            return is_array($decoded) ? $decoded : [];
        }
    }
}
