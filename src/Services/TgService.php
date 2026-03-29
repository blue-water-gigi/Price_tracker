<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

readonly class TgService
{
    private string $apiUrl;

    public function __construct(private string $token)
    {
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    private function sendRequest(string $method, array $params = []): array
    {
        $ch = curl_init("{$this->apiUrl}/{$method}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Content-Type: application/json',
                'Accept: application/json',
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            throw new Exception("cURL error: HTTP Code - {$httpCode}.");
        }

        $json = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if (!$json) {
            throw new Exception("JSON error: " . json_last_error() . "." . json_last_error_msg());
        }

        return $json ?? [];
    }

    public function sendMessage(int $chat_id, string $text, string $parseMode = 'HTML'): void
    {
        $this->sendRequest('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => $parseMode
        ]);
    }
}
