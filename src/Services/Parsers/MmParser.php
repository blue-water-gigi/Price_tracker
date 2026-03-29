<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Exception;

class MmParser implements ParserInterface
{

    public function parse(string $url): array
    {
        return [];
    }

    // public function warmupSession(string $url, string $cookieFile): void
    // {
    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_COOKIEJAR => $cookieFile,   // сохранить куки
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_HTTPHEADER => [
    //             'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0',
    //         ],
    //     ]);
    //     curl_exec($ch);
    //     curl_close($ch);
    // }

    // public function fetchJson(string $url): mixed
    // {
    //     $cookieFile = __DIR__ . "/../../../cookies/mm/mm_cookies.txt";

    //     $dir = dirname($cookieFile);

    //     if (!is_dir($dir)) {
    //         mkdir($dir, 0777, true);
    //     }

    //     if (!file_exists($cookieFile) || (time() - filemtime($cookieFile) > 3600)) {
    //         $this->warmUpSession("https://www.megamarket.ru/", $cookieFile);
    //         sleep(rand(5, 10));
    //     }

    //     $body = json_encode([
    //         "goodsId" => "600023157542_36941",
    //         "context" => ["merchantId" => "36941"],
    //         "auth" => [
    //             "locationId" => "50",
    //             "appPlatform" => "WEB",
    //             "appVersion" => 1773291565,
    //             "os" => "UNKNOWN_OS"
    //         ]
    //     ]);

    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_TIMEOUT => 15,
    //         CURLOPT_POST => true,
    //         CURLOPT_POSTFIELDS => $body,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_HTTPHEADER => [
    //             'Content-Type: application/json',
    //             'Accept: application/json',
    //             'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0',
    //             'Origin: https://megamarket.ru',
    //             'Referer: https://megamarket.ru/',
    //             'x-requested-with: XMLHttpRequest',
    //         ],
    //         CURLOPT_COOKIEFILE => $cookieFile
    //     ]);

    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);

    //     if ($httpCode !== 200) {
    //         throw new Exception("failed: {$httpCode}");
    //     }
    //     return $response;
    // }
}
