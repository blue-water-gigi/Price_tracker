<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Exception;

class WbParser implements ParserInterface
{
    public function parse(string $text): array
    {
        return [];
    }

    private function fetchJson(string $url): array
    {

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept: application/json',
                'Referer: https://www.wildberries.ru/',
                'Origin: https://www.wildberries.ru',
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($response === false || $httpCode !== 200) {
            throw new Exception("cURL error: HTTP Code - {$httpCode}.");
        }

        $data = json_decode($response, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);

        if (!$data) {
            throw new Exception("JSON error: " . json_last_error() . "." . json_last_error_msg());
        }
        return $data ?? [];
    }

    private function extractArticle(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        if (preg_match('/\d+/', $path, $matches)) {
            return $matches[0];
        }
        return '';
    }

    public function getImgUrl(string $article): string
    {
        //https://basket-25.wbbasket.ru/vol4444/part444470/444470528/images/big/1.webp

        $curr_art = $this->extractArticle($article);

        $vol = substr($article, 0, 4);
        $part = substr($article, 0, 6);

        $basket = match (true) {
           //todo СДЕЛАТЬ ОТДЕЛЬНЫЙ МЕТОД который ищет бакет через цикл с помощью curl;
        };

        return "https://basket-{$basket}.wbbasket.ru/vol{$vol}/part{$part}/{$article}/images/big/1.webp";
    }
}
