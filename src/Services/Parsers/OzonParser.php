<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Exception;

//todo реализовать не получилось, т.к. ozon блочит запросы, как бота. даёт 307, мб позже сделаю.
class OzonParser implements ParserInterface
{
    public function parse(string $text): array
    {
        return [];
    }

    public function extractSlug(string $url): string
    {
        //https://www.ozon.ru/product/weissgauff-holodilnik-dvuhkamernyy-shirinoy-60-sm-wrk-2010-dw-inverter-total-nofrost-invertor-polnyy-3574983311/?at=DqtDmnNk2FWpwvYLH2ZRZkfX64qJ7igoDWkwCL1RDX2
        //endpoint : https://www.ozon.ru/api/composer-api.bx/page/json/v2?url=/product/weissgauff-holodilnik-dvuhkamernyy-shirinoy-60-sm-wrk-2010-dw-inverter-total-nofrost-invertor-polnyy-3574983311
        $path = parse_url($url, PHP_URL_PATH);
        return $path ? preg_replace("/product\//", "", trim($path, "/")) : "";
    }

    // public function warmupSession(string $url, string $cookieFile): void
    // {
    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_COOKIEJAR => $cookieFile,
    //         CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
    //         CURLOPT_TIMEOUT => 10,
    //     ]);
    //     curl_exec($ch);
    //     curl_close($ch);
    // }

    // public function fetchJson(string $url): array
    // {
    //     $cookieFile = __DIR__ . "/../../../cookies/ozon/ozon_cookies.txt";

    //     $dir = dirname($cookieFile);

    //     if (!is_dir($dir)) {
    //         mkdir($dir, 0777, true);
    //     }

    //     if (!file_exists($cookieFile) || (time() - filemtime($cookieFile) > 3600)) {
    //         $this->warmUpSession("https://www.ozon.ru/", $cookieFile);
    //         sleep(rand(5, 10));
    //     }

    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTPHEADER => [
    //             'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
    //             'Accept: application/json',
    //             'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
    //             'Referer: https://www.ozon.ru/',
    //             'Sec-Ch-Ua: "Chromium";v="122", "Not(A:Brand";v="24", "Google Chrome";v="122"',
    //             'Sec-Ch-Ua-Mobile: ?0',
    //             'Sec-Ch-Ua-Platform: "Windows"',
    //         ],
    //         CURLOPT_ENCODING => "gzip, deflate, br",
    //         CURLOPT_COOKIEJAR => $cookieFile,
    //         CURLOPT_COOKIEFILE => $cookieFile,
    //     ]);

    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);

    //     if ($httpCode === 403 || $httpCode === 307) {
    //         error_log("Ozon blocking: HTTP Code - {$httpCode}. Stop sending requestes.");
    //         return [];
    //     }

    //     $data = json_decode($response, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);
    //     if (!$data) {
    //         throw new Exception("JSON error: " . json_last_error() . "." . json_last_error_msg());
    //     }

    //     return $data ?? [];
    // }
}
