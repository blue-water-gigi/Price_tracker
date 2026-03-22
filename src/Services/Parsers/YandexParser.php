<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Exception;

class YandexParser implements ParserInterface
{
    public function parse(string $text): array
    {
        return [];
    }

    // public function fetchJson(string $url): array
    // {
    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTPHEADER => [
    //             'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
    //             'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
    //             'Referer: https://www.market.yandex.ru/',
    //             'Sec-Ch-Ua: "Chromium";v="122", "Not(A:Brand";v="24", "Google Chrome";v="122"',
    //             'Sec-Ch-Ua-Mobile: ?0',
    //             'Sec-Ch-Ua-Platform: "Windows"',
    //         ],
    //         CURLOPT_ENCODING => "",
    //     ]);

    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);

    //     if ($httpCode === 403 || $httpCode === 307) {
    //         error_log("Yandex blocking: HTTP Code - {$httpCode}. Stop sending requestes.");
    //         return [];
    //     }

    //     if (str_contains($response, "<html")) {
    //         echo $response;
    //         exit();
    //     }

    //     $data = json_decode($response, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);
    //     if (!$data) {
    //         throw new Exception("JSON error: " . json_last_error() . "." . json_last_error_msg());
    //     }

    //     return $data ?? [];
    // }
}
